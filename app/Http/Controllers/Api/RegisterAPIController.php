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

class RegisterAPIController extends Controller
{
    public function clientRegister(Request $request)
    {
        $resellerData = [];
        $resellerId = 1;

        try {
            $domain = rtrim($request->getScheme() . '://' . parse_url($request->fullUrl(), PHP_URL_HOST), '/');
            $checkDomainInReseller = DB::table('resellers')
                ->whereRaw("TRIM(TRAILING '/' FROM domain) = ?", [$domain])
                ->first();

            if ($checkDomainInReseller) {
                $resellerId = $checkDomainInReseller->id;
            }

            $checkIfReseller = DB::table('resellers')->where('email', $request->email)->exists();
            if ($checkIfReseller) {
                return response()->json(['message' => 'Email already registered as a reseller. Please login.'], 409);
            }

            if (DB::table('reseller_users')->where('email', $request->email)->exists()) {
                return response()->json(['success' => false, 'message' => 'Email already registered. Please login.', 'redirect_url' => route('login-client')]);
            }

            $resellerData = [
                'reseller_id' => $resellerId,
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
                DB::connection('lp_own_db')->table('users')->insert([
                    'name' => $resellerData['name'],
                    'email' => $resellerData['email'],
                    'password' => bcrypt($resellerData['password']),
                    'register_from' => "reseller_client",
                    'popup_status' => 1,
                    'stage' => "total",
                    'billing_country_name' => ''
                ]);
            }

        } catch (Exception $e) {
            \Log::error(['error while adding reseller in another database' => $e->getMessage()]);
            return response()->json(['error' => 'Registration failed. Please try again.'], 500);
        }

        if (!empty($resellerData)) {
            try {
                $last_id = DB::table('wallets')->max('id') ?? 0;
                $unique_transaction_id = 'REWARD25-' . date('Ymd') . ($last_id + 1);
                $unique_order_id = $this->generateOrderId();

                while (DB::table('wallets')->where('transaction_id', $unique_transaction_id)->exists()) {
                    $last_id++;
                    $unique_transaction_id = 'REWARD25-' . date('Ymd') . $last_id;
                }

                DB::table('wallets')->insert([
                    'user_id' => $userId,
                    'reseller_id' => 1,
                    'transaction_id' => $unique_transaction_id,
                    'admin_credit_debit' => 'debit',
                    'added_desc' => 'Signup Bonus',
                    'table_type' => 'fund',
                    'amount' => 100,
                    'total' => 100,
                    'description' => 'Bonus Added',
                    'order_id' => $unique_order_id,
                    'credit_or_debit' => 'Credit',
                    'provider' => 'wallet',
                    'status' => 'complete',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                return response()->json(['success' => true, 'message' => 'Registration successful!', 'redirect_url' => route('login-client')]);
            } catch (Exception $e) {
                \Log::error(['error while adding wallet transaction' => $e->getMessage()]);
                return response()->json(['error' => 'Wallet transaction failed.'], 500);
            }
        }

        return response()->json(['error' => 'Something went wrong.'], 500);
    }

    private function generateOrderId()
    {
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