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
        $userId = DB::table('reseller_users')->where('email', session('email'))->value('id');
        
        $cart_data = DB::table('carts')->select('reseller_id', 'status', 'advertiser_id', 'website_id')->where('advertiser_id', $userId)->get()->toArray();

        $cartsTotal = DB::table('carts')
            ->where('advertiser_id', $userId)
            ->where('status', 0)
            ->count();

        $cartStatus = array_column($cart_data, 'status', 'website_id');

        $walletBalance = DB::table('wallets')->where('user_id', $userId)->where('status', 'complete')->orderBy('id', 'desc')->pluck('total')->first();

        return view('client_marketplace', compact('cartStatus', 'cartsTotal', 'walletBalance'));
    }
    
    public function logout(Request $request)
    {
        return view('register');
    }
}