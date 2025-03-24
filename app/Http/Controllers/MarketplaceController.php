<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Redirect;
use Illuminate\Support\Facades\Http;
use DateTime;
use DOMDocument;
use GuzzleHttp\Client;
use DOMXPath;
use Log;
use Yajra\DataTables\DataTables;
use App\Model\Reseller;
use App\Model\ResellerUser;
use Illuminate\Support\Str;
use App\Auth\ResellerAuthUser;

class MarketplaceController extends Controller
{
    public function marketplaceView()
    {
        return view('client_marketplace');
    }
    
    public function logout(Request $request)
    {
        return redirect()->route('register-client');
    }

    public function cartDetailPageView(Request $request)
    {
        $walletBalance = $request->query('walletBalance', 0);
        $cartTotal = $request->query('cartTotal', 0);
        $userid = $request->query('userid', null);

        $getCartData = DB::table('carts')
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
            ->where('carts.advertiser_id', $userid)
            ->get()
            ->toArray();

        $cartTotalAmount = number_format(array_sum(array_column($getCartData, 'total')));

        return view('client_cart', compact('walletBalance', 'cartTotal', 'userid', 'getCartData', 'cartTotalAmount'));
    }

    public function clientOrdersView()
    {
        return view('client_orders');
    }
}