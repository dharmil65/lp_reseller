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
use Yajra\DataTables\DataTables;

class ResellerPanelAPIController extends Controller
{
    public function getResellerOrders(Request $request)
    {
        try {
            $reseller_id = $request->reseller_id;

            $orders = DB::connection('lp_own_db')
                ->table('order_attributes')
                ->where('reseller_id', $reseller_id)
                ->select('created_at', 'order_lable', 'reseller_order_lable', 'website_id', 'content_writter', 'Preferred_language', 'due_date', 'due_time', 'status', 'price', 'total')
                ->orderBy('id', 'desc');

            if ($request->has('status') && $request->status !== 'all') {
                $orders->where('status', $request->status);
            }

            return DataTables::of($orders)
                ->editColumn('created_at', function ($order) {
                    return date("d-m-Y", strtotime($order->created_at));
                })
                ->editColumn('total', function ($order) {
                    return $order->total ? '$' . number_format($order->total, 0) : '--';
                })
                ->editColumn('website_id', function ($order) {
                    $host_url = DB::connection('lp_own_db')
                        ->table('websites')
                        ->where('id', $order->website_id)
                        ->value('host_url');
                    if ($host_url) {
                        $host_url = trim($host_url);
                        $protocol = (str_starts_with($host_url, 'http://') || str_starts_with($host_url, 'https://')) ? '' : 'https://';
                        return '<a href="' . $protocol . $host_url . '" target="_blank">' . $host_url . '</a>';
                    }
                    return '-';
                })
                ->editColumn('content_writter', function ($order) {
                    $content_map = [
                        'provide_content' => 'Guest Post',
                        'link_insertion' => 'Link Insertion',
                        'expert_writter' => 'Content and Guest Post',
                    ];
                    return $content_map[$order->content_writter] ?? '-';
                })
                ->rawColumns(['website_id'])
                ->toJson();

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}