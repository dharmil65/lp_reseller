<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ url('css/advertiser_custom_new.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/cart_wishlist.css') }}">
    <link href="{{ asset('vendors/iconic-fonts/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}" />  
    <link href="https://fonts.googleapis.com/css2?family=Hepta+Slab:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .logout-container {
            text-align: right;
            margin: 20px;
        }
        .logout-btn {
            background-color: #d9534f;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .logout-btn:hover {
            background-color: #c9302c;
        }
        .site-header {
            background: #275570;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .site-header .side-logo a img {
            padding: 0 15px;
        }

        .site-header .main-navigation ul {
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .site-header .main-navigation ul li {
            padding: 0;
            opacity: 0.8;
            font-size: 15px;
            text-transform: capitalize;
            font-weight: 300;
            letter-spacing: 1px;
            color: #fff;
        }

        .site-header .main-navigation ul li a {
            color: #Ffff;
            text-decoration: none;
        }

        .marketplace-header .menu-icon .menu-icon-detail {
            display: flex;
            align-items: center;
            margin-bottom: 0;
            justify-content: flex-end;
            padding-left: 40px;
            list-style: none;
        }

        .marketplace-header .menu-icon li a {
            padding: 0 20px;
            position: relative;
            cursor: pointer;
        }

        .marketplace-header .menu-icon li a .notification-number {
            position: absolute;
            top: -10px;
            right: 6px;
            background: #fd6a3a;
            border-radius: 6px;
            color: #fff;
            font-size: 10px;
            text-align: center;
            min-width: 20px;
            height: 20px;
            line-height: 20px;
            padding: 2px;
        }

        .marketplace-header .profile-wrapper {
            border: 1px solid #fff;
            border-radius: 20px;
            position: relative;
        }

        .marketplace-header .menu-icon li a {
            padding: 0 20px;
            position: relative;
            cursor: pointer;
            color: #fff;
        }

        .marketplace-header .profile-wrapper a img {
            padding-right: 5px;
            max-width: 45px;
            height: 40px;
            border-radius: 50%;
        }

        .profile-wrapper .dropdown-toggle::after {
            position: absolute;
            top: 18px;
        }

        .marketplace-header .profile-wrapper .dropdown-menu {
            position: absolute;
            right: 0;
            top: 53px;
            border-radius: 10px;
            z-index: 9999;
            font-size: 16px;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .15);
            padding: 0;
            width: 160px;
            display: none;
        }

        .marketplace-header .profile-wrapper .dropdown-menu .dropdown-item {
            padding-left: 15px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .marketplace-header .profile-wrapper .dropdown-menu a {
            color: #275570;
            padding: 0;
            font-size: 14px;
            position: relative;
            cursor: pointer;
        }

        .profile-wrapper .dropdown-item a img {
            padding-right: 10px;
            max-width: 35px;
            height: auto;
            border-radius: 50%;
        }

        .marketplace-header li.profile-wrapper a {
            padding: 0 20px 0 0;
        }

        .marketplace-header .profile-wrapper .dropdown-menu.show {
            display: block;
        }

        label.error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .side-logo {
            display: flex;
            align-items: center;
            gap: 0 11px;
        }

        .site-header .side-logo a img {
            max-width: 200px;
            padding: 0 15px;
        }

        .side-logo h5 {
            margin-bottom: 0;
            color: #fff;
            font-size: 30px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <input type="hidden" name="user_id" id="user_id" value="">
        <header class="site-header marketplace-header">
            <div class="side-logo">
                <a href="#"><img src="{{asset('assets/images/side-logo.png')}}" alt="side-logo" loading="lazy"></a>
                <div class="balance" id="addFundsBtn">
                    <h5 id="walletBalance"> {{ (isset($walletBalance) && $walletBalance > 0) ? "$".$walletBalance : "$0" }} </h5>
                    <p> <span><img src="{{asset('assets/images/hedaer-plus.svg')}}" alt="hedaer-plus" loading="lazy"></span> Add Funds</p>
                </div>
            </div>
            <nav class="main-navigation">
                <ul>
                    <li><a href="#" class="">Dashboard</a></li>
                    <li><a href="{{ route('marketplace') }}" class="active">Marketplace</a></li>
                    <li><a href="{{ route('orders') }}" class="">My Orders</a></li>
                </ul>
            </nav>
            <div class="menu-icon icon-menu">
                <ul class="menu-icon-detail">
                    <li><a  id="wishlist_btn"><img src="{{asset('assets/images/heart.png')}}" alt="heart"><span class="notification-number d-none" loading="lazy" id="wishlistcount"></span></a></li>
                    <li><a id="cart_btn_header"><img src="{{ asset('assets/images/buy.png') }}" alt="buy"><span class="notification-number {{ isset($cartTotal) && $cartTotal > 0 ? '' : 'd-none' }}" loading="lazy" id="cartcount">{{ isset($cartTotal) && $cartTotal > 0 ? $cartTotal : '' }}</span></a></li>
                    <li class="profile-wrapper dropdown">
                        <a class="dropdown-toggle" href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="" alt="profile" loading="lazy"></a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li class="dropdown-item">
                                <a href="javascript:void(0);">
                                    <span><img src="{{asset('assets/images/my-profile.png')}}" alt="profile">My Profile</span>
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="javascript:void(0);">
                                    <span><img src="{{asset('assets/images/my-profile.png')}}" alt="notify-team">Notify Team</span>
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="javascript:void(0);">
                                    <span><img src="{{asset('assets/images/billings.png')}}" alt="billings">Billings &amp; Funds</span>
                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a id="logout_advertiser" href="{{ route('logout') }}">
                                    <span><img src="{{asset('assets/images/logout.png')}}" alt="logout">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>
        <div class="site-wrapper">
            <input type="hidden" id="end_client_id" name="end_client_id" value="{{ (isset(request()->end_client_id) && request()->end_client_id != null) ? request()->end_client_id : null }}">
            <section class="cart-wrapper">
                <div class="container-fluid">
                    <div class="cart-title-wrapper">
                        <div class="cart-title">
                            <h4>cart</h4>
                        </div>
                        <div class="cart-total" id="go_to_checkout_detail">
                            <p>Order Total: <span>{{ "$".$cartTotalAmount ?? 0 }}</span></p>
                            <a href="" id="go_to_summary_btn" class="btn button go_to_summary_btn active">Go to Order Summary <img src="{{asset('assets/images/summary-right.svg')}}" alt="summary-right"></a>
                        </div>
                        <div class="backlink-add" id="success_msg">
                            <img src="{{asset('assets/images/cart-backlink-add.svg')}}" alt="cart-backlink-add">
                            <div class="backlink-title">
                                <h5 id="success_msg_text">Backlink Successfully Added</h5>
                            </div>
                            <img src="{{asset('assets/images/cart-backlink-close.svg')}}" class="backlink-close" alt="cart-backlink-close">
                        </div>
                        <div class="backlink-delete">
                            <img src="{{asset('assets/images/cart-delete-backlink.svg')}}" alt="cart-delete-backlink">
                            <div class="backlink-title">
                                <h5>Can not delete this Backlink</h5>
                            </div>
                            <img src="{{asset('assets/images/cart-backlink-close.svg')}}" class="backlink-close" alt="cart-backlink-close">
                        </div>
                    </div>

                    <div class="cart-info">
                        <div class="cart-site-wrapper">
                            <div class="cart-site-listing">
                                <div class="cart-listing-title">
                                    <form>
                                        <div class="form-group">
                                            <div class="Checkbox">
                                                <input type="checkbox" name="site" id="cart_website_checkbox_parent" class="cart_website_checkbox_parent">
                                                <div class="Checkbox-visible"></div>
                                            </div>
                                            <label for="sites">Sites</label>
                                        </div>
                                    </form>
                                    <div class="cart-action">
                                        <a id="multiple_menul_link"><i id="menu_list_cart" class="fas fa-ellipsis-v"></i></a>
                                        <div class="cart-action-info" id="multiple_menu">
                                            <a id="multiple_add_to_wishlist"><img src="{{asset('assets/images/cart-action-wishlist.svg')}}" alt="cart-action-wishlist"> Move to Wishlist</a>
                                            <a id="multiple_remove_cart"><img src="{{asset('assets/images/cart-action-remove.svg')}}" alt="cart-action-remove"> Remove</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="cart-list-data">
                                <ul class="cart-list-data">
                                    <?php $cartNo = 0; $DataNo = 0; $websiteActive = ''; $active_marketplace_type = 0; ?>
                                    @foreach($getCartData as $cartData)
                                        <?php $DataNo++; ?>

                                        <li data-list-no="{{ $DataNo }}" 
                                            class="cart_list 
                                            @if($cartNo == 0 && $websiteActive == '') active 
                                            @elseif(($websiteActive == $cartData->cart_web_id) && ($active_marketplace_type == $cartData->marketplace_type) && ($websiteActive != '')) active  
                                            @endif" 
                                            data-id="{{ $cartData->cart_id }}" 
                                            id="cart_list_{{ $cartData->cart_id }}" 
                                            data-web-id="{{ $cartData->cart_web_id }}" 
                                            data-type="{{ $cartData->marketplace_type }}" 
                                            data-url="{{ $cartData->host_url }}">

                                            <form>
                                                <div class="form-group">
                                                    @if(($cartData->marketplace_type == 1 || $cartData->deleted_at != null || $cartData->userDelete != null || $cartData->vacation_mode == 1 || $cartData->is_active || $cartData->website_status != 4 || $cartData->price_changed == 1) || ($cartData->total == null && $cartData->link_insertion_price == null))
                                                        <div class="cart_list_lable cart-listing-label  
                                                        @if($cartData->deleted_at != null || $cartData->userDelete != null || $cartData->vacation_mode == 1 || $cartData->is_active == 1 || $cartData->website_status == 3) unavailable 
                                                        @elseif($cartData->website_status == 2 || $cartData->website_status == 1) underreview 
                                                        @endif">
                                                            <p>
                                                                @if(($cartData->deleted_at != null || $cartData->userDelete != null || $cartData->is_active == 1 || $cartData->website_status == 3) || ($cartData->total == null && $cartData->link_insertion_price == null))
                                                                    @if($cartData->marketplace_type == 1)FC @endif Unavailable
                                                                @elseif($cartData->website_status == 2 || $cartData->website_status == 1 || $cartData->vacation_mode == 1 ) 
                                                                    @if($cartData->marketplace_type == 1) FC @endif Under Review
                                                                @elseif($cartData->price_changed == 1) 
                                                                    @if($cartData->marketplace_type == 1) FC @endif Price Updated
                                                                @else
                                                                    @if($cartData->marketplace_type == 1) FC @endif
                                                                @endif
                                                            </p>
                                                        </div>
                                                    @endif

                                                    <div class="Checkbox">
                                                        @if($cartData->deleted_at != null || $cartData->userDelete != null || $cartData->vacation_mode == 1 || $cartData->is_active == 1 || $cartData->website_status == 3)
                                                            <input type="checkbox" disabled name="cart_website">
                                                        @else
                                                            <input type="checkbox" name="cart_website[]" 
                                                                data-web-id="{{ $cartData->website_id }}" 
                                                                id="cart_website_checkbox_{{ $cartData->website_id }}" 
                                                                data-web-marketplacetype="{{ $cartData->website_id }}-{{ $cartData->marketplace_type }}" 
                                                                class="cart_website_checkbox">
                                                        @endif
                                                        <div class="Checkbox-visible"></div>
                                                    </div>

                                                    <label for="website" data-list-no="{{ $DataNo }}" 
                                                        class="cart_list_data cart_list_website_{{ $cartData->website_id }} 
                                                        cart_list_number_{{ $DataNo }} 
                                                        @if($cartNo == 0 && $websiteActive == '') active 
                                                        @elseif(($websiteActive == $cartData->website_id) && ($active_marketplace_type == $cartData->marketplace_type)) active  
                                                        @endif" 
                                                        data-id="{{ $cartData->cart_id }}" 
                                                        id="cart_{{ $cartData->cart_id }}" 
                                                        data-web-id="{{ $cartData->website_id }}" 
                                                        data-type="{{ $cartData->marketplace_type }}" 
                                                        data-url="{{ $cartData->host_url }}">
                                                        {{ $cartData->host_url }}
                                                    </label>

                                                    <?php
                                                        $totalContentAdded = isset($cartData->content_group) ? count(explode(',', $cartData->content_group)) : 0;
                                                    ?>
                                                    <div id="cart_pending_status_{{ $cartData->website_id }}" class="cart-listing-dott 
                                                         cart-dott-fill ">
                                                    </div>
                                                </div>
                                            </form>

                                            <input type="hidden" id="backlinkCount" value="@if(isset($cartData->dofollow_link) && $cartData->dofollow_link != null) {{ $cartData->dofollow_link }} @else {{ $cartData->nofollow_link }} @endif">

                                            <?php $cartNo++; ?>
                                            <div class="cart-tooltip" info-title="delete">
                                                <img src="{{ asset('assets/images/cart-action-remove.svg') }}" 
                                                    class="cart_remove_multiple  
                                                    @if($cartData->deleted_at != null || $cartData->userDelete != null || $cartData->vacation_mode == 1 || $cartData->is_active == 1 || $cartData->website_status == 3) wishlistHide @endif" 
                                                    data-id="{{ $cartData->cart_id }}" 
                                                    id="cart_remove_multiple{{ $cartData->cart_id }}" 
                                                    data-web-id="{{ $cartData->website_id }}" 
                                                    data-list-no="{{ $DataNo }}" 
                                                    web-marketplacetype="{{ $cartData->website_id }}-{{ $cartData->marketplace_type }}" 
                                                    data-type="{{ $cartData->marketplace_type }}" 
                                                    data-url="{{ $cartData->host_url }}" 
                                                    alt="cart-action-remove">
                                            </div>
                                        </li>

                                    @endforeach
                                </ul>

                                </div>
                            </div>
                            <div class="cart-listing-fill">
                                <p>Order Details:</p>
                                <div class="listing-fill fill-submitted">
                                    <p><span></span> Submitted</p>
                                </div>
                                <div class="listing-fill fill-Pending">
                                    <p><span></span> Pending</p>
                                </div>
                                <div class="cart-tourguide cart-listing-tourguide" style="display:none" id="stepTwo">
                                    <div class="cart-tourguide-detail">
                                        <h6>Track your submission status here: Green indicates completed submissions, while orange indicates pending submissions.</h6>
                                    </div>
                                    <div class="tourguide-step">
                                        <p>Step <span>2</span> of 3</p>
                                        <div class="cart-tourguide-btn">
                                            <a id="stepTwoNext" class="btn button btn-primary stepTwoNext">Next</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cart-right-wrapper">
                            <div class="cart-site-detail" id="cart-details-list">
                                <div class="cart-website-info">
                                    @if(count($getCartData) > 0)
                                        @php $firstCartItem = $getCartData[0]; @endphp
                                        <div class="cart-website-title">
                                            <a target="_blank" href="{{ $firstCartItem->website_url }}">{{ $firstCartItem->host_url }}</a>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#information_modal">
                                                <img src="{{ asset('assets/images/cart-information.svg') }}" alt="cart-information">
                                            </button>
                                            <a class="btn guidelines-btn" data-toggle="modal" data-target="#cart-guidelines-modal">
                                                <img src="{{ asset('assets/images/guidelines-question.svg') }}" alt="guidelines-question"> Guidelines
                                            </a>
                                            <p>Minimum Word Count: <span>{{ $firstCartItem->article_count ?? 500 }}</span></p>
                                            <p>Completion ratio: <span>0%</span></p>
                                        </div>
                                    @endif
                                </div>
                                <div class="cart-website-detail">
                                    <div class="cart-backlink-title">
                                        <h5></h5>
                                        <div class="cart-listing-dott"></div>
                                    </div>
                                    <ul class="cart-order-tab">
                                        <li class="cart-order-item+">
                                            <form></form>
                                        </li>
                                    </ul>
                                    <h5></h5>
                                    <div class="cart-order-edit"></div>

                                </div>
                                <div class="cart-order-add">
                                    <a id="add_backlink_msg" class="btn button btn-primary">+Add Backlink </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

<div class="modal provide-content-modal" tabindex="-1" role="dialog" id="provide-content">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="provide_content_modal_content">

            <div class="modal-header" id="provide_content_modal_section">
                <h5 class="modal-title provide_content_backlink" id="exampleModalLabel">
                    <span class="backlink-title" id="provide_backlink"> Backlink 2: </span> <span class="content-title">Provide Content</span> <span class="provide_content_price" id="provide_content_price"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="attachFileClose">
                    <span aria-hidden="true"><img src="{{asset('assets/images/cart-close.svg')}}" alt="cart-close"></span>
                </button>
            </div>
            <div class="modal-body" id="provide_content_modal_body">
             
                <form id="provide_content_detail" enctype="multipart/form-data">
                    <div id="provide_content_detail_data">
                        <div class="download-attachment">
                            <a id="download-attachment" href="/order/"><i class="fas fa-download"></i><span></span> </a>
                        </div>
                        <div class="user-image">
                            <img src="{{asset('assets/images/cart-plus.svg')}}" alt="cart-plus.svg">

                            <div class="edit-icon">
                                <p>Drop files here or</p>
                                <input id="attachment" type="file" name="attachment" accept=".doc,.docx" class="custom-file-input">
                                <span>Browse</span>
                            </div>
                        </div>

                        <span name="hidefilesAttach" id="hidefilesAttach"></span>

                        <label id="attachment-error" style="display:none" class="invalid" for="attachment">The attachment field is required</label>
                        <div class="form-group">
                            <label for="Special">Special Instructions </label>
                            <textarea cols="5" rows="5" id="instruction" name="instruction"></textarea>
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn button">Clear</button>
                            <button type="submit" id="" class="btn button btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade hire-content-modal" id="hire_content" tabindex="-1" role="dialog" aria-labelledby="provide-content-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title hire_content_backlink" id="exampleModalLabel">
                    <span class="backlink-title" id="hire_backlink"> Backlink 2: </span> <span class="content-title">Hire Content Writer</span> <span class="hire_content_price" data-price="" id="hire_content_price"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="attachFileClose">
                    <span aria-hidden="true"><img src="{{asset('assets/images/cart-close.svg')}}" alt="cart-close"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="download-sample">
                    <a target="_blank" href="https://drive.google.com/drive/folders/1eiUIT6U0zmEgxeXSrVGHVDp2QNof57iI" class="btn button btn-primary">Download Sample <img src="{{asset('assets/images/cart-download-sample.svg')}}" alt="cart-download-sample"> </a>
                </div>
                <form class="cart-form hire_content_detail" id="hire_content_detail" enctype="multipart/form-data">
                    <input type="hidden" name="hire_content_cart_id" id="hire_content_cart_id" value="">
                    <input type="hidden" name="hire_content_website_id" id="hire_content_website_id" value="">
                    <input type="hidden" name="hire_content_marketplace_type" id="hire_content_marketplace_type" value="">
                    <input type="hidden" name="hire_content_quantity" id="hire_content_quantity" value="">

                    <div class="row" id="hire_content_detail_data">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="word">Word Count<span>*</span></label>
                                <select name="word_count" class="word_count" id="word_count">
                                    <option value="">Select Word Count</option>
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suggestion">Title Suggestion</label>
                                <input type="text" id="titlesuggestion" class="titlesuggestion" name="titlesuggestion" placeholder="Suggest Title">
                                <input type="hidden" id="follow_link_hire_content_writer" class="follow_link_hire_content_writer" name="follow_link_hire_content_writer">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="word">Category<span>*</span></label>
                                <select name="category" class="category" id="category">
                                    <option value="">Select Category</option>
                                </select>
                                <label id="category-error" style="display:none" class="invalid" for="category">The category field is required</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="word">Keywords<span>*</span></label>
                                <input type="text" id="keywords" class="keywords" name="keywords" placeholder="Provide Keywords; Separated by comma">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prefered_language">Language<span>*</span></label>
                                <select name="prefered_language" class="prefered_language" id="prefered_language">
                                    <option value="">Select Language</option>
                                </select>
                                <label id="prefered_language-error" style="display:none" class="invalid" for="prefered_language">The prefered language field is required</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="referencelink">Reference Link<span>*</span></label>
                                <input type="text" name="referencelink" id="referencelink" placeholder="eg. https://example.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="anchortext">Anchor Text<span>*</span></label>
                                <input type="text" name="anchortext" id="anchortext" placeholder="Enter Anchor text">
                                <label style="display: none;" id="anchortext-error" class="invalid" for="anchortext">The anchor text field is required</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group landing-group">
                                <label for="targeturl">Landing Page URL<span>*</span></label>
                                <input type="text" name="targeturl" id="targeturl"
                                    placeholder="Enter Landing Page URL">

                            </div>
                        </div>

                        <div class="col-md-12" id="dynamicInputContainer"></div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Target Audience is from (Country)<span>*</span></label>
                                <select name="target_audience" class="target_audience" id="target_audience">
                                    <option value="">Select Target Audience</option>


                                </select>
                                <label id="target_audience-error" style="display:none" class="invalid" for="target_audience">The target audience field is required</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="word">Brief Note</label>
                                <textarea cols="20" rows="6" id="briefnote" name="briefnote" placeholder="Brief Note: Any additional notes required can be specified here in detail." class="specialinstruction"></textarea>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <h5 class="advance_writing_info">Advance Writing Information</h5>
                        </div>

                        <div class="col-md-4 advance_writing_info_list">
                            <div class="form-group">
                                <label for="word">Choose Writing</label>
                                <select name="choose_writing" class="choose_writing" id="choose_writing">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 advance_writing_info_list">
                            <div class="form-group">
                                <label for="word">Writing Style</label>
                                <select name="writing_style" class="writing_style" id="writing_style">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 advance_writing_info_list">
                            <div class="form-group">
                                <label for="word">Preferred Voice</label>
                                <select name="prefered_voice" class="prefered_voice" id="prefered_voice">

                                </select>
                            </div>
                        </div>
                    </div>
                   
            </div>

            </form>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="{{asset('assets/js/sweetalert2.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {

        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('walletBalance') && urlParams.has('cartTotal')) {
            localStorage.setItem('walletBalance', urlParams.get('walletBalance'));
            localStorage.setItem('cartTotal', urlParams.get('cartTotal'));
        }

        if (urlParams.has('userid')) {
            localStorage.setItem('userid', urlParams.get('userid'));
        }

        let walletBalance = localStorage.getItem('walletBalance') || '0';
        let cartTotal = localStorage.getItem('cartTotal') || '0';
        let userid = localStorage.getItem('userid') || '0';

        $('#walletBalance').text(walletBalance > 0 ? "$" + walletBalance : "$0");
        $('#cartcount').text(cartTotal > 0 ? cartTotal : '').toggleClass('d-none', cartTotal <= 0);
        $('#user_id').val(userid);

        setTimeout(function () {
            const userid = urlParams.get('userid');
            if (userid) {
                const newUrl = `${window.location.origin}${window.location.pathname}?userid=${userid}`;
                window.history.replaceState({}, document.title, newUrl);
            }
        }, 1500);

        var summaryBtn = $("#go_to_summary_btn");
        var endClientInput = $("#user_id");

        if (summaryBtn.length && endClientInput.length) {
            var endClientId = endClientInput.val().trim();
            summaryBtn.attr("href", endClientId ? `/api/cart/order-summary?end_client_id=` + encodeURIComponent(endClientId) : `/api/cart/order-summary`);
        }

        $(".cart_list").click(function (e) {
            e.preventDefault();

            $(".cart_list").removeClass("active");
            $(this).addClass("active");

            var selectedWebsiteId = $(this).data("web-id");

            var selectedCartItem = @json($getCartData);
            
            var cartItem = selectedCartItem.find(item => item.cart_web_id == selectedWebsiteId);

            if (cartItem) {
                $(".cart-website-info").html(`
                    <div class="cart-website-title">
                        <a target="_blank" href="${cartItem.website_url}">${cartItem.host_url}</a>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#information_modal">
                            <img src="{{ asset('assets/images/cart-information.svg') }}" alt="cart-information">
                        </button>
                        <a class="btn guidelines-btn" data-toggle="modal" data-target="#cart-guidelines-modal">
                            <img src="{{ asset('assets/images/guidelines-question.svg') }}" alt="guidelines-question"> Guidelines
                        </a>
                        <p>Minimum Word Count: <span>${cartItem.article_count ?? 500}</span></p>
                        <p>Completion ratio: <span>${cartItem.completion_ratio}%</span></p>
                    </div>
                `);
            }
        });

        $(".cart_list.active").trigger("click");

        setTimeout(function() {
            $('.cart_list_data.active').trigger('click');
        }, 1);

        $('body').on('click', '.cart_list_data', function() {
            var cart_id = $(this).attr('data-id');
            var marketplace_type = $(this).attr('data-type');
            var cart_url = $(this).attr('data-url');
            var website_id = $(this).attr('data-web-id');
            var endClientId = $('#user_id').val();

            $('.cart_list').removeClass('active');
            $('.cart_list_data').removeClass('active');

            $(this).addClass('active');
            $('#cart_list_' + cart_id).addClass('active');
            $('#cart_list_data' + cart_id).addClass('active');

            $.ajax({
                type: 'POST',
                url: "/api/cart/fetch-cart-data",
                data: {

                    'cart_id': cart_id,
                    'cart_url': cart_url,
                    'marketplace_type': marketplace_type,
                    'website_id': website_id,
                    'endClientId': endClientId,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(data) {
                    $('#information_modal_body').html(data.websiteinfo);
                    $('#cart-details-list').html(data.html);
                    $('#guidelines_item').html(data.guideline);
                },
                error: function(xhr, status, error) {
                    setTimeout(function() {
                        window.location.href = xhr.responseJSON.redirect_url;
                    }, 500);
                    return false;
                }
            });
        });

        $('body').on('click', '.provide_content', function() {
            var price = $(this).attr('data-price');
            var quantity = $(this).attr('data-quantity');
            var marketplace_type = $(this).attr('data-matketplace');
            var website_id = $(this).attr('data-web');
            var cart_id = $(this).attr('data-id');
            var showQuantity = $('#cart_backlink_no_' + cart_id).text();

            $.ajax({
                type: 'POST',
                url: "/api/cart/provide-cart-data-end-client",
                data: {
                    'type': 'provide_content',
                    'cart_id': cart_id,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(data) {
                    $('#provide_content_detail_data').html(data.cartListHtml);
                    languageListOfSelectedCart = data.languageList;
                    if (languageListOfSelectedCart.length === 1) {
                        window.setTimeout(function(){
                            $('#provide_content_detail .form-group .select2-selection__arrow').css('display', 'none');
                        }, 200);
                    } else {
                        window.setTimeout(function(){
                            $('#provide_content_detail .form-group .select2-selection__arrow').css('display', 'block');
                        }, 200);
                    }
                    $('#provide_content_price').text('$' + price);
                    $('#provide_content_website_id').val(website_id);
                    $('#provide_content_quantity').val(quantity);
                    $('#provide_content_marketplace_type').val(marketplace_type);
                    $('#provide_content_cart_id').val(cart_id);
                    $('#provide_backlink').text(showQuantity + ':');
                    $('#provide-content').modal('show');
                    $('#attachment').removeAttr('title');
                    $('#language').select2({
                        dropdownParent: $('.provide-content-modal .modal-content'),
                        minimumResultsForSearch: -1
                    });
                },
                error: function(xhr, status, error) {
                    setTimeout(function() {
                        window.location.href = xhr.responseJSON.redirect_url;
                    }, 500);
                    return false;
                }
            });
        });

        function hasNumber(myString) {
            var remove_space = myString.replace(/ /g, '');
            return /\d{6}/.test(remove_space);
        }

        function hasSkype(mystring) {
            return /live:.|cid.+[a-zA-Z][a-zA-Z0-9\.,\-_]{9,31}/.test(mystring);
        }

        function hasFiverr(mystring) {
            return /fiverr/.test(mystring);
        }

        function hasEmail(myString) {
            return /@/.test(myString);
        }

        function checkIfEmailInString(text) {
            var re =
                /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/;
            return re.test(text);
        }

        $.validator.addMethod("requiredfile", function(value, element) {
            return element.files.length > 0;
        }, "Attachment is required");

        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        });

        $.validator.addMethod("checkExtension", function(value, element) {
            var urlRegEx = new RegExp(/.*\.(DOC|doc|docx|DOCX)/g);
            return this.optional(element) || urlRegEx.test(value);
        }, "Only doc, docx can be accepted");

        $.validator.addMethod("noteValidation", function(value, element) {
            checkEmail = (checkIfEmailInString(value));
            checkNumber = (hasNumber(value));
            checkch = (hasEmail(value));
            skype = (hasSkype(value));
            fiverr = (hasFiverr(value));

            if (checkNumber == true || checkEmail == true || checkch == true || skype == true || fiverr == true) {
                return false;
            } else {
                return true;
            }
        }, "Warning! Your account may be suspended or terminated if you provide any personal information.");

        $('#provide_content_detail').validate({
            errorClass: 'invalid',
            validClass: 'valid',
            highlight: function(element) {
                $(element).addClass('invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('invalid');
            },
            rules: {
                attachment: {
                    requiredfile: true,
                    checkExtension: true,
                    filesize: 3145728,
                },
                instruction: {
                    noteValidation: true,
                    maxlength: 400,
                },
                language: {
                    required: function () {
                        return languageListOfSelectedCart.length > 1;
                    }
                }
            },
            messages: {
                attachment: {
                    requiredfile: "Attachment is required",
                    checkExtension: "Only .doc and .docx files are allowed",
                    filesize: "File must be less than 3MB"
                },
                instruction: {
                    noteValidation: "Instruction cannot be empty",
                    maxlength: "Maximum 400 characters allowed"
                },
                language: {
                    required: "Language is required"
                }
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });
                
                var formData = new FormData(form);
                var end_client_id = $('#user_id').val();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('type', 'provide_content');
                formData.append('end_client_id', end_client_id);

                $.ajax({
                    type: 'POST',
                    url: "/api/cart/add-quantity",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#provide-content').modal('hide');
                        toastr.success('Backlink details added successfully');
                    },
                    error: function(xhr, status, error) {
                        setTimeout(function() {
                            window.location.href = xhr.responseJSON.redirect_url;
                        }, 500);
                        return false;
                    }
                });
            }
        });

        $('body').on('click', '.info_msg_show_data_occupy', function() {
            toastr.info("Please clear the selected data type before proceeding.");
        });

        var price_word = parseInt($("select[name='word_count']").find(':selected').attr('data-price')) || 0;
        var languageNotEnglish = false;

        $('body').on('change', '.word_count, select[name="prefered_language"]', function() {
            var selectedLanguage = $('select[name=prefered_language]').find(':selected').val();
            var selectedWordCount = $('select[name=word_count]').find(':selected').val();
            
            if (selectedLanguage && selectedLanguage !== "English") {
                price_word = parseInt($("select[name='word_count']").find(':selected').attr('data-non-english-price')) || 0;
                languageNotEnglish = true;
            } else if (selectedLanguage === "English") {
                price_word = parseInt($("select[name='word_count']").find(':selected').attr('data-price')) || 0;
                languageNotEnglish = false;
            }

            var provied_price = parseInt($('#hire_content_price').attr('data-price')) || 0;
            var totalPrice = price_word + provied_price;

            if (price_word > 0 && selectedWordCount != '') { 
                $('.expert_price_list').css('display', 'block');
            } else {
                $('.expert_price_list').css('display', 'none');
            }

            $('.expert_price').text('$' + price_word);
            if (selectedWordCount != undefined && selectedWordCount != '' && selectedLanguage != '' && selectedLanguage != undefined) {
                $('#hire_content_price').text('$' + totalPrice);
            } else {
                $('#hire_content_price').text('$' + provied_price);
            }
        });

        $('body').on('click', '.hire_content', function() {

            $('#hire_content_detail')[0].reset();
            $('#hire_content_price').text('');
            $('#hire_content_price').attr('data-price', '');
            $('#hire_content_website_id').val('');
            $('#hire_content_quantity').val('');
            $('#hire_content_marketplace_type').val('');
            $('#hire_content_cart_id').val('');

            var cart_id = $(this).attr('data-id');
            var price = $(this).attr('data-price');
            var quantity = $(this).attr('data-quantity');
            var marketplace_type = $(this).attr('data-matketplace');
            var website_id = $(this).attr('data-web');
            var publishPrice = $(this).attr('data-publishprice');
            var showQuantity = $('#cart_backlink_no_' + cart_id).text();

            $('#hire_content_price').text('$' + price);
            $('#hire_content_price').attr('data-price', publishPrice);
            $('#hire_content_website_id').val(website_id);
            $('#hire_content_quantity').val(quantity);
            $('#hire_content_marketplace_type').val(marketplace_type);
            $('#hire_content_cart_id').val(cart_id);
            $('#hire_content').modal('show');

            if (languageNotEnglish == false) {
                var price = $(this).attr('data-price');
            } else {
                var price = $(this).attr('data-non-english-price')
            }

            $.ajax({
                type: 'POST',
                url: "/api/cart/hire-cart-data-detail",
                data: {
                    'cart_id': cart_id,
                    'type': 'hire_content',
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(data) {
                    $('#hire_content_detail_data').html(data.cartListHtml);
                    languageListOfHireContentWriter = data.languageList;

                    if (languageListOfHireContentWriter.length === 1) {
                        window.setTimeout(function () {
                            $('#select2-prefered_language-container').siblings('.select2-selection__arrow').css('display', 'none');
                        }, 200);
                    } else {
                        window.setTimeout(function () {
                            $('#select2-prefered_language-container').siblings('.select2-selection__arrow').css('display', 'block');
                        }, 200);
                    }

                    if(languageListOfHireContentWriter.length !== 1){
                        if(data.cartDetails.language == null || data.cartDetails.language == ''){
                            var $preferedLanguage = $('#prefered_language');
                            $preferedLanguage.prepend('<option value="" selected>Select Language</option>');
                        }
                    }

                    var dynamicInputCounter = $('.anchor_text_added').length;
                    var dynamicInputCounterfordata = dynamicInputCounter++;

                    $('#anchor_text_' + dynamicInputCounterfordata).rules("add", {
                        required: true,
                        messages: {
                            required: 'The anchor text field is required',
                        }
                    });

                    $('#target_url_' + dynamicInputCounterfordata).rules("add", {
                        required: true,
                        url: true,
                        messages: {
                            required: 'The landing page URL field is required',
                            url: 'Please enter a valid landing page URL'
                        }
                    });
                
                    $('#hire_backlink').text(showQuantity + ':');

                    $("#category").select2({
                        dropdownParent: $('#hire_content .modal-content')
                    });

                    $("#prefered_language").select2({
                        minimumResultsForSearch: -1,
                        dropdownParent: $('#hire_content .modal-content')
                    });

                    $("#target_audience").select2({
                        dropdownParent: $('#hire_content .modal-content')
                    });

                    $("#word_count").select2({
                        minimumResultsForSearch: -1,
                        dropdownParent: $('#hire_content .modal-content')
                    });

                    $("#choose_writing").select2({
                        minimumResultsForSearch: -1,
                        dropdownParent: $('#hire_content .modal-content')
                    });
                    $("#writing_style").select2({
                        minimumResultsForSearch: -1,
                        dropdownParent: $('#hire_content .modal-content')
                    });

                    $("#prefered_voice").select2({
                        minimumResultsForSearch: -1,
                        dropdownParent: $('#hire_content .modal-content')
                    });
                },
                error: function(xhr, status, error) {
                    setTimeout(function() {
                        window.location.href = xhr.responseJSON.redirect_url;
                    }, 500);
                    return false;
                }
            });
        });


        $(document).on('change', 'select', function() {
            $(this).valid();
        });

        var dynamicInputCounterfordata = 1;

        $('#hire_content_detail').validate({
            errorClass: 'invalid',
            validClass: 'valid',
            highlight: function(element, errorClass, validClass) {
                $(element).addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass(errorClass).addClass(validClass);
            },
            rules: {
                keywords: {
                    required: true,
                    normalizer: function(value) {
                        return $.trim(value);
                    }
                },
                targeturl: {
                    required: true,
                    url: true,
                    normalizer: function(value) {
                        return $.trim(value);
                    }
                },
                referencelink: {
                    required: true,
                    url: true,
                    normalizer: function(value) {
                        return $.trim(value);
                    }
                },
                anchortext: {
                    required: true,
                    normalizer: function(value) {
                        return $.trim(value);
                    },
                    maxlength: 100,
                },
                word_count: {
                    required: true,
                },
                category: {
                    required: true,
                },
                prefered_language: {
                    required: function () {
                        return languageListOfHireContentWriter.length > 1;
                    }
                },
                target_audience: {
                    required: true,
                },
                briefnote: {
                    noteValidation: true,
                    maxlength: 400,
                }
            },
            messages: {
                keywords: {
                    required: 'The keywords field is required',
                },
                targeturl: {
                    required: 'The landing page url field is required',
                    url: 'Please enter valid landing page url'
                },
                referencelink: {
                    required: 'The reference link field is required',
                    url: 'Please enter valid reference link'
                },
                anchortext: {
                    required: 'The anchor text field is required',
                },
                word_count: {
                    required: 'The word count field is required',
                },
                category: {
                    required: 'The category field is required'
                },
                prefered_language: {
                    required: 'The language field is required'
                },
                target_audience: {
                    required: 'The target audience field is required'
                }
            },
            submitHandler: function(form) {
                var website_id = $('#hire_content_website_id').val();
                var dynamicFields = {};
                var count = 1;
                var end_client_id = $('#user_id').val();

                $('.anchor_text_added').each(function(index, element) {
                    dynamicFields['anchor_text_' + count] = $(element).val();
                    dynamicFields['target_url_' + count] = $(element).closest('.row').find('input[name^="target_url"]').val();
                    count++;
                });

                var prefVal = $('#prefered_language').val();

                if (languageListOfHireContentWriter.length === 1) {
                   prefVal =  languageListOfHireContentWriter[0];
                } else {
                    prefVal =  $('#prefered_language').val();   
                }

                if(prefVal !== "English"){
                    languageNotEnglish = true;
                }

                if (languageNotEnglish == true) {
                    var expert_price = $("select[name='word_count']").find(':selected').attr('data-non-english-price');
                } else {
                    var expert_price = $("select[name='word_count']").find(':selected').attr('data-price');
                }

                $.ajax({
                    type: 'POST',
                    url: "/api/cart/add-quantity",
                    data: {
                        'website_id': website_id,
                        'words': $('#word_count').val(),
                        'titlesuggestion': $('#titlesuggestion').val(),
                        'categoryid': $('#category').val(),
                        'keywords': $('#keywords').val(),
                        'targeturl': $('#targeturl').val(),
                        'referencelink': $('#referencelink').val(),
                        'anchortext': $('#anchortext').val(),
                        'target_audience': $('#target_audience').val(),
                        'prefered_language':  prefVal,
                        'choose_writing': $('#choose_writing').val(),
                        'prefered_voice': $('#prefered_voice').val(),
                        'writing_style': $('#writing_style').val(),
                        'expert_price_id': $('#expert_price_id').val(),
                        'quantity': $('#hire_content_quantity').val(),
                        'type': 'hire_content',
                        'marketplace_type': $('#hire_content_marketplace_type').val(),
                        'briefnote': $('#briefnote').val(),
                        'expert_price_id': $("select[name='word_count']").find(':selected').attr('data-id'),
                        'expert_price': expert_price,
                        'languageNotEnglish': languageNotEnglish,
                        "_token": "{{ csrf_token() }}",
                        'dynamicFields': dynamicFields,
                        'end_client_id': end_client_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#hire_content').modal('hide');

                        var submitedCount = $('.cart-backlink-title .cart-dott-fill').length;
                        if (submitedCount == 4) {
                            var active_no = $('.cart_list_data.active').attr('data-list-no');
                            var curentActive = $('.cart_list_data.active').attr('data-web-id');

                            var active_websiteno = parseInt(active_no) + 1;
                            var website_active = $('label.cart_list_number_' + active_websiteno).attr('data-web-id');
                            if (website_active == undefined && website_active) {
                                var active_websiteno = active_no - 1;
                                var website_active = $('label.cart_list_number_' + active_websiteno).attr('data-web-id');
                                
                            }
                            cartListRefresh(website_active);
                        } else {
                            cartListRefresh(website_id);
                        }

                        toastr.info('Backlink details added successfully');
                    },
                    error: function(xhr, status, error) {
                        setTimeout(function() {
                            window.location.href = xhr.responseJSON.redirect_url;
                        }, 500);
                        return false;
                    }
                });
            }
        });
    });
</script>
</html>