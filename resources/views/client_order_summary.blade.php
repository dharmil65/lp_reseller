<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Order Summary</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ url('css/advertiser_custom_new.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/cart_wishlist.css') }}">
    <link href="{{ asset('vendors/iconic-fonts/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        .invalid {
            border: 2px solid red !important;
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
        <header class="site-header marketplace-header">
            <div class="side-logo">
                <a href="#"><img src="{{asset('assets/images/side-logo.png')}}" alt="side-logo" loading="lazy"></a>
                <div class="balance" id="addFundsBtn">
                    <h5> {{ "$".$balance ?? '' }} </h5>
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
            <input type="hidden" id="end_client_id" name="end_client_id" value="{{ request()->end_client_id ? request()->end_client_id : null }}">
            <section class="order-summary-wrapper">
                <div class="container">
                    <div class="cart-title">
                        <h4>Order Summary</h4>
                        <a href="" class="btn button"><i class="fas fa-long-arrow-alt-left"></i> Go Back</a>
                    </div>
                    <table class="order-summary-table" id="order_summary">
                        <thead>
                            <tr class="summry-header">
                                <th>Website</th>
                                <th>Order type</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <!-- <th>Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderSummaryData as $key => $val)
                            
                            <tr class="summry-detail" id="{{$val['website_id']}}">
                                <td class="website">
                                    {{-- <a href="javascript:void(0)" class="disabled-link">{{$val['website_name']}} --}}
                                    <a href="{{ $val['website_url'] }}" target="_blank">{{$val['website_name']}}
                                    <?php if($val['marketplace_type'] == 1){?>
                                        <img src="{{asset('assets/images/fc.webp')}}" />
                                    <?php } ?>
                                    </a>
                                    <?php
                                        if($val['marketplace_type'] == 1){
                                            if (isset($val['forbiddencategory']) && $val['forbiddencategory'] != null) {
                                                $category_list = explode(',', $val['forbiddencategory']);
                                                $categoryCount = count($category_list);
                                                if ($categoryCount > 1) {
                                                    $categoryData = $category_list[0] . ' +' . ($categoryCount - 1);
                                                    unset($category_list[0]);
                                                    $tooltip = implode(',', $category_list);
                                                } else {
                                                    $categoryData = (isset($category_list[0]) && $category_list[0] != null) ?  implode(',', $category_list) : 'NA';
                                                    $tooltip = implode(',', $category_list);
                                                }
                                            } else {
                                                $categoryData = 'NA';
                                                $tooltip = '';
                                            }
                                        }else{
                                            if (isset($val['category']) && $val['category'] != null) {
                                                $category_list = explode(',', $val['category']);
                                                $categoryCount = count($category_list);
                                                if ($categoryCount > 1) {
                                                    $categoryData = $category_list[0] . ' +' . ($categoryCount - 1);
                                                    unset($category_list[0]);
                                                    $tooltip = implode(',', $category_list);
                                                } else {
                                                    $categoryData = (isset($category_list[0]) && $category_list[0] != null) ?  implode(',', $category_list) : 'NA';
                                                    $tooltip = implode(',', $category_list);
                                                }
                                            } else {
                                                $categoryData = 'NA';
                                                $tooltip = '';
                                            }
                                        }
                                                

                                    ?>
                                    <?php if ($categoryData != 'NA') { ?>

                                        <p>{{(isset($val['marketplace_type']) && $val['marketplace_type'] == 1) ? 'Forbidden Category' : 'Category'}}:<span category-title="{{ $tooltip }}"> {{ $categoryData}}
                                            </span>
                                        <?php } else { ?>
                                        <p>{{(isset($val['marketplace_type']) && $val['marketplace_type'] == 1) ? 'Forbidden Category' : 'Category'}}:{{ $categoryData}}
                                        </p>
                                    <?php } ?>
                                </td>
                                <td>
                                    <table>
                                        @if($val['quantity']['provide_content']>0)
                                        <tr>
                                            <td style="padding: 0;">Provide Content</td>
                                        </tr>
                                        @endif
                                        @if($val['quantity']['hire_content']>0)
                                        <tr>
                                            <td style="padding: 0;">Hire Content Writer  <span data-title="It includes content and guest post price"><img src="{{asset('assets/images/exclamation-mark.png')}}" alt="exclamation-mark"></span></td>
                                        </tr>
                                        @endif
                                        @if($val['quantity']['link_insertion']>0)
                                        <tr>
                                            <td style="padding: 0;">Link Insertion</td>
                                        </tr>
                                        @endif
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        @if($val['quantity']['provide_content']>0)
                                        <tr>
                                            <td style="padding: 0;" class="quantity">{{$val['quantity']['provide_content']}}</td>
                                        </tr>
                                        @endif
                                        @if($val['quantity']['hire_content']>0)
                                        <tr>
                                            <td style="padding: 0;" class="quantity">{{$val['quantity']['hire_content']}}</td>
                                        </tr>
                                        @endif
                                        @if($val['quantity']['link_insertion']>0)
                                        <tr>
                                            <td style="padding: 0;" class="quantity">{{$val['quantity']['link_insertion']}}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        @if($val['quantity']['provide_content']>0)
                                        <tr>
                                            <td style="padding: 0;" class="total">${{$val['total']['provide_content']}}</td>
                                        </tr>
                                        @endif
                                        @if($val['quantity']['hire_content']>0)
                                        <tr>
                                            <td style="padding: 0;" class="total">${{$val['total']['hire_content']}}</td>
                                        </tr>
                                        @endif
                                        @if($val['quantity']['link_insertion']>0)
                                        <tr>
                                            <td style="padding: 0;" class="total">${{$val['total']['link_insertion']}}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                    @if($total > $balance)
                        <div class="summary-note">
                            <p><span><b>Note: </b></span>There is a low balance in your wallet, add amount to place order quickly.</p>
                        </div>
                    @endif
                    <div class="summary-total">
                        <div class="order-details">
                            <ul>
                                <li>
                                    <span class="order-details-title">Total:</span>
                                    <span class="order-details-price totalamount">${{ $total }}</span>
                                </li>
                            </ul>
                        </div>
                        <button type="button" class="btn button order-btn" id="place_order_btn"><span id="place_order_btn_text">Place Order</span>
                        <div class="search-loader">
                            <div class="spinner spinner-8">
                                <div class="ms-circle1 ms-child"></div>
                                <div class="ms-circle2 ms-child"></div>
                                <div class="ms-circle3 ms-child"></div>
                                <div class="ms-circle4 ms-child"></div>
                                <div class="ms-circle5 ms-child"></div>
                                <div class="ms-circle6 ms-child"></div>
                                <div class="ms-circle7 ms-child"></div>
                                <div class="ms-circle8 ms-child"></div>
                                <div class="ms-circle9 ms-child"></div>
                                <div class="ms-circle10 ms-child"></div>
                                <div class="ms-circle11 ms-child"></div>
                                <div class="ms-circle12 ms-child"></div>
                            </div>
                        </div>
                        </button>
                        
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="{{asset('assets/js/sweetalert2.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.search-loader').css('display','none');

            let isOrderButtonDisabled = false;

            if ($('.summary-note').is(':visible')) {
                $('#place_order_btn').prop('disabled', true);
                isOrderButtonDisabled = true;
            }

            $('#place_order_btn').on('click', function (e) {
                if (isOrderButtonDisabled) {
                    return false;
                }

                $('.search-loader').css('display', 'block');
                $('#place_order_btn').prop('disabled', true);
                isOrderButtonDisabled = true;
                $('#place_order_btn_text').text('Processing....');

                let urlParams = new URLSearchParams(window.location.search);
                let endClientId = urlParams.get('end_client_id') || '0';

                $.ajax({
                    type: 'GET',
                    url: "/api/cart/place-order",
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: { end_client_id: endClientId },
                    dataType: "json",
                    success: function (response) {
                        let redirectUrl = "{{ route('marketplace') }}?end_client_id=" + endClientId;
                        window.location.href = redirectUrl;
                    }
                });
            });
        });
    </script>

</body>
</html>