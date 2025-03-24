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
use Validator;

class MarketplaceAPIController extends Controller
{
    public function fetchMarketplaceData(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        if (!$authorizationHeader || !Str::startsWith($authorizationHeader, 'Bearer ')) {
            return response()->json([
                'error' => 'Unauthorized. Token is required.',
                'logout' => true
            ], 401);
        }

        $token = str_replace('Bearer ', '', $authorizationHeader);
        $user = DB::table('reseller_users')->where('remember_token', $token)->first();

        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized. Invalid token.',
                'logout' => true
            ], 401);
        }

        $userId = $user->id;

        $cartData = DB::table('carts')
            ->select('reseller_id', 'status', 'advertiser_id', 'website_id')
            ->where('advertiser_id', $userId)
            ->get()
            ->toArray();

        $cartsTotal = DB::table('carts')
            ->where('advertiser_id', $userId)
            ->where('status', 0)
            ->count();

        $cartStatus = array_column($cartData, 'status', 'website_id');

        $walletBalance = DB::connection('lp_own_db')->table('wallets')
            ->where('end_client_id', $userId)
            ->where('status', 'complete')
            ->orderBy('id', 'desc')
            ->value('total');

        $marketplaceType = $request->input('marketplaceType');
        $pagePerSize = $request->input('page_per_size', 25);
        $page = $request->has('page') ? $request->input('page', 1) : 1;
        $offset = ($page - 1) * $pagePerSize;
        $search = $request->input('search');
        $orderBy = $request->input('orderBy', 'id');
        $orderDir = $request->input('orderDir', 'asc');

        $query = DB::connection('lp_own_db')->table('advertiser_marketplace');

        if ($marketplaceType == 1) {
            $query->whereNotNull('forbiddencategories')
                ->where('forbiddencategories', '!=', '')
                ->where(function ($data) {
                    $data->whereNotNull('forbidden_category_guest_post_price')
                        ->orWhereNotNull('forbidden_category_linkinsertion_price');
                });
        } else {
            $query->whereNotNull('category')
                ->where('category', '!=', '')
                ->where(function ($data) {
                    $data->whereNotNull('guest_post_price')
                        ->orWhereNotNull('linkinsertion_price');
                });
        }

        $totalWebsiteData = $query->distinct()->count();

        if ($offset >= $totalWebsiteData) {
            $offset = 0;
        }

        $websiteData = $query->distinct()->skip($offset)->take($pagePerSize)->get();
        $totalPages = ceil($totalWebsiteData / $pagePerSize);

        return response()->json([
            'cartStatus' => $cartStatus,
            'cartsTotal' => $cartsTotal,
            'walletBalance' => $walletBalance,
            'success' => !$websiteData->isEmpty(),
            'perPageCount' => $websiteData->count(),
            'total' => $totalWebsiteData,
            'totalPages' => $totalPages,
            'data' => $websiteData,
            'message' => $websiteData->isEmpty() ? 'No data found' : '',
        ], $websiteData->isEmpty() ? 404 : 200);
    }

    public function cartStore(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        $user = DB::table('reseller_users')->where('remember_token', $token)->first();

        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized. Please log in again.',
                'logout' => true
            ], 401);
        }

        $advertiser_id = $user->id ?? null;
        $reseller_id = $request->resellerId ?? 1;
        $marketplaceType = $request->marketplaceType;
        $website_id = $request->website_id;

        if ($request->action == 'add') {
            $cartItem = DB::table('carts')
                ->where('advertiser_id', $advertiser_id)
                ->where('website_id', $website_id)
                ->where('marketplace_type', $marketplaceType)
                // ->where('project_id', auth()->user()->current_project_selected)
                ->first();

            if ($cartItem) {
                if ($cartItem->status == 1) {
                    DB::table('carts')
                        ->where('advertiser_id', $advertiser_id)
                        ->where('website_id', $website_id)
                        ->where('marketplace_type', $marketplaceType)
                        ->update(['status' => 0]);
                } 
            } else {
                $websiteData = DB::connection('lp_own_db')->table('advertiser_marketplace')
                    ->select(
                        'guest_post_price', 'linkinsertion_price',
                        'forbidden_category_guest_post_price', 'forbidden_category_linkinsertion_price',
                        'without_commission_guest_post_price', 'without_commission_linkinsertion_price',
                        'without_commission_fc_guest_post_price', 'without_commission_fc_linkinsertion_price'
                    )
                    ->where('website_id', $website_id)
                    ->first();

                    $guestPostPrice = $marketplaceType == 1 ? $websiteData->forbidden_category_guest_post_price : $websiteData->guest_post_price;
                    $linkInsertionPrice = $marketplaceType == 1 ? $websiteData->forbidden_category_linkinsertion_price : $websiteData->linkinsertion_price;
                    $withoutCommissionGuestPostPrice = $marketplaceType == 1 ? $websiteData->without_commission_fc_guest_post_price : $websiteData->without_commission_guest_post_price;
                    $withoutCommissionLinkInsertionPrice = $marketplaceType == 1 ? $websiteData->without_commission_fc_linkinsertion_price : $websiteData->without_commission_linkinsertion_price;

                DB::table('carts')->insert([
                    'advertiser_id' => $advertiser_id,
                    'reseller_id' => $reseller_id ?? 1,
                    'website_id' => $website_id,
                    // 'source' => 'competitorsBacklinkAnalysis',
                    'price' => $guestPostPrice,
                    'link_insertion_price' => $linkInsertionPrice,
                    'wihthout_commission_guest_post_price' => $withoutCommissionGuestPostPrice,
                    'wihthout_commission_linkinsertion_price' => $withoutCommissionLinkInsertionPrice,
                    'total' => $guestPostPrice ?? $linkInsertionPrice,
                    'status' => 0,
                    'marketplace_type' => $marketplaceType,
                    // 'project_id' => auth()->user()->current_project_selected,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            DB::table('carts')
                ->where('website_id', $website_id)
                ->where('marketplace_type', $marketplaceType)
                // ->where('project_id', auth()->user()->current_project_selected)
                ->delete();
        }

        $cartTotal = DB::table('carts')
            ->where('advertiser_id', $advertiser_id)
            ->where('status', 0)
            ->count();

        session(['end_client_cart_total' => $cartTotal]);

        return response()->json([
            'success' => 'true', 
            'message' => $request->action == 'add' ? 'Added to cart successfully' : 'Removed from cart successfully',
            'cartTotal' => $cartTotal
        ]);
    }

    public function cartShowEndClient(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = DB::table('reseller_users')->where('remember_token', $token)->first();
        $id = $user->id ?? null;
        $cartTotal = DB::table('carts')->where('advertiser_id', $id)->where('status', 0)->count();
        $walletBalance = DB::connection('lp_own_db')->table('wallets')->where('end_client_id', $id)->where('status', 'complete')->orderBy('id', 'desc')->pluck('total')->first();

        return response()->json(['success' => true, 'walletBalance' => $walletBalance, 'cartTotal' => $cartTotal, 'userid' => $id]);
    }

    public function fetchEndClientCartData(Request $request)
    {
        $id = $request->endClientId ?? null;
        $cartDetails = DB::table('carts')->where('advertiser_id',  $id)->where('marketplace_type', $request->marketplace_type)->where('website_id', $request->website_id)->orderby('quantity_no', 'ASC')->get();
        $websiteDetail = DB::connection('lp_own_db')->table('advertiser_marketplace')->where('website_id', $request->website_id);
        if($request->marketplace_type == 0){
            $websiteDetail->whereNotNull('category');
        }else{
            $websiteDetail->whereNotNull('forbiddencategories');
        }
        $websiteDetail = $websiteDetail->first();
        $cartPriceCollection = $cartDetails->pluck('price');
        $cartPrice = $cartPriceCollection->filter()->isNotEmpty() ? $cartPriceCollection : null;
        $usersDetail = DB::connection('lp_own_db')->table('websites')->join('users', 'websites.publisher_id', 'users.id')->where('websites.id', $request->website_id)->select('users.id', 'users.deleted_at as userDeleted', 'users.vacation_mode', 'websites.deleted_at', 'users.is_active')->first();
        $returnHTML = view('cart_details', compact('cartDetails', 'usersDetail', 'websiteDetail', 'cartPrice'))->render();
        $guideline = $websiteDetail->guidelines;
        return response()->json(array('success' => true, 'html' => $returnHTML, 'message' => 'cart details', 'guideline' => $guideline));
    }

    public function provideCartDataEndClient(Request $request)
    {
        $cartDetails =  DB::table('carts')->where('id', $request->cart_id)->first();
        if ($request->type == 'provide_content') {
            $languageData = DB::connection('lp_own_db')->table('websites')->where('id', $cartDetails->website_id)->select('language')->first();
            $languageList = explode(',', $languageData->language);
            $cartListHtml =  view('provide_content_modal', compact('cartDetails','languageList'))->render();
            return response()->json(array('success' => true, 'cartListHtml' => $cartListHtml, 'cart data fetch','languageList' => $languageList));
        }
    }

    public function addQuantityDataEndClient(Request $request){
        $id = $request->end_client_id ?? null;

        if($request->type == "provide_content"){
            $validator = Validator::make($request->all(), [
            ]);
        }
        if ($validator->fails()) {
            array_push($cart_error, $validator->messages());
        }else{
            $website = DB::connection('lp_own_db')->table('websites')->where('id', $request->provide_content_website_id)->first();

            $cartData = DB::table('carts')->select('*')->where('website_id', $request->provide_content_website_id)
                ->where('advertiser_id', $id)->where('quantity_no', $request->provide_content_quantity)
                ->where('marketplace_type', $request->provide_content_marketplace_type)
                ->get();

            $guestPostPrice=$website->publishing_price;
            $linkInsertionPrice=$website->linkinsertion_price;
            $marketplace_type=(isset($website->category_type) && $website->category_type == null) ? '0' : $website->category_type;
              
            if($website->marketplace_guest_post_price == null || $website->marketplace_guest_post_price != null) {
                if($website->guest_post_commission_price!=null && $website->guest_post_value_addition == null) {
                    $guestPostPriceTotal = (ceil(($website->publishing_price * $website->guest_post_commission_price)/100)) + $website->publishing_price;
                }else if($website->guest_post_commission_price == null && $website->guest_post_value_addition!=null) {
                    $guestPostPriceTotal = $website->guest_post_value_addition + $website->publishing_price;
                }
            }else{
                $guestPostPriceTotal = $website->marketplace_guest_post_price;
            }
            
            $linkInsertionPriceTotal = null;
            if($website->marketplace_linkinsertion_price == null || $website->marketplace_linkinsertion_price != null){
                if($website->link_insertion_commission_price!=null && $website->link_insertion_value_addition == null){
                    $linkInsertionPriceTotal = (ceil(($website->linkinsertion_price * $website->link_insertion_commission_price)/100)) + $website->linkinsertion_price;
                }else if($website->link_insertion_commission_price == null && $website->link_insertion_value_addition!=null) {
                    $linkInsertionPriceTotal = $website->link_insertion_value_addition + $website->linkinsertion_price;
                }
            }else{
                $linkInsertionPriceTotal = $website->marketplace_linkinsertion_price;
            }

            if(count($cartData) > 0){
                if($request->type == 'provide_content'){
                    if($request->file('attachment')){
                        $file = $request->file('attachment');
                        $attachment= time() . rand(1, 99999) . '.' .$file->getClientOriginalExtension();
                        $data['attachment']=$attachment;
                    }
                    $data['content_writter']='provide_content';
                    $data['instruction']=$request->specialinstruction;
                    $data['wihthout_commission_guest_post_price']=$guestPostPrice;
                    $data['total']=$cartData[0]->total;
                    $data['language'] = $request->language ?? null;
                }

                $cart = DB::table('carts')->where('website_id',$request->provide_content_website_id)->where('quantity_no',$request->provide_content_quantity)
                ->where('advertiser_id',$id)->update($data);

                return response()->json(array('success' => true,'message'=>'Quantity store successfully'));
            }else{

                DB::table('carts')->insert([
                    'advertiser_id' => $id,
                    'website_id' => $website->id,
                    'status' => 0,
                    'content_writter' => ($request->type == 'provide_content') ? 'provide_content' : null,
                    'instruction' => ($request->type == 'provide_content') ? $request->specialinstruction : null,
                    'attachment' => ($request->attachment && isset($attachmentFilename) && $attachmentFilename != null) ? $attachmentFilename : null,
                    'wihthout_commission_guest_post_price' => ($request->type == 'provide_content') ? $guestPostPrice : null,
                    'total' => ($request->type == 'provide_content') ? ceil($guestPostPriceTotal) : null,
                    'price' => $guestPostPrice,
                    'link_insertion_price' => $linkInsertionPrice,
                    'quantity_no' => $request->provide_content_quantity,
                    'project_id' => Auth::user()->current_project_selected,
                    'marketplace_type' => $request->provide_content_marketplace_type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                return response()->json(array('success' => true, 'cart' =>$cart->id ,'message'=>'Quantity store successfully','total'=>number_format($cart->total)));
            }    
        }
    }

    public function endClientOrderSummary()
    {
        $id = request()->end_client_id ?? null;
        $pagetitle = 'Order Summary';
        $afflitateTotalDiscount = 0;
        $websiteId = [];

        $advertiserCartListing = DB::table('carts')
            ->where('advertiser_id', $id)
            ->whereNotNull('content_writter')
            ->where('status', 0)
            ->get()
            ->toArray();
            
        $websiteIds = collect($advertiserCartListing)->pluck('website_id')->unique()->toArray();

        $advertiserMarketplaces = DB::connection('lp_own_db')->table('advertiser_marketplace')
            ->whereIn('website_id', $websiteIds)
            ->get()
            ->keyBy('website_id')
            ->toArray();

        foreach ($advertiserCartListing as &$cart) {
            $websiteId = $cart->website_id;
            if (isset($advertiserMarketplaces[$websiteId])) {
                $cart->host_url = $advertiserMarketplaces[$websiteId]->host_url;
                $cart->website_url = $advertiserMarketplaces[$websiteId]->website_url;
                $cart->category = $advertiserMarketplaces[$websiteId]->category;
                $cart->forbiddencategory = $advertiserMarketplaces[$websiteId]->forbiddencategories;
            } else {
                $cart->host_url = null;
                $cart->website_url = null;
                $cart->category = null;
                $cart->forbiddencategory = null;
            }
        }

        $orderSummaryListing = [];
        $orderSummaryData = [];
        $orderSummaryDataForbidden = [];

        foreach ($advertiserCartListing as $value) {
            DB::table('carts')->where('id', $value->id)->update(['price_changed' => 0]);
            if ($value->marketplace_type == 0 || $value->marketplace_type == null) {
                $orderSummaryListing[$value->website_id][] = $value;
            } else {
                $orderSummaryDataForbidden[$value->website_id][] = $value;
            }
        }

        $total = $subtotal = $totalExpertPrice = 0;
        $orderSummaryListing = array_merge($orderSummaryListing, $orderSummaryDataForbidden);
        
        foreach ($orderSummaryListing as $key => $val) {
            $provideContent = $linkinsertion = $hirecontent = 0;
            $provideContent_total = $linkinsertion_total = $hirecontent_total = $expertPriceTotal = 0;
            $res = $orderSummaryListing[$key];

            for ($j = 0; $j < count($res); $j++) {
                $orderSummaryData[$key]['website_name'] = $res[$j]->host_url;
                $orderSummaryData[$key]['website_url'] = $res[$j]->website_url;
                $orderSummaryData[$key]['category'] = $res[$j]->category;
                $orderSummaryData[$key]['website_id'] = $res[$j]->website_id;
                $orderSummaryData[$key]['marketplace_type'] = $res[$j]->marketplace_type;
                $orderSummaryData[$key]['forbiddencategory'] = $res[$j]->forbiddencategory;

                if ($res[$j]->content_writter == 'provide_content') {
                    $provideContent += 1;
                    $provideContent_total += $res[$j]->total;
                }

                $orderSummaryData[$key]['quantity'] = [
                    'provide_content' => $provideContent,
                    'link_insertion' => $linkinsertion,
                    'hire_content' => $hirecontent
                ];

                $orderSummaryData[$key]['total'] = [
                    'provide_content' => $provideContent_total,
                    'link_insertion' => $linkinsertion_total,
                    'hire_content' => $hirecontent_total
                ];
            }
            $total += $provideContent_total + $linkinsertion_total + $hirecontent_total;
            $subtotal = $total;
            $totalExpertPrice += $expertPriceTotal;
        }

        $subtotal -= $totalExpertPrice;
        $balance = DB::connection('lp_own_db')->table('wallets')->where('end_client_id', $id)->where('status', 'complete')->orderBy('id', 'desc')->pluck('total')->first();

        if (count($orderSummaryData) > 0) {
            return view('client_order_summary', compact('pagetitle', 'orderSummaryData', 'total', 'subtotal', 'balance'));
        } else {
            return redirect()->back();
        }
    }

    public function endClientOrderPlace(Request $request)
    {
        $user_id = $request->end_client_id ?? null;
        $reseller_id = 1;
        $cartQuery = DB::table('carts')
            ->where('advertiser_id', $user_id)
            ->whereNotNull('content_writter')
            ->whereNull('project_id')
            ->where('status', 0);

        $cartDetails = $cartQuery->get()->map(function ($item) {
            return (array) $item;
        })->toArray();
        
        $advertiserCartListing = $cartDetails;
                
        $cartstotal = array_sum(array_column($cartDetails, 'total'));
        $cartId = array_column($cartDetails, 'id');
        $checkIfPriceChanged = in_array(1, array_column($cartDetails, 'price_changed'));
        $websiteIds = array_unique(array_column($cartDetails, 'website_id'));

        $grandTotal = $totalDiscount = $totalGPOrderTotal = 0;
        $unique = [];

        $resellerUserId = $user_id;
        $balance = DB::connection('lp_own_db')->table('wallets')->where('end_client_id', $user_id)->where('status', 'complete')->orderBy('id', 'desc')->pluck('total')->first();
        $data = [
            'advertiser' => DB::table('reseller_users')->where('id', $resellerUserId)->value('name'),
            'order_url' => url('/') . '/advertiser/order',
            'email' => $email ?? null
        ];

        $websiteId = !empty($advertiserCartListing) ? array_column($advertiserCartListing, "website_id") : [];

        $websiteData = DB::connection('lp_own_db')->table('websites')
            ->whereIn('id', $websiteId)
            ->get()
            ->keyBy('id');

        $orderNoti = [];
        $contentNoti = [];
        $publisherMail = [];
        $lowPrizeMail = [];
        $orderAttrMail = [];

        $getEmail = DB::table('reseller_users')->where('id', $user_id)->value('email');
        $getUserID = DB::connection('lp_own_db')->table('users')->where('email', $getEmail)->value('id');
        $ordercount =  DB::connection('lp_own_db')->table('orders')->where('advertiser_id', $getUserID)->select('orders.orderno_count')->orderBy('orders.orderno_count', 'desc')->first();
        $countOrder = $ordercount == null ? 1 : $ordercount->orderno_count + 1;

        $orderId = DB::connection('lp_own_db')->table('orders')->insertGetId([
            'order_no' => $this->generateOrderId(),
            'advertiser_id' => $getUserID,
            'status' => 1,
            'reseller_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'orderno_count' => $countOrder ?? 0,
        ]);
        
        for ($i = 0; $i < count($advertiserCartListing); $i++) {

            $website = $websiteData[$advertiserCartListing[$i]['website_id']];

            $commission_price = $website->commission_price;
            $value_addition = $website->value_addition;

            $guest_post_commission_price = isset($website->guest_post_commission_price) && $website->guest_post_commission_price > 0 ? $website->guest_post_commission_price : null;
            $guest_post_value_addition = isset($website->guest_post_value_addition) && $website->guest_post_value_addition > 0 ? $website->guest_post_value_addition : null;

            $link_insertion_commission_price = isset($website->link_insertion_commission_price) && $website->link_insertion_commission_price > 0 ? $website->link_insertion_commission_price : null;
            $link_insertion_value_addition = isset($website->link_insertion_value_addition) && $website->link_insertion_value_addition > 0 ? $website->link_insertion_value_addition : null;

            $fc_guest_post_commission_price = isset($website->fc_guest_post_commission_price) && $website->fc_guest_post_commission_price > 0 ? $website->fc_guest_post_commission_price : null;
            $fc_guest_post_value_addition = isset($website->fc_guest_post_value_addition) && $website->fc_guest_post_value_addition > 0 ? $website->fc_guest_post_value_addition : null;

            $fc_link_insertion_commission_price = isset($website->fc_link_insertion_commission_price) && $website->fc_link_insertion_commission_price > 0 ? $website->fc_link_insertion_commission_price : null;
            $fc_link_insertion_value_addition = isset($website->fc_link_insertion_value_addition) && $website->fc_link_insertion_value_addition > 0 ? $website->fc_link_insertion_value_addition : null;

            $tat = $website->tat;
            $expert_days = $days = 0;
            $total_price=0;
            $discount_amount = 0;
            $totalGpOrderAmt = $advertiserCartListing[$i]['total'] - $advertiserCartListing[$i]['expert_price'];
            $discountAmount = 0;

            if (!empty($tat)) {
                $tatArr = explode('_', $tat);
                $expert_days += $tatArr[0];
                $due_date = Carbon::now()->addDays($expert_days)->format('Y-m-d');
            } else {
                $due_date = Carbon::now()->addDay()->format('Y-m-d');
            }

            $orderLabel = $this->generateOwnOrderLable();
            if ($balance > $totalGpOrderAmt) {
                try {
                    $orderAttributesId = DB::connection('lp_own_db')->table('order_attributes')->insertGetId([
                        'order_id' => $orderId ?? null,
                        'order_lable' => $orderLabel ?? null,
                        'reseller_order_lable' => $this->generateResellerOrderLable(),
                        'reseller_id' => $reseller_id ?? null,
                        'end_client_id' => $user_id ?? null,
                        'website_id' => $advertiserCartListing[$i]['website_id'],
                        'content_writter' => $advertiserCartListing[$i]['content_writter'],
                        'instruction' => $advertiserCartListing[$i]['instruction'],
                        'title' => $advertiserCartListing[$i]['title'],
                        'keyword' => $advertiserCartListing[$i]['keyword'],
                        'refrence_link' => $advertiserCartListing[$i]['refrence_link'],
                        'anchor_text' => $advertiserCartListing[$i]['anchor_text'],
                        'reference' => $advertiserCartListing[$i]['reference'],
                        'blog_url' => $advertiserCartListing[$i]['blog_url'],
                        'category_id' => $advertiserCartListing[$i]['category_id'],
                        'brief_note' => $advertiserCartListing[$i]['brief_note'],
                        'status' => 1,
                        'choose_content' => $advertiserCartListing[$i]['choose_content'],
                        'writting_style' => $advertiserCartListing[$i]['writting_style'],
                        'preferred_voice' => $advertiserCartListing[$i]['preferred_voice'],
                        'order_type_category' => $advertiserCartListing[$i]['marketplace_type'],
                        'guest_post_commission_price' => $guest_post_commission_price,
                        'guest_post_value_addition' => $guest_post_value_addition,
                        'link_insertion_commission_price' => $link_insertion_commission_price,
                        'link_insertion_value_addition' => $link_insertion_value_addition,
                        'fc_guest_post_commission_price' => $fc_guest_post_commission_price,
                        'fc_guest_post_value_addition' => $fc_guest_post_value_addition,
                        'fc_link_insertion_commission_price' => $fc_link_insertion_commission_price,
                        'fc_link_insertion_value_addition' => $fc_link_insertion_value_addition,
                        'Preferred_language' => isset($advertiserCartListing[$i]['prefered_language']) && $advertiserCartListing[$i]['prefered_language'] !== null ? $advertiserCartListing[$i]['prefered_language'] : (isset($advertiserCartListing[$i]['language']) && $advertiserCartListing[$i]['language'] !== null? $advertiserCartListing[$i]['language'] : null),
                        'country_name' => !empty($advertiserCartListing[$i]['target_audience']) 
                        ? DB::connection('lp_own_db')->table('countries')->where('id', $advertiserCartListing[$i]['target_audience'])->value('name') 
                        : null,
                        'promo_percentage' => $promo_percentage->promo_percentage ?? null,
                        'promo_type' => $promo_percentage->offer_name ?? null,
                        'affiliate_finalprice' => 0,
                        'project_id' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'reseller_order' => 1,
                        'price' => $advertiserCartListing[$i]['wihthout_commission_guest_post_price'],
                        'total' => $advertiserCartListing[$i]['total'],
                    ]);
                } catch (Exception $e) {
                    \Log::info(['error while inserting data in lp_own_db.order_attributes', $e]);
                }
    
                if ($cartId) {
                    DB::table('carts')->whereIn('id', $cartId)->delete();
                }

                $orderLabel = $orderLabel ?? null;
                $contentWriter = $advertiserCartListing[$i]['content_writter'];
                $description = match ($contentWriter) {
                    'expert_writter' => 'Content and Guest Post',
                    'link_insertion' => 'Link Insertion',
                    default => 'Guest Post',
                };
                
                $extraData = [
                    'description' => $description,
                    'order_attribute_id' => $orderAttributesId,
                    'order_label' => $orderLabel,
                    'order_type' => $contentWriter ?? $advertiserCartListing[$i]['content_writter'],
                ];

                $total_price = $advertiserCartListing[$i]['total'] - $discountAmount;
                $wal = DB::connection('lp_own_db')->table('wallets')->where('end_client_id', $user_id)->where('status', 'complete')->orderBy('id', 'desc')->first();
                $walletData = [
                    'end_client_id' => $user_id,
                    'user_id' => $user_id,
                    'reseller_id' => 1,
                    'credit_or_debit' => 'Debit',
                    'amount' => $total_price,
                    'total' => $wal->total - $total_price,
                    'status' => 'complete',
                    'provider' => 'wallet',
                    'order_id' => $this->generatedUniqueOrderId(),
                    'transaction_id' => 'ORDPLACEMENT-' . $orderLabel . '-' . date('Ymd-H:i:s'),
                    'added_by_user_id' => $user_id,
                    'added_desc' => 'Order Placed',
                    'table_type' => 'order',
                    'admin_credit_debit' => 'credit',
                ];

                if (!empty($extraData)) {
                    $walletData = array_merge($walletData, [
                        'order_attribute_id' => $orderAttributesId,
                        'order_lable' =>$orderLabel,
                        'description' => $extraData['description'],
                        'order_type' => $extraData['order_type'],
                    ]);
                }
                
                $wallet = DB::connection('lp_own_db')->table('wallets')->insert($walletData);
            } else {
                return response()->json(array('success' => false, 'message' => 'Order not executed due to low wallet balance.'));
            }

        }

        return response()->json(array('success' => true, 'message' => 'Order placed successfully!!','flag'=>0));
    }

    private function generateOwnOrderLable()
    {
        $lastOrder = DB::connection('lp_own_db')->table('order_attributes')->select("order_lable")->orderBy('id', 'DESC')->first();

        if ($lastOrder && isset($lastOrder->order_lable)) {
            $lastNumber = (int) filter_var($lastOrder->order_lable, FILTER_SANITIZE_NUMBER_INT);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return '#' . $newNumber;
    }

    private function generateResellerOrderLable()
    {
        $lastOrder = DB::connection('lp_own_db')->table('order_attributes')->whereNotNull('reseller_order_lable')->select("reseller_order_lable")->orderBy('id', 'DESC')->first();

        if ($lastOrder && isset($lastOrder->reseller_order_lable)) {
            $lastNumber = (int) filter_var($lastOrder->reseller_order_lable, FILTER_SANITIZE_NUMBER_INT);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return '#' . $newNumber;
    }

    private function generateOrderId()
    {
        $lastOrder = DB::connection('lp_own_db')->table('orders')->select("order_no")->orderBy('id', 'DESC')->first();

        if ($lastOrder && isset($lastOrder->order_no)) {
            $lastNumber = (int) filter_var($lastOrder->order_no, FILTER_SANITIZE_NUMBER_INT);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return '#' . $newNumber;
    }

    private function generatedUniqueOrderId()
    {
        do {
            $wallet = DB::connection('lp_own_db')->table('wallets')->orderBy('id', 'DESC')->first();

            $newId = isset($wallet) ? $wallet->id : 0;

            if (env('APP_ENV') == 'local') {
                $unique_order_id = '#10' . str_pad($newId + 15000 + 1, 9, "0", STR_PAD_LEFT);
            } else {
                $unique_order_id = '#10' . str_pad($newId + 1, 8, "0", STR_PAD_LEFT);
            }

            $exists = DB::connection('lp_own_db')->table('wallets')->where('order_id', $unique_order_id)->exists();

        } while ($exists);

        return $unique_order_id;
    }

    public function fetchClientOrders(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        if (!$authorizationHeader || !Str::startsWith($authorizationHeader, 'Bearer ')) {
            return response()->json([
                'error' => 'Unauthorized. Token is required.',
                'logout' => true
            ], 401);
        }

        $token = str_replace('Bearer ', '', $authorizationHeader);
        $user = DB::table('reseller_users')->where('remember_token', $token)->first();

        if (!$user) {
            return response()->json([
                'error' => 'Invalid token.',
                'logout' => true
            ], 401);
        }

        $fetchUserID = DB::connection('lp_own_db')->table('users')->where('email', $user->email)->value('id');
        $fetchUserName = DB::connection('lp_own_db')->table('users')->where('email', $user->email)->value('name');

        $query = DB::connection('lp_own_db')->table('order_attributes')
            ->join('websites', 'websites.id', 'order_attributes.website_id')
            ->where('order_attributes.end_client_id', $user->id)
            ->whereNull('order_attributes.deleted_at')
            ->select(
                'order_attributes.created_at', 'order_attributes.updated_at as start_date', 'order_attributes.total as price', 'order_attributes.with_comission_price', 'order_attributes.due_date', 'order_attributes.due_time', 'order_attributes.status', 'order_attributes.id as order_attr_id', 'order_attributes.content_modification', 'order_attributes.url', 'order_attributes.order_lable', 'order_attributes.original_expert_price', 'order_attributes.expert_price', 'order_attributes.discount_amount', 'order_attributes.total', 'order_attributes.content_writter', 'order_attributes.reject_type', 'order_attributes.reject_action_status', 'order_attributes.reject_reason', 'order_attributes.is_continue', 'order_attributes.tat as order_tat', 'order_attributes.req_price', 'order_attributes.order_type_category', 'order_attributes.remaining_time', 'order_attributes.is_lls_check','order_attributes.lls_status', 'order_attributes.affiliate_finalprice', 'websites.id as websiteID', 'websites.publisher_id', 'websites.website_url', 'websites.host_url', 'websites.tat', 'order_attributes.commission_price','order_attributes.value_addition', 'order_attributes.Preferred_language', 'order_attributes.reseller_order_lable',
                DB::raw("'$fetchUserID' as fetchUserID"),
                DB::raw("'$fetchUserName' as fetchUserName"),
                DB::raw('(select count(*) from `socket_order_message` where  ( `to_id` = '.$fetchUserID.') and `status` = 1 AND seen = 0 and order_id = `order_attributes`.`id` and is_reseller_msg = 1)  as new_msg')
            );

        $status = $request->input('status', 1);

        if ($status !== 'all') {
            $query->where('order_attributes.status', $status);
        }

        $getOrderData = $query->orderBy('order_attributes.id', 'desc')->get();
        
        foreach ($getOrderData as $order) {
            $order->host_url = DB::connection('lp_own_db')
                ->table('websites')
                ->where('id', $order->websiteID)
                ->value('host_url') ?? '-';

            $checkLable = DB::connection('lp_own_db')
                ->table('order_attributes')
                ->where('reseller_order_lable', $order->reseller_order_lable)
                ->value('order_lable') ?? '-';

            $order->live_link = $order->url;
            $demo_lable = ltrim($checkLable, '#');
            $order->action_column = '
                <div class="multi-actions" id="multiAction' . $demo_lable . '" data-oaid="' . $demo_lable . '">
                    <a class="checked acationDeisb" data-title="Accept">
                        <img src="' . asset("assets/images/checked-icon.png") . '" alt="checked-icon">
                    </a>
                    <a class="save acationDeisb" data-oaid="' . $demo_lable . '" data-title="Comment">
                        <img src="' . asset("assets/images/save-icon.png") . '" alt="save-icon">
                    </a>
                    <input type="hidden" name="ord_id_val" id="ord_id_val" value="' . $demo_lable . '">
                    <div class="close-action-popup" id="' . $demo_lable . '" style="display: none;">
                        <a href="javascript:void(0);" class="close-icon"></a>
                        <div class="close-action-popup-inner">
                            <span>Add Comment</span> <span class="comment_id"> #' . $order->order_lable . '</span>
                            <form action="post" id="form' . $demo_lable . '">
                                <div class="reason-input">
                                    <textarea name="message" data-oaid="' . $demo_lable . '" class="modificationMessage" id="modificationMessage' . $demo_lable . '" cols="30" rows="10" placeholder="Enter your comment"></textarea>
                                </div>
                                <div class="reason-submit">
                                    <div class="choose_file file " id="choose_file_' . $demo_lable . '" style="display: inline-block;" bottom-title="Choose file" title="">
                                        <a> <img src="' . asset("assets/images/send-icon.png") . '" alt="send-icon"></a>
                                        <input name="attachment" class="download" id="attachment_' . $demo_lable . '" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx">
                                    </div>
                                    <span class="docuemnt_name" id="docuemnt_name_' . $demo_lable . '"></span>
                                    <input type="button" value="" class="submitModification">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';
        }

        $statusCounts = DB::connection('lp_own_db')
            ->table('order_attributes')
            ->where('order_attributes.end_client_id', $user->id)
            ->whereNull('order_attributes.deleted_at')
            ->selectRaw('order_attributes.status, COUNT(*) as count')
            ->groupBy('order_attributes.status')
            ->pluck('count', 'order_attributes.status');

        return response()->json([
            'orders' => $getOrderData,
            'statusCounts' => $statusCounts,
            'totalOrders' => array_sum($statusCounts->toArray()),
            'fetchUserID' => $fetchUserID,
            'fetchUserName' => $fetchUserName
        ]);
    }

    public function clientApprovalToComplete(Request $request)
    {
        $order_attribute_id = $request->param['order_attribute_id'];
        if (strpos($order_attribute_id, '#') !== 0) {
            $order_attribute_id = '#' . $order_attribute_id;
        }
        
        $orderAttributeDetails = DB::connection('lp_own_db')
            ->table('order_attributes')
            ->where('order_lable', $order_attribute_id)
            ->first();

        if ($orderAttributeDetails && $orderAttributeDetails->status == 7) {
            $updateInLP = DB::connection('lp_own_db')
                ->table('order_attributes')
                ->where('order_lable', $order_attribute_id)
                ->update(['status' => 6]);

            $getPublisher = DB::connection('lp_own_db')->table('order_attributes')->join('websites', 'websites.id', 'order_attributes.website_id')
                ->join('users', 'users.id', 'websites.publisher_id')
                ->where('order_attributes.order_lable', $order_attribute_id)
                ->select('websites.website_url', 'users.name', 'users.email', 'order_attributes.order_lable', 'order_attributes.order_id', 'order_attributes.created_at', 'order_attributes.url', 'order_attributes.updated_at')->first();
            
            // $jsonArray = escapeshellarg(json_encode($getPublisher));
            // $additionalParam = "orderCompleteMailToPublisher";
            // $command = 'php ' . base_path('artisan') . ' background:mailSend ' . $jsonArray . ' ' . $additionalParam . '> /dev/null 2>&1 &';
            // exec($command);
            // $mail = MailHelper::orderCompleteMailPublisher($getPublisher);
        }
        
        return response()->json(array('success' => true, 'message' => 'Order completed Successfully'));
    }

    public function getClientChatMessage(Request $request)
    {
        $reseller_id = $request->reseller_id;
        if (!$reseller_id) {
            $authorizationHeader = $request->header('Authorization');
            if (!$authorizationHeader || !Str::startsWith($authorizationHeader, 'Bearer ')) {
                return response()->json([
                    'error' => 'Unauthorized. Token is required.',
                    'logout' => true
                ], 401);
            }
    
            $token = str_replace('Bearer ', '', $authorizationHeader);
            $user = DB::table('reseller_users')->where('remember_token', $token)->first();
    
            if (!$user) {
                return response()->json([
                    'error' => 'Invalid token.',
                    'logout' => true
                ], 401);
            }
            
            $getChatMessage = '';
    
            DB::connection('lp_own_db')->table('socket_order_message')->where('to_id', $request->user_id)->where('order_id', $request->order_attribute_id)->where('status', 1)->where('is_reseller_msg', 1)->update(array('seen' => 1));
        }
        
        $chatMessage = DB::connection('lp_own_db')->table('socket_order_message')
            ->where('socket_order_message.is_reseller_msg', 1)
            ->where('socket_order_message.content_order_msg_or_not', null)
            ->where('socket_order_message.order_id', $request->order_attribute_id)
            ->where('to_id', $request->user_id)
            ->where('admin_seen', 1)
            ->where('socket_order_message.status',1)
            ->select('socket_order_message.*', 'socket_order_message.id AS message_created_at')
            ->join('order_attributes', 'order_attributes.id', '=', 'socket_order_message.order_id')
            ->orderBy('socket_order_message.created_at', 'ASC')
            ->union(
                DB::table('socket_order_message')
                    ->where('socket_order_message.is_reseller_msg', 1)
                    ->where('socket_order_message.content_order_msg_or_not', null)
                    ->where('socket_order_message.order_id', $request->order_attribute_id)
                    ->where('from_id', $request->user_id)
                    ->select('socket_order_message.*', 'socket_order_message.id AS message_created_at')
                    ->join('order_attributes', 'order_attributes.id', '=', 'socket_order_message.order_id')
                    ->orderBy('socket_order_message.created_at', 'ASC')
            )->orderBy('message_created_at', 'ASC')
            ->get();
        
        if ($chatMessage) {
            foreach ($chatMessage as $chat) {
                $getChatMessage = $chat->body;
                if ($this->containsUrl($getChatMessage)) {
                    $getChatMessageWithLinks = $this->makeUrlsClickable($getChatMessage);
                    $chat->body_with_links = $getChatMessageWithLinks;
                    $getChatMessage = strip_tags($getChatMessage);
                }
            }
        }

        $order_status = $request->order_status;
        
        $user_id = $request->user_id;
        $name = DB::connection('lp_own_db')->table('users')->where('id', $user_id)->value('name');

        if ($reseller_id) {
            $returnHTML = view('reseller_chatbody', compact('chatMessage', 'order_status', 'user_id', 'name'))->render();
        } else {
            $returnHTML = view('chatbody', compact('chatMessage', 'order_status', 'user_id'))->render();
        }
        
        return response()->json(array('success' => true,'html'=>$returnHTML));
    }

    private function containsUrl($text)
    {
        $urlRegex = '/(?:https?:\/\/)?(?:www\.)?[^\s]+\.[^\s]{2,}(?:\.[^\s]{2,})?(?:\/[^\s]*)?\b/';
        return preg_match($urlRegex, $text);
    }

    public function makeUrlsClickable($text)
    {
        $urlRegex = '/(?:https?:\/\/)?(?:www\.)?[^\s]+\.[^\s]{2,}(?:\.[^\s]{2,})?(?:\/[^\s]*)?\b/';
        $newText = preg_replace_callback($urlRegex, function ($matches) {
            $url = $matches[0];
            if (!preg_match('/^https?:\/\//i', $url)) {
                $newUrl = 'https://' . $url;
            } else {
                $newUrl = $url;
            }
            return '<a href="' . $newUrl . '" target="_blank">' . $url . '</a>';
        }, $text);

        return $newText;
    }

    public function clientUnreadMsgCounts(Request $request)
    {
        $authorizationHeader = $request->header('Authorization');
        if (!$authorizationHeader || !Str::startsWith($authorizationHeader, 'Bearer ')) {
            return response()->json([
                'error' => 'Unauthorized. Token is required.',
                'logout' => true
            ], 401);
        }

        $token = str_replace('Bearer ', '', $authorizationHeader);
        $user = DB::table('reseller_users')->where('remember_token', $token)->first();

        $fetchUserID = DB::connection('lp_own_db')->table('users')->where('email', $user->email)->value('id');

        if (!$user) {
            return response()->json([
                'error' => 'Invalid token.',
                'logout' => true
            ], 401);
        }

        $newUnreadMsgCount = DB::connection('lp_own_db')->select("select count(socket_order_message.id) AS new FROM `socket_order_message`  JOIN order_attributes ON order_attributes.id=socket_order_message.order_id WHERE `socket_order_message`.`is_reseller_msg` = 1 and `socket_order_message`.`content_order_msg_or_not` is null AND `socket_order_message`.`seen` = 0  AND `order_attributes`.`status` != 0 AND `order_attributes`.`status` != 6 AND socket_order_message.admin_seen = 1 AND socket_order_message.status = 1 AND socket_order_message.to_id=".$fetchUserID);

        return response()->json(array('success' => true, 'count' => $newUnreadMsgCount[0]->new));
    }

    public function sendMessage(Request $request)
    {
        $chatId = DB::connection('lp_own_db')->table('socket_order_message')->insertGetId([
            'body' => $request->message,
            'from_id' => $request->from_id,
            'to_id' => $request->to_id,
            'order_id' => $request->order_id,
            'is_reseller_msg' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return response()->json(['success' => true, 'message' => 'Message sent successfully!', 'id' => $chatId]);
    }
}