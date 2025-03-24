<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>
        @if (Request::is('*orders*'))
            Orders
        @elseif (Request::is('*marketplace*'))
            Marketplace
        @elseif (Request::is('*cart*'))
            Cart
        @elseif (Request::is('*order-summary*'))
            Order Summary
        @else
            --
        @endif
    </title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ url('css/advertiser_custom_new.css') }}?v={{ config('css_versions.advertiser_custom_new_version') }}">
	<link rel="stylesheet" type="text/css" href="{{ url('css/reseller_custom.css') }}?v={{ config('css_versions.reseller_custom_css_version') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="{{ asset('vendors/iconic-fonts/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('css/cart_wishlist.css') }}">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}" />  
    <link href="https://fonts.googleapis.com/css2?family=Hepta+Slab:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="{{asset('assets/js/sweetalert2.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
<body>
    <div class="main-wrapper">
        <header class="site-header marketplace-header">
            <div class="side-logo">
                <a href="#"><img src="{{asset('assets/images/side-logo.png')}}" alt="side-logo" loading="lazy"></a>
                <div class="balance" id="addFundsBtn">
                    <h5 id="walletBalance"></h5>
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
                    <li><a id="cart_btn_header"><img src="{{ asset('assets/images/buy.png') }}" alt="buy"><span class="notification-number" loading="lazy" id="cartcount" style="display:none;"></span></a></li>
                    <li class="profile-wrapper dropdown">
                        <a class="dropdown-toggle" href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ isset(Auth::user()->userDetails) && !empty( Auth::user()->userDetails->image ) ? url('profile') .'/' . Auth::user()->userDetails->image : asset('assets/images/no-image.png') }}" alt="profile" loading="lazy"></a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
            @yield('content')
        </div>
    </div>
@yield('scripts')
</body> 
<script>
    $(document).ready(function(){
        
    });
</script>
</html>