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
            
            if (!$reseller_id || empty($reseller_id) || $reseller_id == null) {
                return response()->json(['error' => 'Unauthorized', 'redirect_url' => route('reseller-home')], 401);
            }

            $orders = DB::connection('lp_own_db')
                ->table('order_attributes')
                ->join('websites', 'websites.id', 'order_attributes.website_id')
                ->join('orders', 'orders.id', 'order_attributes.order_id')
                ->where('reseller_id', $reseller_id)
                ->select('order_attributes.created_at', 'order_attributes.order_lable', 'order_attributes.reseller_order_lable', 'order_attributes.website_id', 'order_attributes.content_writter', 'order_attributes.Preferred_language', 'order_attributes.due_date', 'order_attributes.due_time', 'order_attributes.status', 'order_attributes.price', 'order_attributes.total', 'order_attributes.id as order_attr_id', 'websites.publisher_id', 'websites.website_url', 'websites.host_url', 'orders.advertiser_id')
                ->orderBy('order_attributes.id', 'desc');

            if ($request->has('status') && $request->status !== 'all') {
                $orders->where('order_attributes.status', $request->status);
            }

            // $fetchResellerEmail = DB::connection('lp_own_db')->table('resellers')->where('id', $reseller_id)->value('email');
            // $fetchUserID = DB::connection('lp_own_db')->table('users')->where('email', $fetchResellerEmail)->value('id');

            return DataTables::of($orders)
                ->editColumn('created_at', function ($order) {
                    return date("d-m-Y", strtotime($order->created_at));
                })
                ->editColumn('total', function ($order) {
                    return $order->total ? '$' . number_format($order->total, 0) : '--';
                })
                ->editColumn('website_id', function ($order) {
                    $host_url = trim($order->host_url);
                    $protocol = (str_starts_with($host_url, 'http://') || str_starts_with($host_url, 'https://')) ? '' : 'https://';
                    return '<a href="' . $protocol . $host_url . '" target="_blank">' . $host_url . '</a>';
                })
                ->editColumn('content_writter', function ($order) {
                    $content_map = [
                        'provide_content' => 'Guest Post',
                        'link_insertion' => 'Link Insertion',
                        'expert_writter' => 'Content and Guest Post',
                    ];
                    return $content_map[$order->content_writter] ?? '-';
                })
                ->editColumn('status', function ($order) {
                    $statusLabels = [
                        1 => 'New',
                        2 => 'In Progress',
                        5 => 'Delayed',
                        7 => 'Delivered',
                        6 => 'Completed',
                        0 => 'Rejected',
                    ];
                    return $statusLabels[$order->status] ?? '--';
                })
                ->addColumn('chat', function ($order) {
                    return '<a href="javascript:void(0);" class="chat-icon" 
                        data-userid="' . $order->advertiser_id . '" 
                        data-orderlabel="' . $order->order_lable . '" 
                        data-publisher="' . $order->publisher_id . '" 
                        data-oaid="' . $order->order_attr_id . '" 
                        data-status="1">
                        <img src="' . asset('assets/images/comment-icon.png') . '" alt="chat">
                        </a>';
                })
                ->rawColumns(['website_id', 'chat'])
                ->toJson();

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}