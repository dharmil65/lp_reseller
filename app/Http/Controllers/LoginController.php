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

    public function login(Request $request)
    {
        try {
            $resellerUser = DB::table('reseller_users')->where('email', $request->email)->first();
            
            if (!$resellerUser) {
                $checkInUsers = DB::table('users')->where('email', $request->email)->where('is_reseller', 1)->first();
                $checkInReseller = DB::table('resellers')->where('email', $request->email)->first();
    
                if ($checkInReseller) {

                    Session::put('reseller_id', $checkInReseller->id);
                    Session::put('reseller_name', $checkInReseller->name);
                    
                    Session::put('checkInUsers', $checkInUsers);
                    Session::put('checkInReseller', $checkInReseller);

                    return redirect()->route('reseller-home');
                }
            }
        } catch (Exception $e) {
            \Log::error(['error while verifying user: ' => $e->getMessage()]);
        }

        if ($resellerUser && Hash::check($request->login_password, $resellerUser->password)) {
            $query = DB::connection('lp_own_db')->table('advertiser_marketplace');
            
            $totalRecords = $query->count();
            $start = $request->input('start', 0);
            $length = $request->input('length', 25);
            $searchValue = $request->input('search.value');

            if ($searchValue) {
                $query->where('host_url', 'like', '%' . $searchValue . '%');
            }

            $filteredRecords = $query->count();
            $data = $query->offset($start)->limit($length)->get();
            
            $domain = rtrim(request()->getScheme() . '://' . parse_url(URL::full(), PHP_URL_HOST), '/');
                    
            $checkDomainInReseller = DB::connection('lp_reseller')
                ->table('resellers')
                ->whereRaw("TRIM(TRAILING '/' FROM domain) = ?", [$domain])
                ->first();
            
            if ($checkDomainInReseller == null) {
                Session::put('reseller_id', 1);
                Session::put('reseller_name', 'Solomon Hoover');
            } else {
                Session::put('reseller_id', $checkDomainInReseller->id);
                Session::put('reseller_name', $checkDomainInReseller->name);
            }
            
            Session::put('email', $request->email);
            Session::put('data', $data);
            Session::put('filteredRecords', $filteredRecords);

            $user = (object) [
                'id' => $resellerUser->id,
                'name' => $resellerUser->name,
                'email' => $resellerUser->email,
                'password' => $resellerUser->password
            ];

            Session::put('auth_user', (array) $resellerUser);
            
            $authUser = new ResellerAuthUser($resellerUser);
            Auth::setUser($authUser);

            return redirect()->route('client_marketplace');
        } else {
            return redirect()->back()->with(['error' => 'Invalid credentials!', 'redirectTo' => 'user-login']);
        }
    }
}