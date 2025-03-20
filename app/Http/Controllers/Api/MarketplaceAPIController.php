<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\URL;
use App\Auth\ResellerAuthUser;

class MarketplaceAPIController extends Controller
{
    public function fetchMarketplaceData(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        if (!$authorizationHeader || !Str::startsWith($authorizationHeader, 'Bearer ')) {
            return response()->json([
                'error' => 'Unauthorized. Token is required.',
                'logout' => true
            ], 401);
        }

        $token = str_replace('Bearer ', '', $authorizationHeader);
        $user = DB::table('reseller_users')->where('remember_token', $token)->first();

        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized. Invalid token.',
                'logout' => true
            ], 401);
        }

        $userId = $user->id;

        $cartData = DB::table('carts')
            ->select('reseller_id', 'status', 'advertiser_id', 'website_id')
            ->where('advertiser_id', $userId)
            ->get()
            ->toArray();

        $cartsTotal = DB::table('carts')
            ->where('advertiser_id', $userId)
            ->where('status', 0)
            ->count();

        $cartStatus = array_column($cartData, 'status', 'website_id');

        $walletBalance = DB::table('wallets')
            ->where('user_id', $userId)
            ->where('status', 'complete')
            ->orderBy('id', 'desc')
            ->pluck('total')
            ->first();

        $marketplaceType = $request->input('marketplaceType');
        $pagePerSize = $request->input('page_per_size', 25);
        $page = $request->has('page') ? $request->input('page', 1) : 1;
        $offset = ($page - 1) * $pagePerSize;
        $search = $request->input('search');
        $orderBy = $request->input('orderBy', 'id');
        $orderDir = $request->input('orderDir', 'asc');

        $query = DB::connection('lp_own_db')->table('advertiser_marketplace');

        if ($marketplaceType == 1) {
            $query->whereNotNull('forbiddencategories')
                ->where('forbiddencategories', '!=', '')
                ->where(function ($data) {
                    $data->whereNotNull('forbidden_category_guest_post_price')
                        ->orWhereNotNull('forbidden_category_linkinsertion_price');
                });
        } else {
            $query->whereNotNull('category')
                ->where('category', '!=', '')
                ->where(function ($data) {
                    $data->whereNotNull('guest_post_price')
                        ->orWhereNotNull('linkinsertion_price');
                });
        }

        $totalWebsiteData = $query->distinct()->count();

        if ($offset >= $totalWebsiteData) {
            $offset = 0;
        }

        $websiteData = $query->distinct()->skip($offset)->take($pagePerSize)->get();
        $totalPages = ceil($totalWebsiteData / $pagePerSize);

        return response()->json([
            'cartStatus' => $cartStatus,
            'cartsTotal' => $cartsTotal,
            'walletBalance' => $walletBalance,
            'success' => !$websiteData->isEmpty(),
            'perPageCount' => $websiteData->count(),
            'total' => $totalWebsiteData,
            'totalPages' => $totalPages,
            'data' => $websiteData,
            'message' => $websiteData->isEmpty() ? 'No data found' : '',
        ], $websiteData->isEmpty() ? 404 : 200);
    }

    public function cartStore(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        $user = DB::table('reseller_users')->where('remember_token', $token)->first();

        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized. Please log in again.',
                'logout' => true
            ], 401);
        }

        $advertiser_id = $user->id ?? null;
        $reseller_id = $request->resellerId ?? 1;
        $marketplaceType = $request->marketplaceType;
        $website_id = $request->website_id;

        if ($request->action == 'add') {
            $cartItem = DB::table('carts')
                ->where('advertiser_id', $advertiser_id)
                ->where('website_id', $website_id)
                ->where('marketplace_type', $marketplaceType)
                // ->where('project_id', auth()->user()->current_project_selected)
                ->first();

            if ($cartItem) {
                if ($cartItem->status == 1) {
                    DB::table('carts')
                        ->where('advertiser_id', $advertiser_id)
                        ->where('website_id', $website_id)
                        ->where('marketplace_type', $marketplaceType)
                        ->update(['status' => 0]);
                } 
            } else {
                $websiteData = DB::connection('lp_own_db')->table('advertiser_marketplace')
                    ->select(
                        'guest_post_price', 'linkinsertion_price',
                        'forbidden_category_guest_post_price', 'forbidden_category_linkinsertion_price',
                        'without_commission_guest_post_price', 'without_commission_linkinsertion_price',
                        'without_commission_fc_guest_post_price', 'without_commission_fc_linkinsertion_price'
                    )
                    ->where('website_id', $website_id)
                    ->first();

                    $guestPostPrice = $marketplaceType == 1 ? $websiteData->forbidden_category_guest_post_price : $websiteData->guest_post_price;
                    $linkInsertionPrice = $marketplaceType == 1 ? $websiteData->forbidden_category_linkinsertion_price : $websiteData->linkinsertion_price;
                    $withoutCommissionGuestPostPrice = $marketplaceType == 1 ? $websiteData->without_commission_fc_guest_post_price : $websiteData->without_commission_guest_post_price;
                    $withoutCommissionLinkInsertionPrice = $marketplaceType == 1 ? $websiteData->without_commission_fc_linkinsertion_price : $websiteData->without_commission_linkinsertion_price;

                DB::table('carts')->insert([
                    'advertiser_id' => $advertiser_id,
                    'reseller_id' => $reseller_id ?? 1,
                    'website_id' => $website_id,
                    // 'source' => 'competitorsBacklinkAnalysis',
                    'price' => $guestPostPrice,
                    'link_insertion_price' => $linkInsertionPrice,
                    'wihthout_commission_guest_post_price' => $withoutCommissionGuestPostPrice,
                    'wihthout_commission_linkinsertion_price' => $withoutCommissionLinkInsertionPrice,
                    'total' => $guestPostPrice ?? $linkInsertionPrice,
                    'status' => 0,
                    'marketplace_type' => $marketplaceType,
                    // 'project_id' => auth()->user()->current_project_selected,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            DB::table('carts')
                ->where('website_id', $website_id)
                ->where('marketplace_type', $marketplaceType)
                // ->where('project_id', auth()->user()->current_project_selected)
                ->delete();
        }

        $cartTotal = DB::table('carts')
            ->where('advertiser_id', $advertiser_id)
            ->where('status', 0)
            ->count();

        session(['end_client_cart_total' => $cartTotal]);

        return response()->json([
            'success' => 'true', 
            'message' => $request->action == 'add' ? 'Added to cart successfully' : 'Removed from cart successfully',
            'cartTotal' => $cartTotal
        ]);
    }

    public function cartShowEndClient(Request $request)
    {
        // dd($request->all());
        $token = $request->bearerToken();
        if (!$token || !Auth::guard('api')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id = $request->end_client_id ?? null;
        $email = DB::connection('lp_reseller')->table('reseller_users')->where('id', $id)->value('email');
        $userId = DB::connection('lp_reseller')->table('users')->where('email', $email)->value('id');
        $walletBalance = DB::connection('lp_reseller')->table('wallets')->where('user_id', $userId)->where('status', 'complete')->orderBy('id', 'desc')->pluck('total')->first();
        $getCartData = DB::connection('lp_reseller')->table('carts')
            ->leftJoin('phpfmv_gpmarketplace.websites', 'carts.website_id', '=', 'websites.id')
            ->leftJoin('phpfmv_gpmarketplace.users', 'websites.publisher_id', '=', 'users.id')
            ->select([
                'carts.id as cart_id', 'carts.reseller_id', 'carts.advertiser_id', 'carts.website_id as cart_web_id',
                'carts.status as cart_status', 'carts.total', 'carts.content_writter', 'carts.instruction',
                'carts.title', 'carts.expert_price', 'carts.keyword', 'carts.reference', 'carts.category_id',
                'carts.brief_note', 'carts.attachment', 'carts.price', 'carts.expert_price_id', 'carts.choose_content',
                'carts.writting_style', 'carts.preferred_voice', 'carts.refrence_link', 'carts.anchor_text',
                'carts.blog_url', 'carts.link_insertion_price', 'carts.other_category_price', 'carts.price_changed',
                'carts.tag_used', 'carts.quantity_no', 'carts.prefered_language', 'carts.target_audience',
                'carts.marketplace_type', 'carts.wihthout_commission_guest_post_price',
                'carts.wihthout_commission_linkinsertion_price', 'carts.project_id', 'carts.source',
                'carts.anchor_text_1', 'carts.anchor_text_2', 'carts.anchor_text_3', 'carts.anchor_text_4',
                'carts.target_url_1', 'carts.target_url_2', 'carts.target_url_3', 'carts.target_url_4',
                'carts.language', 'carts.deleted_at',
                'websites.id as website_id', 'websites.website_url', 'websites.host_url',
                'websites.article_count', 'websites.status as website_status',
                'websites.publisher_id', 'websites.dofollow_link', 'websites.nofollow_link',
                'websites.deleted_at as website_deleted',
                'users.deleted_at as userDelete', 'users.vacation_mode', 'users.is_active'
            ])
            ->where('carts.advertiser_id', $id)
            ->get()
            ->toArray();

        return response()->json(['success' => true, 'data' => $getCartData, 'walletBalance' => $walletBalance]);
    }

}