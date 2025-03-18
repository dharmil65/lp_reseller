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
        $userId = DB::table('reseller_users')->where('email', session('email'))->value('id');

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
}