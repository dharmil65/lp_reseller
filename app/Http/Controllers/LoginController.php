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

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function resellerHomepage(Request $request)
    {
        try {
            $resellerId = $request->query('reseller_id');
            $resellerName = $request->query('reseller_name');

            $reseller = DB::connection('lp_own_db')->table('resellers')->where('name', $resellerName)->select('id', 'name', 'commission_value')->first();
            
            if (!$reseller) {
                \Log::error(['error while fetching reseller information: ' => $e->getMessage()]);
                return response()->json(['error' => 'Reseller not found!'], 500);
            }

            $totalResellerUsers = DB::table('reseller_users')->where('reseller_id', $resellerId)->count();
            $totalResellerOrders = DB::connection('lp_own_db')->table('order_attributes')->where('reseller_id', $resellerId)->count();

            return view('reseller_dashboard', compact('reseller', 'totalResellerUsers', 'totalResellerOrders', 'resellerId', 'resellerName'));
        } catch (Exception $e) {

            \Log::error(['error while fetching reseller homepage' => $e]);
            return redirect()->back();
        }
    }
}