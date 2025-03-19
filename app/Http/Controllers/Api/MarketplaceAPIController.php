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

        $cart_data = DB::table('carts')
            ->select('reseller_id', 'status', 'advertiser_id', 'website_id')
            ->where('advertiser_id', $userId)
            ->get();

        $cartsTotal = DB::table('carts')
            ->where('advertiser_id', $userId)
            ->where('status', 0)
            ->count();

        $cartStatus = $cart_data->pluck('status', 'website_id')->toArray();

        $walletBalance = DB::table('wallets')
            ->where('user_id', $userId)
            ->where('status', 'complete')
            ->orderBy('id', 'desc')
            ->value('total');

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
        $advertiser_id = Auth::id() ?? $request->clientId;
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
}