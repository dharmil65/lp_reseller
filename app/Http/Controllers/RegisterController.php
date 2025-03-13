<?php

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

class RegisterController extends Controller
{

    public function showClientRegisterForm()
    {
        return view('register');
    }

    public function clientRegister(Request $request)
    {
        $resellerData = [];
        $resellerId = 1;

        try {
            $domain = rtrim(request()->getScheme() . '://' . parse_url(URL::full(), PHP_URL_HOST), '/');

            $checkDomainInReseller = DB::table('resellers')
                ->whereRaw("TRIM(TRAILING '/' FROM domain) = ?", [$domain])
                ->first();

            if (isset($checkDomainInReseller) && !empty($checkDomainInReseller)) {
                $resellerId = $checkDomainInReseller->id;
            }

            $checkIfReseller = DB::table('resellers')->where('email', $request->email)->exists();
            if ($checkIfReseller) {
                $checkInUsers = DB::table('users')->where('email', $request->email)->where('is_reseller', 1)->first();
                $checkInReseller = DB::table('resellers')->where('email', $request->email)->first();

                if ($checkInUsers && $checkInReseller) {

                    Session::put('reseller_id', $checkInReseller->id);
                    Session::put('reseller_name', $checkInReseller->name);
                    
                    Session::put('checkInUsers', $checkInUsers);
                    Session::put('checkInReseller', $checkInReseller);

                    return redirect()->route('reseller-home');
                } else {
                    return redirect()->route('login-client');
                }
            }

            if ($request->email) {
                $resellerUser = DB::table('reseller_users')->where('email', $request->email)->first();
                if ($resellerUser) {
                    Session::put('auth_user', (array) $resellerUser);
                    Auth::setUser(new ResellerAuthUser($resellerUser));
                    return redirect()->route('end_user_marketplace');
                }
            }

            $resellerData = [
                'reseller_id' => $resellerId ?? 1,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $userId = DB::table('reseller_users')->insertGetId($resellerData);
            
            $resellerDataForLpOwnDb = $resellerData;
            $resellerDataForLpOwnDb['reseller_userid'] = $resellerDataForLpOwnDb['reseller_id'];
            unset($resellerDataForLpOwnDb['reseller_id']);
            
            $checkIfExistsInLPReseller = DB::connection('lp_own_db')->table('reseller_users')->where('email', $request->email)->exists();
            $checkIfExistsInLPUsers = DB::connection('lp_own_db')->table('users')->where('email', $request->email)->exists();
            if (!$checkIfExistsInLPReseller && !$checkIfExistsInLPUsers) {
                
                DB::connection('lp_own_db')->table('reseller_users')->insert($resellerDataForLpOwnDb);

                $user = [
                    'name' => $resellerData['name'],
                    'email' => $resellerData['email'],
                    'password' => bcrypt($resellerData['password']),
                    'register_from'=> "reseller_client",
                    'popup_status' => 1,
                    'stage' => "total",
                    'billing_country_name' => ''
                ];

                DB::connection('lp_own_db')->table('users')->insert($user);
            }
        } catch (Exception $e) {
            \Log::error(['error while adding reseller in another database: ' => $e->getMessage()]);
        }

        if (!empty($resellerData)) {
            try {
                $last_id = DB::table('wallets')->max('id');
                $last_id = $last_id ? $last_id + 1 : 1;
                
                $unique_transaction_id = 'REWARD25-' . date('Ymd') . $last_id;
                $unique_order_id = $this->generateOrderId();

                while (DB::table('wallets')->where('transaction_id', $unique_transaction_id)->exists()) {
                    $last_id++;
                    $unique_transaction_id = 'REWARD25-' . date('Ymd') . $last_id;
                }

                $wal = [
                    'user_id' => $userId ?? null,
                    'reseller_id' => 1,
                    'transaction_id' => $unique_transaction_id ?? null,
                    'admin_credit_debit' => 'debit',
                    'added_desc' => 'Signup Bonus',
                    'table_type' => 'fund',
                    'amount' => 100,
                    'total' => 100,
                    'description' => 'Bonus Added',
                    'order_id' => $unique_order_id ?? null,
                    'credit_or_debit' => 'Credit',
                    'provider' => 'wallet',
                    'status' => 'complete',
                ];

                DB::table('wallets')->insert($wal);

                return redirect()->route('login-client')->with(['success' => 'Registration successful!']);
            } catch (Exception $e) {
                \Log::error(['error while adding reseller in another database: ' => $e->getMessage()]);
            }
        }
    }

    private function generateOrderId() {
        do {
            $wallet = DB::table('wallets')->orderBy('id', 'desc')->first(['id']);
            $newId = $wallet ? $wallet->id : 0;
            $randomDigits = rand(10000, 99999);
            $unique_order_id = '#10' . str_pad($newId + 1, 8, "0", STR_PAD_LEFT) . $randomDigits;
            $exists = DB::table('wallets')->where('order_id', $unique_order_id)->exists();
        } while ($exists);
    
        return $unique_order_id;
    }
}