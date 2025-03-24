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

class LoginAPIController extends Controller
{
    public function apiLogin(Request $request)
    {
        try {
            $resellerUser = DB::table('reseller_users')->where('email', $request->email)->first();
   
            if (!$resellerUser) {
                $checkInReseller = DB::connection('lp_own_db')->table('resellers')->where('email', $request->email)->first();

                if ($checkInReseller && Hash::check($request->login_password, $checkInReseller->password)) {
                    return response()->json([
                        'success' => true, 
                        'message' => 'Reseller login successful!', 
                        'reseller_id' => $checkInReseller->id,
                        'reseller_name' => $checkInReseller->name,
                        'user' => [
                            'token' => null,
                        ],
                        'redirect_url' => route('reseller-home', [
                            'reseller_id' => $checkInReseller->id,
                            'reseller_name' => urlencode($checkInReseller->name)
                        ])
                    ]);
                }
            }

            if ($resellerUser && Hash::check($request->login_password, $resellerUser->password)) {
                $authUser = new ResellerAuthUser($resellerUser);
                Auth::setUser($authUser);
                $token = $resellerUser->remember_token;
                if (!$token) {
                    $token = Str::random(30);
                    DB::table('reseller_users')->where('id', $resellerUser->id)->update([
                        'remember_token' => $token
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $resellerUser->id,
                        'name' => $resellerUser->name,
                        'email' => $resellerUser->email,
                        'token' => $token,
                    ],
                    'redirect_url' => route('marketplace')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 400);

        } catch (Exception $e) {
            \Log::error(['error while verifying user: ' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Invalid Credentials'], 500);
        }
    }
}