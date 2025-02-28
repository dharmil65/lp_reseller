<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ResellerController extends Controller
{
    public function index()
    {
        return view('reseller.dashboard');
    }

    public function viewSettings()
    {
        return view('reseller.settings');
    }

    public function viewOrders()
    {
        return view('reseller.orders');
    }

    public function viewPriceChangePage()
    {
        return view('reseller.price_change_list');
    }

    public function updateCommissionReseller(Request $request)
    {
        $checkInUsers = Session::get('checkInUsers');
        if (!empty($request->commission) && !empty($request->user_name)) {
            $request->merge(['old_commission_price' => DB::connection('lp_reseller')->table('resellers')->where('name', $request->user_name)->value('commission_value')]);
            $update = DB::connection('lp_reseller')->table('resellers')->where('name', $request->user_name)->update(['commission_value' => $request->commission]);
            if ($update) {
                $updatedReseller = DB::connection('lp_reseller')->table('resellers')->where('name', $request->user_name)->first();

                Session::put('checkInReseller', $updatedReseller);
                
                $jsonArray = escapeshellarg(json_encode($request->all()));
                $additionalParam = "resellerCommissionUpdatedMail";
                $command = 'php ' . base_path('artisan') . ' background:mailSend ' . $jsonArray . ' ' . $additionalParam . '> /dev/null 2>&1 &';
                exec($command);

                return response()->json(array('success' => true, 'message' => 'Commission price updated!'));
            }
        } else {
            return response()->json(array('success' => false, 'message' => 'Enter valid value!'));
        }
    }

    public function submitAPIKey(Request $request)
    {
        $input = $request->input('input');
        $user = $request->input('user');

        if (empty($input) || empty($user)) {
            return response()->json(['success' => false, 'message' => 'API key and user are required.']);
        }

        $resellerExists = DB::connection('lp_reseller')->table('resellers')->where('reseller_api_token', $input)->where('name', $user)->exists();
        // dd($resellerExists);

        if (!$resellerExists) {
            return response()->json(['success' => false, 'message' => 'Please enter a valid API key.']);
        }

        $commissionValue = DB::connection('lp_reseller')->table('resellers')->where('name', $user)->value('commission_value');
        $commissionMultiplier = 1 + ($commissionValue / 100);

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

        $data->transform(function ($record) use ($commissionMultiplier) {
            $record->guest_post_price = (!is_null($record->guest_post_price) && $record->guest_post_price != 0)
                ? "$" . round($record->guest_post_price * $commissionMultiplier)
                : 0;
            $record->linkinsertion_price = (!is_null($record->linkinsertion_price) && $record->linkinsertion_price != 0)
                ? "$" . round($record->linkinsertion_price * $commissionMultiplier)
                : 0;
            return $record;
        });

        return response()->json([
            'success' => true,
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }
}