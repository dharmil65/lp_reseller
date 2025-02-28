<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ResellerApiController extends Controller
{
    public function updateCommission(Request $request)
    {
        $user = auth()->user();

        if (!$request->commission || $request->commission < 0 || $request->commission > 100) {
            return response()->json(['success' => false, 'message' => 'Enter a valid commission value!'], 400);
        }

        $oldCommission = DB::connection('phpfmv_gpmarketplace')
            ->table('resellers')
            ->where('name', $request->user_name)
            ->value('commission_value');

        // dd($oldCommission);

        if (is_null($oldCommission)) {
            return response()->json(['success' => false, 'message' => 'User not found!'], 404);
        }

        $updated = DB::connection('lp_reseller')
            ->table('resellers')
            ->where('name', $request->user_name)
            ->update(['commission_value' => $request->commission]);

        if ($updated) {
            $updatedReseller = DB::connection('lp_reseller')
                ->table('resellers')
                ->where('name', $request->user_name)
                ->first();

            Session::put('checkInReseller', $updatedReseller);

            $jsonArray = escapeshellarg(json_encode($request->all()));
            $additionalParam = "resellerCommissionUpdatedMail";
            $command = 'php ' . base_path('artisan') . ' background:mailSend ' . $jsonArray . ' ' . $additionalParam . '> /dev/null 2>&1 &';
            exec($command);

            return response()->json(['success' => true, 'message' => 'Commission updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to update commission.'], 500);
    }
}