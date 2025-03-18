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
                $checkInUsers = DB::table('users')->where('email', $request->email)->where('is_reseller', 1)->first();
                $checkInReseller = DB::table('resellers')->where('email', $request->email)->first();
                if ($checkInReseller) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Reseller login successful',
                        'reseller_id' => $checkInReseller->id,
                        'reseller_name' => $checkInReseller->name,
                        'user_data' => $checkInUsers ?? null,
                        'redirect_url' => route('client_marketplace') // Add redirect URL
                    ]);
                }
            }
        } catch (Exception $e) {
            \Log::error(['error while verifying user: ' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        }

        if ($resellerUser && Hash::check($request->login_password, $resellerUser->password)) {
            $authUser = new ResellerAuthUser($resellerUser);
            Auth::setUser($authUser);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id' => $resellerUser->id,
                    'name' => $resellerUser->name,
                    'email' => $resellerUser->email,
                ],
                'redirect_url' => route('client_marketplace')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 400);
    }
}