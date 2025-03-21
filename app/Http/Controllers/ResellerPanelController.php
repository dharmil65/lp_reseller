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

class ResellerPanelController extends Controller
{
    public function resellerOrdersView(Request $request)
    {
        $statusCounts = DB::connection('lp_own_db')
            ->table('order_attributes')
            ->where('reseller_id', $request->query('reseller_id'))
            ->whereNull('deleted_at')
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $resellerId = $request->query('reseller_id');
        $resellerName = $request->query('reseller_name');

        return view('reseller_orders', compact('statusCounts', 'resellerId', 'resellerName'));
    }
}