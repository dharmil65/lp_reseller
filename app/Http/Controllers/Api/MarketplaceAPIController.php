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
        $orderDir = $request->input('orderDir', 'desc');

        $query = DB::connection('lp_own_db')->table('advertiser_marketplace');

        if (in_array($orderBy, ['ahref', 'semrush'])) {
            $query = $query->orderBy(DB::raw("CAST($orderBy AS UNSIGNED)"), $orderDir);
        } else {
            $query = $query->orderBy($orderBy, $orderDir);
        }

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

        $websiteData->transform(function ($item) {
            $item->ahref = $this->formatNumber($item->ahref);
            $item->semrush = $this->formatNumber($item->semrush);
            return $item;
        });

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

    private function formatNumber($val)
    {
        if ($val >= 1E12) {
            return round($val / 1E12, 1) . 'T';
        } elseif ($val >= 1E9) {
            return round($val / 1E9, 1) . 'B';
        } elseif ($val >= 1E6) {
            return round($val / 1E6, 1) . 'M';
        } elseif ($val >= 1E3) {
            return round($val / 1E3, 1) . 'K';
        } else {
            return (string) $val;
        }
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
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id = $user->id ?? null;
        $cartTotal = DB::table('carts')->where('advertiser_id', $id)->where('status', 0)->count();
        $walletBalance = DB::connection('lp_own_db')->table('wallets')->where('end_client_id', $id)->where('status', 'complete')->orderBy('id', 'desc')->pluck('total')->first();

        return response()->json(['success' => true, 'walletBalance' => $walletBalance, 'cartTotal' => $cartTotal, 'userid' => $id]);
    }

    public function fetchEndClientCartData(Request $request)
    {
        $id = $request->endClientId ?? null;
        $cartDetails = DB::table('carts')->where('advertiser_id',  $id)->where('marketplace_type', $request->marketplace_type)->where('website_id', $request->website_id)->orderby('quantity_no', 'ASC')->get();

        if (!$cartDetails || count($cartDetails) == 0) {
            return response()->json(['error' => 'Unauthorized', 'redirect_url' => route('marketplace')], 401);
        }

        $websiteDetail = DB::connection('lp_own_db')->table('advertiser_marketplace')->where('website_id', $request->website_id);
        
        if ($request->marketplace_type == 0) {
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

        if (!$cartDetails || empty($cartDetails)) {
            return response()->json(['error' => 'Unauthorized', 'redirect_url' => route('marketplace')], 401);
        }

        if ($request->type == 'provide_content') {
            $languageData = DB::connection('lp_own_db')->table('websites')->where('id', $cartDetails->website_id)->select('language')->first();
            $languageList = explode(',', $languageData->language);
            $cartListHtml =  view('provide_content_modal', compact('cartDetails','languageList'))->render();
            return response()->json(array('success' => true, 'cartListHtml' => $cartListHtml, 'cart data fetch','languageList' => $languageList));
        }
    }

    public function addQuantityDataEndClient(Request $request){
        $id = $request->end_client_id ?? null;

        if (!$id) {
            return response()->json(['error' => 'Unauthorized', 'redirect_url' => route('marketplace')], 401);
        }

        if ($request->type == "provide_content"){
            $validator = Validator::make($request->all(), [
            ]);
        } else if($request->type == "hire_content") {
            $validator = Validator::make($request->all(), [
                "words"=>'required',
                "categoryid"=>'required',
                "keywords"=>'required',
                "targeturl"=>'required',
                "anchortext"=>'required',
                "prefered_language"=>'required',
                "target_audience"=>'required'
            ]);
        }

        if ($validator->fails()) {
            array_push($cart_error, $validator->messages());
        } else {
            $website_id = isset($request->provide_content_website_id) ? $request->provide_content_website_id : $request->website_id;
            $marketplace_type = isset($request->provide_content_marketplace_type) ? $request->provide_content_marketplace_type : $request->marketplace_type;
            $quantity = isset($request->provide_content_quantity) ? $request->provide_content_quantity : $request->quantity;
            $website = DB::connection('lp_own_db')->table('websites')->where('id', $website_id)->first();

            $cartData = DB::table('carts')->select('*')->where('website_id', $website_id)
                ->where('advertiser_id', $id)->where('quantity_no', $quantity)
                ->where('marketplace_type', $marketplace_type)
                ->get();

            $guestPostPrice = $website->publishing_price;
            $linkInsertionPrice = $website->linkinsertion_price;
            $marketplace_type = (isset($website->category_type) && $website->category_type == null) ? '0' : $website->category_type;
              
            if ($website->marketplace_guest_post_price == null || $website->marketplace_guest_post_price != null) {
                if ($website->guest_post_commission_price!=null && $website->guest_post_value_addition == null) {
                    $guestPostPriceTotal = (ceil(($website->publishing_price * $website->guest_post_commission_price)/100)) + $website->publishing_price;
                } else if ($website->guest_post_commission_price == null && $website->guest_post_value_addition!=null) {
                    $guestPostPriceTotal = $website->guest_post_value_addition + $website->publishing_price;
                } else {
                    $guestPostPriceTotal = $website->marketplace_guest_post_price;
                }
            }else{
                $guestPostPriceTotal = $website->marketplace_guest_post_price;
            }
            
            $linkInsertionPriceTotal = null;
            if ($website->marketplace_linkinsertion_price == null || $website->marketplace_linkinsertion_price != null) {
                if ($website->link_insertion_commission_price!=null && $website->link_insertion_value_addition == null) {
                    $linkInsertionPriceTotal = (ceil(($website->linkinsertion_price * $website->link_insertion_commission_price)/100)) + $website->linkinsertion_price;
                } else if ($website->link_insertion_commission_price == null && $website->link_insertion_value_addition!=null) {
                    $linkInsertionPriceTotal = $website->link_insertion_value_addition + $website->linkinsertion_price;
                } else {
                    $linkInsertionPriceTotal = $website->marketplace_linkinsertion_price;
                }
            } else {
                $linkInsertionPriceTotal = $website->marketplace_linkinsertion_price;
            }

            if (count($cartData) > 0) {
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
                } else if ($request->type == 'hire_content') {
                    $data = array(
                        'category_id' => $request->categoryid,
                        'keyword' => $request->keywords,
                        'reference' => $request->targeturl,
                        'anchor_text' => $request->anchortext,
                        'language' => $request->prefered_language,
                        'target_audience' => $request->target_audience,
                        'brief_note' => $request->briefnote
                    );

                    if ($request->titlesuggestion != null) {
                        $data['title'] = $request->titlesuggestion;
                    }
                    if ($request->referencelink != null) {
                        $data['refrence_link'] = $request->referencelink;
                    }
                    if ($request->choose_writing != null) {
                        $data['choose_content'] = $request->choose_writing;
                    }
                    if ($request->writing_style != null) {
                        $data['writting_style'] = $request->writing_style;
                    }
                    if ($request->prefered_voice != null) {
                        $data['preferred_voice'] = $request->prefered_voice;
                    }
                    
                    if ($request->has('dynamicFields')) {
                        $dynamicFields = $request->input('dynamicFields');
                        foreach ($dynamicFields as $key => $value) {
                            if (preg_match('/^anchor_text_(\d+)$/', $key, $matches)) {
                                $index = $matches[1];
                                if ($index >= 1 && $index <= 4) {
                                    $data['anchor_text_' . $index] = $value;
                                }
                            }
                            if (preg_match('/^target_url_(\d+)$/', $key, $matches)) {
                                $index = $matches[1];
                                if ($index >= 1 && $index <= 4) {
                                    $data['target_url_' . $index] = $value;
                                }
                            }
                        }
                    }                    

                    $data['content_writter']='expert_writter';
                    $data['expert_price']=$request->expert_price;
                    $data['expert_price_id']=$request->expert_price_id;
                    $data['wihthout_commission_guest_post_price']=$guestPostPrice;
                    $data['total']=ceil($guestPostPriceTotal + $request->expert_price);
                    $data['language'] = $request->prefered_language ?? null;
                    $data['target_audience'] = $request->target_audience ?? null;
                }

                $cart = DB::table('carts')->where('website_id', $website_id)->where('quantity_no', $quantity)->where('advertiser_id', $id)->update($data);

                return response()->json(array('success' => true,'message'=>'Quantity store successfully', 'cart_id' => $cartData[0]->id, 'web_id' => $cartData[0]->website_id));
            } else {
                $cartId = DB::table('carts')->insertGetId([
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
                    'quantity_no' => $quantity,
                    'project_id' => Auth::user()->current_project_selected ?? null,
                    'marketplace_type' => $marketplace_type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $cart = DB::table('carts')->where('id', $cartId)->first();
                
                return response()->json(array('success' => true, 'cart' => $cart->id ,'message'=>'Quantity store successfully','total'=>number_format($cart->total)));
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
         
        if (!$advertiserCartListing || empty($advertiserCartListing)) {
            return response()->json(['error' => 'Unauthorized', 'redirect_url' => route('marketplace')], 401);
        }

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
            $provideContent_total = $linkinsertion_total = $hirecontent_total = $expertPriceTotal = $expertPriceTotalOld = 0;
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
                } else if ($res[$j]->content_writter == 'expert_writter') {
                    $hirecontent += 1;
                    $hirecontent_total += $res[$j]->total;
                    $expertPriceTotal += ($res[$j]->expert_price != null) ? $res[$j]->expert_price : 0;

                    if (isset($res[$j]->expert_price)) {
                        $expertPriceTotalOld += $res[$j]->expert_price;
                    }
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

        $cartTotal = DB::table('carts')
            ->where('advertiser_id', $id)
            ->where('status', 0)
            ->count();

        if (count($orderSummaryData) > 0) {
            return view('client_order_summary', compact('pagetitle', 'orderSummaryData', 'total', 'subtotal', 'balance', 'cartTotal'));
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

        if (!$cartDetails || empty($cartDetails)) {
            return response()->json(['error' => 'Unauthorized', 'redirect_url' => route('marketplace')], 401);
        }
        
        $advertiserCartListing = $cartDetails;
                
        $cartstotal = array_sum(array_column($cartDetails, 'total'));
        $cartId = array_column($cartDetails, 'id');
        $checkIfPriceChanged = in_array(1, array_column($cartDetails, 'price_changed'));
        $websiteIds = array_unique(array_column($cartDetails, 'website_id'));

        $grandTotal = $totalDiscount = $totalGPOrderTotal = 0;
        $unique = [];

        $resellerUserId = $user_id;
        $balance = DB::connection('lp_own_db')->table('wallets')->where('end_client_id', $user_id)->where('status', 'complete')->orderBy('id', 'desc')->pluck('total')->first();

        $checkIfVacationMode = DB::connection('lp_own_db')->table('websites')->select("websites.id")
            ->join('users', 'websites.publisher_id', '=', 'users.id')
            ->whereIn('websites.id', $websiteIds)
            ->where('users.vacation_mode', 1)
            ->exists();

        $sellerBuyerCoupon = DB::connection('lp_own_db')->table('seller_buyer_coupons')->where('buyer_id', $user_id)->where('status', 0)->get()->toArray();

        if(isset($sellerBuyerCoupon) && !empty($sellerBuyerCoupon)) {
            $referDiscount = $cartstotal * 10/100;
            $cartstotal =  $cartstotal - round($referDiscount);
        }

        if ($cartstotal > $balance) {
            return response()->json(['success' => true, 'error' => 'Wallet balance is low', 'flag' => 1, 'redirect_url' => route('marketplace')], 401);
        } else if ($checkIfPriceChanged) {
            Session::put('flagMessage', 'Price has changed in carts');
            return response()->json(['success' => true, 'error' => 'Price has changed in carts', 'flag' => 2, 'redirect_url' => route('marketplace')], 401);
        } else if ($checkIfVacationMode) {
            Session::put('flagMessage', 'Publisher websites under review');
            return response()->json(['success' => true, 'error' => 'Publisher websites under review.', 'flag' => 3, 'redirect_url' => route('marketplace')], 401);
        }
            
        $data = [
            'advertiser' => DB::table('reseller_users')->where('id', $resellerUserId)->value('name'),
            'order_url' => url('/') . '/advertiser/order',
            'email' => DB::table('reseller_users')->where('id', $resellerUserId)->value('email')
        ];

        $websiteId = !empty($advertiserCartListing) ? array_column($advertiserCartListing, "website_id") : [];

        $websiteData = DB::connection('lp_own_db')->table('websites')
            ->whereIn('id', $websiteId)
            ->get()
            ->keyBy('id');

        $getEmail = DB::table('reseller_users')->where('id', $user_id)->value('email');
        $getUserID = DB::connection('lp_own_db')->table('users')->where('email', $getEmail)->value('id');
        $ordercount =  DB::connection('lp_own_db')->table('orders')->where('advertiser_id', $getUserID)->select('orders.orderno_count')->orderBy('orders.orderno_count', 'desc')->first();
        $countOrder = $ordercount == null ? 1 : $ordercount->orderno_count + 1;

        $appSettings = DB::connection('lp_own_db')->table('admin_settings')->get();

        $appSettingData = [];
        
        if (!empty($appSettings)) {
            $data = $appSettings->map(
                function ($item) {
                    return array(
                        $item->meta_key => $item->meta_value,
                    );
                }
            )->toArray();

            if ($data) {
                $appSettingData = call_user_func_array('array_merge', $data);
            }
        }

        $orderId = DB::connection('lp_own_db')->table('orders')->insertGetId([
            'order_no' => $this->generateOrderId(),
            'advertiser_id' => $getUserID,
            'status' => 1,
            'reseller_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'orderno_count' => $countOrder ?? 0,
        ]);

        $chkuserlogin = DB::connection('lp_own_db')->table('seller_buyer_coupons')
            ->where('create_status', 'affiliate')
            ->where('buyer_id', $getUserID)
            ->first();
        
        $publisher_details_all = DB::connection('lp_own_db')->table('users')->select('name', 'email','id')->get()->keyBy('id');

        $orderNoti = $contentNoti = $publisherMail = $lowPrizeMail = $orderAttrMail = $publisherMail = [];
        
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

            $expert_days = $days = $total_price = $discount_amount = $discountAmount = 0;
            $tat = $website->tat;
            $coupon_status = (int) $appSettingData['coupon_status'];
            
            if (isset($appSettingData['coupon_discount'])) {
                $discount_amount = $appSettingData['coupon_discount'];
            }
            
            $totalGpOrderAmt = $advertiserCartListing[$i]['total'] - $advertiserCartListing[$i]['expert_price'];

            $currenttime = Carbon::now();

            $promo_percentage = DB::connection('lp_own_db')->table('promotions')->where('start_date', '<=', $currenttime)
                ->where('end_date', '>=', $currenttime)
                ->where('promotion_website.website_id', $advertiserCartListing[$i]['website_id'])
                ->join('promotion_website', 'promotion_website.promotion_id', 'promotions.id')
                ->select('promo_percentage','offer_name')
                ->first();

            if ($advertiserCartListing[$i]['source']){
                $source = $advertiserCartListing[$i]['source'];
            } else {
                $source = null;
            }

            if ($advertiserCartListing[$i]['content_writter'] == "provide_content") {
                $price = $advertiserCartListing[$i]['wihthout_commission_guest_post_price'];
                $attachment = $advertiserCartListing[$i]['attachment'];
                $total = $advertiserCartListing[$i]['total'];
                $with_comission_price = $advertiserCartListing[$i]['total'] - $discountAmount;
                $total_price = $advertiserCartListing[$i]['total'] - $discountAmount;
            } else if ($advertiserCartListing[$i]['content_writter'] == "link_insertion") {
                $price = $advertiserCartListing[$i]['wihthout_commission_linkinsertion_price'];
                $link_insertion_price = $advertiserCartListing[$i]['link_insertion_price'];
                $total = $advertiserCartListing[$i]['total'];
                $with_comission_price = $advertiserCartListing[$i]['total'] - $discountAmount;
                $total_price = $advertiserCartListing[$i]['total'] - $discountAmount;
            } else if ($advertiserCartListing[$i]['content_writter'] == "hire_content") {
                $price = $advertiserCartListing[$i]['wihthout_commission_guest_post_price'];
                $expert_price_id = $advertiserCartListing[$i]['expert_price_id'];
                $expert_price = $advertiserCartListing[$i]['expert_price'];
                $original_expert_price = $advertiserCartListing[$i]['expert_price'];
                $total = $advertiserCartListing[$i]['total'];
                $with_comission_price = $advertiserCartListing[$i]['total'] - $discountAmount;
                $total_price = $advertiserCartListing[$i]['total'] - $discountAmount;
                $expert = DB::connection('lp_own_db')->table('expert_prices')->find($advertiserCartListing[$i]['expert_price_id']);
                $expert_days = $expert->days;
                $content_days = $expert->days;
            } else {
                $price = $advertiserCartListing[$i]['wihthout_commission_guest_post_price'];
                $total = $advertiserCartListing[$i]['total'];
            }

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
                        'price' => $price,
                        'total' => $total,
                        'due_date' => $due_date,
                        'due_time' => Carbon::now()->format('H:i:s'),
                        'source' => $source,
                        'anchor_text_1' => $advertiserCartListing[$i]['anchor_text_1'] ?? null,
                        'anchor_text_2' => $advertiserCartListing[$i]['anchor_text_2'] ?? null,
                        'anchor_text_3' => $advertiserCartListing[$i]['anchor_text_3'] ?? null,
                        'anchor_text_4' => $advertiserCartListing[$i]['anchor_text_4'] ?? null,
                        'target_url_1' => $advertiserCartListing[$i]['target_url_1'] ?? null,
                        'target_url_2' => $advertiserCartListing[$i]['target_url_2'] ?? null,
                        'target_url_3' => $advertiserCartListing[$i]['target_url_3'] ?? null,
                        'target_url_4' => $advertiserCartListing[$i]['target_url_4'] ?? null,
                        'discount_amount' => $discountAmount,
                    ]);
                } catch (Exception $e) {
                    \Log::info(['error while inserting data in lp_own_db.order_attributes', $e]);
                }
    
                if ($cartId) {
                    DB::table('carts')->whereIn('id', $cartId)->delete();
                }

                if ($chkuserlogin) {
                    
                    $exp = isset($expert_price) ? $expert_price : $advertiserCartListing[$i]['expert_price'];
                    if ($chkuserlogin->publisher_id == 25940) {
                        $sellerDis = ($total - $discountAmount - $exp) * 0.04;
                    } else {
                        $sellerDis = ($total - $discountAmount - $exp) * 0.02;
                    }
                    
                    DB::connection('lp_own_db')->table('order_attributes')->where('id', $orderAttributesId)->update(['seller_discount_amt' => $sellerDis]);
                }

                $orderLabel = $orderLabel ?? null;

                $data['website_name'] = $website->website_url;

                $publisher_details = $publisher_details_all[$website->publisher_id];

                $data = [
                    'website_name' => $website->website_url,
                    'publisher' => $publisher_details->name,
                    'publisher_order' => url('/') . '/publisher/orders-details',
                    'publisher_email' => $publisher_details->email,
                    'order_label' => $orderLabel
                ];

                if ($advertiserCartListing[$i]['content_writter'] == "hire_content") {
                    $contentData = DB::connection('lp_own_db')->table('contents')->insert([
                        'order_attribute_id' => $orderAttributesId,
                        'content_status' => 0,
                        'content_due_date' => isset($content_days) ? Carbon::now()->addDays($content_days)->format('Y-m-d') : null,
                    ]);
                    
                    if ($contentData) {
                        $content = DB::connection('lp_own_db')->table('contents')
                            ->where('order_attribute_id', $orderAttributesId)
                            ->latest('id')
                            ->first();
                    
                        $checkContent = DB::connection('lp_own_db')->table('content_logs')
                            ->where('order_attribute_id', $orderAttributesId)
                            ->whereNotNull('old_attachment')
                            ->count();
                    
                        DB::connection('lp_own_db')->table('content_logs')->insert([
                            'status' => $status,
                            'order_attribute_id' => $orderAttributesId,
                            'user_id' => $user,
                            'old_attachment' => $name,
                            'modification_flag' => $checkContent >= 1 ? 2 : 1,
                        ]);
                    
                        $contentNoti[] = $content;
                    }
                } else {
                    $publisherMail[] = $data;
                }

                DB::connection('lp_own_db')->table('order_attribute_logs')->insert([
                    'order_attribute_id' => $orderAttributesId,
                    'status' => 1,
                    'action_by' => $getUserID,
                    'attachment' => $advertiserCartListing[$i]['attachment'],
                    'delay_approve' => 0,
                    'status_desc' => 0
                ]);

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

                $walletId = DB::connection('lp_own_db')->table('wallets')->insertGetId($walletData);

                $wallet = DB::connection('lp_own_db')->table('wallets')->where('id', $walletId)->first();

                $checkPriceCompare = DB::connection('lp_own_db')->table('advertiser_marketplace')->where('website_id', $advertiserCartListing[$i]['website_id'])->first();
                
                if(!$checkPriceCompare){
                    $getActualPrice  =  $this->getActualPrice($advertiserCartListing[$j]);
                }

                if ($advertiserCartListing[$i]['content_writter'] == "provide_content") {
                    if ($advertiserCartListing[$i]['marketplace_type'] == 0) {
                        $payThisOrder = $wallet->amount;
                        $acctualprice =  $checkPriceCompare ? $checkPriceCompare->guest_post_price : $getActualPrice['guest_post_price'] - $advertiserCartListing[$i]['discount_amount'];
                    } else {
                        $payThisOrder = $wallet->amount;
                        $acctualprice = $checkPriceCompare ? $checkPriceCompare->forbidden_category_guest_post_price : $getActualPrice['forbidden_category_guest_post_price'] - $advertiserCartListing[$i]['discount_amount'];
                    }
                } elseif ($advertiserCartListing[$i]['content_writter'] == "link_insertion") {
                    if ($advertiserCartListing[$i]['marketplace_type'] == 0) {
                        $payThisOrder = $wallet->amount;
                        $acctualprice = $checkPriceCompare ? $checkPriceCompare->linkinsertion_price : $getActualPrice['linkinsertion_price'] - $advertiserCartListing[$i]['discount_amount'];
                    } else {
                        $payThisOrder = $wallet->amount;
                        $acctualprice = $checkPriceCompare ? $checkPriceCompare->forbidden_category_linkinsertion_price : $getActualPrice['forbidden_category_linkinsertion_price']  - $advertiserCartListing[$i]['discount_amount'];
                    }
                } elseif ($advertiserCartListing[$i]['content_writter'] == "expert_writter") {
                    if ($advertiserCartListing[$i]['marketplace_type'] == 0) {
                        $payThisOrder = $wallet->amount;
                        $acctualprice = ($checkPriceCompare ? $checkPriceCompare->guest_post_price : $getActualPrice['guest_post_price']) + $advertiserCartListing[$i]['expert_price'] - $advertiserCartListing[$i]['discount_amount'];
                    }else{
                        $payThisOrder = $wallet->amount;
                        $acctualprice = ($checkPriceCompare ? $checkPriceCompare->forbidden_category_guest_post_price : $getActualPrice['forbidden_category_guest_post_price'])  + $advertiserCartListing[$i]['expert_price'] - $advertiserCartListing[$i]['discount_amount'];
                    }
                }

                $mailData['orderLable'] = $orderLabel;
                $mailData['advertiserName'] = DB::table('reseller_users')->where('id', $resellerUserId)->value('name');
                $mailData['publisherName'] = $publisher_details->name;

                if ($advertiserCartListing[$i]['content_writter'] == "provide_content") {
                    $mailData['orderType'] = "Guest Post";    
                } else if ($advertiserCartListing[$i]['content_writter'] == "expert_writter") {
                    $mailData['orderType'] = "Content + GP";    
                } else if ($advertiserCartListing[$i]['content_writter'] == "link_insertion") {
                    $mailData['orderType'] = "Link Insertion";    
                }

                $mailData['orderPrice'] = $payThisOrder;
                $mailData['marketplacePrice'] = $acctualprice;
                $mailData['route'] = null;

                if($payThisOrder != $acctualprice) {
                    $jsonArray = escapeshellarg(json_encode($mailData));
                    $additionalParam = "orderPlacedAtLowPriceMail";
                    $command = 'php ' . base_path('artisan') . ' background:mailSend ' . $jsonArray . ' ' . $additionalParam . '> /dev/null 2>&1 &';
                    exec($command);
                }

                if (isset($sellerBuyerCoupon) && !empty($sellerBuyerCoupon)) {
                    $publisherName = DB::connection('lp_own_db')->table('users')->where('id', $sellerBuyerCoupon[0]['publisher_id'])->get()->toArray();
                    $updateSellerBuyerCouponStatus = DB::connection('lp_own_db')->table('seller_buyer_coupons')->where('buyer_id', $getUserID)->update(['status' => 1, 'reject_order_status' => '0' , 'order_id' => $orderId]);
                }

                $ordercount = DB::connection('lp_own_db')->table('orders')->where('advertiser_id', $getUserID)->select('orders.orderno_count')->orderBy('orders.orderno_count', 'desc')->first();
                $countOrder = isset($ordercount) ? $ordercount->orderno_count + 1 : 1;

                DB::connection('lp_own_db')->table('orders')->where('id', $orderId)->update([
                    'grand_total' => $total_price,
                    'coupon' => isset($sellerBuyerCoupon) && !empty($sellerBuyerCoupon) ? $sellerBuyerCoupon[0]['coupon'] : null,
                    'discount' => isset($totalDiscount) && $totalDiscount != null ? $totalDiscount : null,
                    'orderno_count' => $countOrder
                ]);

                $getRegisterFrom = DB::connection('lp_own_db')->table('users')->where('id', $getUserID)->value('register_from');
                
                if ($getRegisterFrom == "dmpankaj") {
                    $orderCompletedExists = DB::connection('lp_own_db')->table('seller_buyer_coupons')->where('buyer_id', $getUserID)->where('order_coupon_status', 1)->exists();
                    
                    if (!$orderCompletedExists) {
                        DB::connection('lp_own_db')->table('seller_buyer_coupons')->insert([
                            'buyer_id' => $getUserID,
                            'publisher_id' => 7604,
                            'coupon' => "DMPANKAJ",
                            'order_id' => $order->id,
                            'status' => 1,
                        ]);
                    }
                }
    
                if ($countOrder == 1) {
                    session()->forget('feedbackoncomplete');
                    session()->forget('feedback_id');
                    session()->put('feedbackoncomplete', 'show');
                    session()->put('feedback_id', $orderId);
                    $feedbackpopup = 'show';
                    $feedback_id = $orderId;
                } else {
                    $feedback = $countOrder / 5;
                    if (is_int($feedback)) {
                        $feedback_id = $orderId;
                        session()->forget('feedbackoncomplete');
                        session()->forget('feedback_id');
                        session()->put('feedbackoncomplete', 'show');
                        session()->put('feedback_id', $orderId);
                        $feedbackpopup = 'show';
                    } else {
                        $feedback_id = '';
                        $feedbackpopup = 'not show';
                    }
                }
            } else {
                return response()->json(array('success' => false, 'message' => 'Order not executed due to low wallet balance.'));
            }

        }

        return response()->json(array('success' => true, 'message' => 'Order placed successfully!!', 'flag'=>0));
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

    public function getActualPrice($orderAttr){

        $checkPriceCompareFromWebsites = DB::connection('lp_own_db')->table('websites')->where('id', $advertiserCartListing[$i]['website_id'])->first();

        if ($checkPriceCompareFromWebsites->publishing_price != null && !empty($checkPriceCompareFromWebsites->publishing_price)) {
            if ($checkPriceCompareFromWebsites->guest_post_commission_price) {
                $priceList['guest_post_price'] = ceil($checkPriceCompareFromWebsites->publishing_price + (($checkPriceCompareFromWebsites->publishing_price * $checkPriceCompareFromWebsites->guest_post_commission_price)/100));
            } else if ($checkPriceCompareFromWebsites->guest_post_value_addition) {
                $priceList['guest_post_price'] = ceil($checkPriceCompareFromWebsites->publishing_price + $checkPriceCompareFromWebsites->guest_post_value_addition);       
            }
        }

        if($checkPriceCompareFromWebsites->linkinsertion_price != null && !empty($checkPriceCompareFromWebsites->linkinsertion_price)){               
            if ($checkPriceCompareFromWebsites->link_insertion_commission_price) {
                $priceList['linkinsertion_price'] = ceil($checkPriceCompareFromWebsites->linkinsertion_price + (($checkPriceCompareFromWebsites->linkinsertion_price * $checkPriceCompareFromWebsites->link_insertion_commission_price)/100));
            } else if ($checkPriceCompareFromWebsites->link_insertion_value_addition) {
                $priceList['linkinsertion_price'] = ceil($checkPriceCompareFromWebsites->linkinsertion_price + $checkPriceCompareFromWebsites->link_insertion_value_addition);  
            }     
        }

        if ($checkPriceCompareFromWebsites->forbidden_category_guest_post_price != null) {
            if($checkPriceCompareFromWebsites->fc_guest_post_commission_price){
                $priceList['forbidden_category_guest_post_price']=ceil($checkPriceCompareFromWebsites->forbidden_category_guest_post_price + (($checkPriceCompareFromWebsites->forbidden_category_guest_post_price * $checkPriceCompareFromWebsites->fc_guest_post_commission_price)/100));
            } else if ($checkPriceCompareFromWebsites->fc_guest_post_value_addition){
                $priceList['forbidden_category_guest_post_price']=ceil($checkPriceCompareFromWebsites->forbidden_category_guest_post_price + $checkPriceCompareFromWebsites->value_addition);
            }
        }  
            
        if ($checkPriceCompareFromWebsites->forbidden_category_linkinsertion_price != null && $checkPriceCompareFromWebsites->other_category_price == null){
            if ($checkPriceCompareFromWebsites->fc_link_insertion_commission_price) {
                $priceList['forbidden_category_linkinsertion_price']=ceil($checkPriceCompareFromWebsites->forbidden_category_linkinsertion_price + (($checkPriceCompareFromWebsites->forbidden_category_linkinsertion_price * $checkPriceCompareFromWebsites->fc_link_insertion_commission_price)/100));
            } else if ($checkPriceCompareFromWebsites->fc_link_insertion_value_addition) {
                $priceList['forbidden_category_linkinsertion_price']=ceil($checkPriceCompareFromWebsites->forbidden_category_linkinsertion_price + $checkPriceCompareFromWebsites->fc_link_insertion_value_addition);
            }
        }

        return $priceList;
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
        $order_attribute_id = $request->param['order_attribute_id'] ?? $request->order_attribute_id;
        $main_user_id = $request->param['main_user_id'] ?? $request->main_user_id;

        if (!$order_attribute_id || $order_attribute_id == null || $order_attribute_id == "undefined") {
            return response()->json(['error' => 'Unauthorized', 'redirect_url' => route('marketplace')], 401);
        }

        if (strpos($order_attribute_id, '#') !== 0) {
            $order_attribute_id = '#' . $order_attribute_id;
        }
        
        $orderAttributeDetails = DB::connection('lp_own_db')
            ->table('order_attributes')
            ->where('order_lable', $order_attribute_id)
            ->first();

        $checkTransaction = DB::connection('lp_own_db')->table('transactions')->where('order_attribute_id', $orderAttributeDetails->id)->where('tra_settled_dt', null)->where('transaction_id', 'NOT LIKE', '%ORDRETURN%')->get()->toArray();
        
        if($checkTransaction != null) {
            $debit = DB::connection('lp_own_db')->table('transactions')->where('order_attribute_id', $orderAttributeDetails->id)->where('tra_credit_debit', 'LIKE', 'debit')->sum('payment');
            $credit = DB::connection('lp_own_db')->table('transactions')->where('order_attribute_id', $orderAttributeDetails->id)
                ->where(
                    function ($credit) {
                        $credit->where('tra_credit_debit', 'LIKE', 'credit');
                        $credit->orWhere('tra_credit_debit', null);
                    }
                );
            
            $credit = $credit->sum('payment');
            $payAmount =$credit - $debit;
            
            if(($payAmount == 0)) {
                $checkTransaction = [];
            }
        }

        if ($orderAttributeDetails && $orderAttributeDetails->status == 7) {

            $countCompleteOrder =  DB::connection('lp_own_db')->table('order_attributes')
                ->join('orders', 'order_attributes.order_id', 'orders.id')
                ->where('orders.advertiser_id', $main_user_id)->select('order_attributes.order_complete_accept_no')->orderBy('order_attributes.order_complete_accept_no', 'desc')->first();

            $countCompleteOrder = $countCompleteOrder->order_complete_accept_no + 1;

            $chkuserlogin = DB::connection('lp_own_db')->table('order_attributes')->join('orders', 'orders.id', 'order_attributes.order_id')
                ->join('seller_buyer_coupons', 'orders.advertiser_id', 'seller_buyer_coupons.buyer_id')
                ->join('users', 'users.id', 'seller_buyer_coupons.publisher_id')
                ->where('seller_buyer_coupons.create_status', 'affiliate')
                ->where('order_attributes.id', $orderAttributeDetails->id)
                ->select('order_attributes.total', 'order_attributes.seller_discount_amt', 'publisher_id', 'seller_buyer_coupons.id as seller_buyer_id', 'users.email', 'users.name')
                ->first();

            if($chkuserlogin) {
                $appSettings = DB::connection('lp_own_db')->table('admin_settings')->get();
                $appSettingData = [];

                if (!empty($appSettings)) {
                    $data = $appSettings->map(
                        function ($item) {
                                return array(
                                    $item['meta_key'] => $item['meta_value'],
                                );
                        }
                    )->toArray();

                    if ($data) {
                        $appSettingData = call_user_func_array('array_merge', $data);
                    }
                }

                $coupon_status = (int) $appSettingData['coupon_status'];
                $coupon_discount = (int) $appSettingData['coupon_discount'];
                $seller_reffer_amount = (int) $appSettingData['seller_refer_amount'];

                $dis_amt = $chkuserlogin->discount_amount ? $chkuserlogin->discount_amount : 0;
                $totalamt = $chkuserlogin->total - $dis_amt;

                if ($chkuserlogin->publisher_id == 25940) {
                    $disc_amt = $totalamt * 4/100;
                } else {
                    $disc_amt = $totalamt * 2/100;
                }

                DB::connection('lp_own_db')->table('transactions')->insert([
                    'publisher_id' => $chkuserlogin->publisher_id,
                    'transaction_id' => 'AFFILIATECOMM-' . date('Ymd-H:i:s'),
                    'seller_transaction_id' => 'REFER2-' . date('Ymd') . rand(11, 99) . $chkuserlogin->publisher_id,
                    'tra_credit_debit' => 'credit',
                    'admin_credit_debit' => 'debit',
                    'seller_buyer_coupon_id' => $chkuserlogin->seller_buyer_id,
                    'payment' => $disc_amt,
                    'order_type' => $orderAttributeDetails->content_writter,
                    'order_id_lable' => $orderAttributeDetails->order_lable,
                    'order_attribute_id' => $orderAttributeDetails->id,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $datas['publisher_name'] = $chkuserlogin->name;
                $datas['publisher_email'] = $chkuserlogin->email;
                $datas['publisher_disc_amt'] = "$".$chkuserlogin->seller_discount_amt;
            
                $jsonArray = escapeshellarg(json_encode($datas));
                $additionalParam = "couponSuccessAdv";
                $command = 'php ' . base_path('artisan') . ' background:mailSend ' . $jsonArray . ' ' . $additionalParam . '> /dev/null 2>&1 &';
                exec($command);
            }

            $getRegisterFrom = DB::connection('lp_own_db')->table('users')->where('id', $main_user_id)->value('register_from');

            if($getRegisterFrom == "dmpankaj") {
                $dmPankajOrderCompleted = DB::connection('lp_own_db')->table('seller_buyer_coupons')->where('buyer_id', $main_user_id)->where('order_coupon_status', 1)->exists();

                if(!$dmPankajOrderCompleted) {
                    DB::connection('lp_own_db')->table('seller_buyer_coupons')->where('buyer_id', $main_user_id)->where('order_id', $orderAttributeDetails->order_id)->where('publisher_id', 7604)->where('order_coupon_status', 0)->update(['order_coupon_status' => 1]);
                        
                    DB::connection('lp_own_db')->table('transactions')->insert([
                        'transaction_id' => 'REFER25-' . date('Ymd-H:i:s'),
                        'tra_credit_debit' => 'credit',
                        'order_type' => $orderAttributeDetails->content_writter,
                        'order_id_lable' => $orderAttributeDetails->order_lable,
                        'admin_credit_debit' => 'debit',
                        'seller_transaction_id' => 'REFER25-' . date('Ymd') . rand(11, 99),
                        'publisher_id' => 7604,
                        'payment' => 25,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            if(empty($checkTransaction) && (count($checkTransaction) < 1)) {

                $website = DB::connection('lp_own_db')->table('websites')->where('id', $orderAttributeDetails->website_id)->first();

                DB::connection('lp_own_db')->table('transactions')->insert([
                    'tra_credit_debit' => 'credit',
                    'admin_credit_debit' => 'debit',
                    'transaction_id' => 'ORDCOMPLETION-' . $orderAttributeDetails->order_lable . '-' . date('Ymd-H:i:s'),
                    'publisher_id' => $website->publisher_id,
                    'order_attribute_id' => $orderAttributeDetails->id,
                    'seller_transaction_id' => $this->generateRandomTransactionId(6, "TRSID") . time(),
                    'payment' => $orderAttributeDetails->price,
                    'order_type' => $orderAttributeDetails->content_writter,
                    'order_id_lable' => $orderAttributeDetails->order_lable,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'credit_or_debit' => 0,
                ]);

                $CheckCoupon = DB::connection('lp_own_db')->table('order_attributes')->join('orders', 'orders.id', 'order_attributes.order_id')
                    ->join('websites', 'websites.id', 'order_attributes.website_id')
                    ->join('users as u1', 'u1.id', 'websites.publisher_id')
                    ->leftjoin('seller_buyer_coupons as sbc', 'orders.advertiser_id', 'sbc.buyer_id')
                    ->join('users as u2', 'u2.id', 'sbc.publisher_id')
                    ->where('order_attributes.id', $orderAttributeDetails->id)
                    ->select('sbc.publisher_id', 'sbc.coupon', 'sbc.order_coupon_status', 'u1.name', 'u1.email', 'u2.name as coupon_publisher_name', 'u2.email as coupon_publisher_email')->first();

                if (!empty($CheckCoupon)) {
                    $CheckCouponInOrder = DB::connection('lp_own_db')->table('order_attributes')->join('orders', 'orders.id', 'order_attributes.order_id')
                        ->where('orders.coupon', $CheckCoupon['coupon'])
                        ->where('order_attributes.id', $orderAttributeDetails->id)->first();


                    if (!empty($CheckCouponInOrder['coupon'])) {
                        $updateCouponOrderStatus = DB::connection('lp_own_db')->table('seller_buyer_coupons')->join('users', 'users.id', 'seller_buyer_coupons.publisher_id')
                            ->where('publisher_id', $CheckCoupon['publisher_id'])
                            ->where('coupon', $CheckCoupon['coupon'])
                            ->where('buyer_id', $main_user_id)
                            ->update(array('order_coupon_status' => 1));

                        $data['publisher_name'] = $CheckCoupon['coupon_publisher_name'];
                        $data['publisher_email'] = $CheckCoupon['coupon_publisher_email'];
                        $data['publisher_coupon'] = $CheckCoupon['coupon'];
                    }
                }

                // $notification = Notifications::publisherWalletNotify($orderAttributeDetails->price, "wallet", $website->publisher_id);
            }

            // $notification = OrderAttributeHelper::createOrderNotification($orderAttributeDetails);

            DB::connection('lp_own_db')->table('order_attribute_logs')->insert([
                'order_attribute_id' => $orderAttributeDetails->id,
                'status' => 6,
                'action_by' => $main_user_id,
                'created_at' => now(),
                'updated_at' => now(),
                'delay_approve' => 0,
                'status_desc' => 0
            ]);

            $updateInLP = DB::connection('lp_own_db')
                ->table('order_attributes')
                ->where('order_lable', $order_attribute_id)
                ->update(['status' => 6, 'order_complete_accept_no' => $countCompleteOrder]);

            $getPublisher = DB::connection('lp_own_db')->table('order_attributes')->join('websites', 'websites.id', 'order_attributes.website_id')
                ->join('users', 'users.id', 'websites.publisher_id')
                ->where('order_attributes.order_lable', $order_attribute_id)
                ->select('websites.website_url', 'users.name', 'users.email', 'order_attributes.order_lable', 'order_attributes.order_id', 'order_attributes.created_at', 'order_attributes.url', 'order_attributes.updated_at')->first();
        }

        if (isset($countCompleteOrder)) {
            if ($countCompleteOrder == 1) {
                $feedbackpopup = 'show';
                session()->forget('feedbackoncomplete');
                session()->forget('feedback_id');
                session()->put('feedbackoncomplete', 'show');
                session()->put('feedback_id', $orderAttributeDetails->id);

            } else {
                $feedback = $countCompleteOrder / 5;
                if (is_int($feedback)) {
                    session()->forget('feedbackoncomplete');
                    session()->forget('feedback_id');
                    session()->put('feedbackoncomplete', 'show');
                    session()->put('feedback_id', $orderAttributeDetails->id);
                    $feedbackpopup = 'show';
                } else {
                    $feedbackpopup = 'not show';
                }
            }
        } else {
            $feedbackpopup = 'not show';
        }
        
        return response()->json(array('success' => true, 'message' => 'Order completed Successfully'));
    }

    private function generateRandomTransactionId($length, $prefix)
    {
        try {
            $pool = array_merge(range(0, 9));
            $key = "";
            for ($i = 0; $i < $length; $i++) {
                $key .= $pool[mt_rand(0, count($pool) - 1)];
            }
            return $prefix . $key;
        } catch (Exception $e) {
            Exceptions::exception($e);
        }
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

    public function hireCartDataDetail(Request $request)
    {
        $cartDetails =  DB::table('carts')
            ->leftJoin('phpfmv_gpmarketplace.websites', 'carts.website_id', '=', 'websites.id')
            ->where('carts.id', $request->cart_id)
            ->select('carts.*', 'websites.dofollow_link', 'websites.nofollow_link')
            ->first();

        if (!$cartDetails || empty($cartDetails) || $cartDetails == null) {
            return response()->json(['error' => 'Unauthorized', 'redirect_url' => route('marketplace')], 401);
        }

        $websiteDetail = DB::connection('lp_own_db')->table('advertiser_marketplace')->where('website_id', $cartDetails->website_id)->first();

        $expertprice = DB::connection('lp_own_db')->table('expert_prices')->where('status', 1)
            ->orderByRaw("CAST(SUBSTRING_INDEX(name, ' ', 1) AS UNSIGNED) ASC")
            ->get()
            ->toArray();

        $countries = DB::connection('lp_own_db')->table('countries')->get();

        if ($cartDetails->marketplace_type == 1) {
            $categories = DB::connection('lp_own_db')->table('categories')->where('other_category_status', 1)->orderby('name', 'asc')->get()->toArray();
        } else {
            $categories = DB::connection('lp_own_db')->table('categories')->where('other_category_status', 0)->orderby('name', 'asc')->get()->toArray();
        }

        $languageData = DB::connection('lp_own_db')->table('websites')->where('id', $cartDetails->website_id)->select('language')->first();
        $languageList = explode(',', $languageData->language);

        $cartListHtml =  view('hire_detail', compact(
            'cartDetails',
            'categories',
            'expertprice',
            'countries',
            'websiteDetail',
            'languageList'
        ))->render();

        return response()->json(array('success' => true, 'cartListHtml' => $cartListHtml, 'cartDetails'=>$cartDetails , 'languageList' => $languageList, 'cart data fetch'));
    }
}