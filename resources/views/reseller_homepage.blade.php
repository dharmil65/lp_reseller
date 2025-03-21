<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $resellerId = isset($resellerId) ? $resellerId : null;
        $resellerName = isset($resellerName) ? $resellerName : null;
        $walletBalance = DB::connection('lp_own_db')->table('wallets as w1')->selectRaw('SUM(w1.total) as total_balance')->where('w1.reseller_id', $resellerId)->whereRaw('w1.id = (SELECT MAX(id) FROM wallets WHERE user_id = w1.user_id)')->value('total_balance');
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Reseller Dashboard')</title>
    <link href="{{ asset('vendors/iconic-fonts/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ url('css/dashboard_custom.css') }}?v={{ config('css_versions.custom_css_version') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/admin_custom.css') }}?v={{ config('css_versions.admin_custom_css_version') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/reseller_custom.css') }}?v={{ config('css_versions.reseller_custom_css_version') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')

    <style>
        body {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #2c3e50;
            padding-top: 20px;
            color: white;
            transition: width 0.3s ease;
            overflow-x: hidden;
            position: fixed;
            left: 0;
            top: 40px;
            bottom: 0;
        }
        .sidebar ul {
            padding: 0;
            margin: 0;
        }
        .sidebar ul li {
            list-style: none;
            text-align: left;
            display: flex;
            align-items: center;
        }
         .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            width: 100%;
        }
        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #fff;
            color: #2c3e50;
        }
        .sidebar.collapsed {
            width: 60px;
        }
        .sidebar.collapsed ul li a span {
            display: none;
        }
        .sidebar ul li a i {
            font-size: 18px;
        }
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
            width: calc(100% - 250px);
        }
        .content-wrapper.expanded {
            margin-left: 60px;
            width: calc(100% - 60px);
        }
        .navbar {
            background: #34495e;
            padding: 10px;
            color: white;
            display: flex;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            margin-left: 20px;
        }
        .section-content.active {
            padding-top: 90px;
        }
        .res_logout {
            color: #fff;
        }
        .res_name {
            padding-right: 11px;
        }

        .admin-dashboard .table:not(.marketplaceTable) thead th {
            color: #fff !important;
        }

        .admin-dashboard .table thead th::before {
            display: none !important;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <button class="toggle-btn" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <div class="navbar_btns">
            <h5> {{ (isset($walletBalance) && $walletBalance > 0) ? "$".$walletBalance : "$100" }} </h5>
            <span class="res_name" id="res_name">Hello, {{ (isset($resellerName) && $resellerName != null) ? $resellerName : '' }}</span>
            <a href="{{ route('logout') }}" class="res_logout" id="res_logout">Log Out</a>
        </div>
    </nav>

    <div class="sidebar" id="sidebar">
        <ul>
            <li>
                <a href="{{ route('reseller-home', ['reseller_id' => $resellerId, 'reseller_name' => $resellerName]) }}">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>
            <li><a href="{{ route('logout') }}"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
            <li>
                <a href="{{ route('reseller_orders', ['reseller_id' => $resellerId, 'reseller_name' => $resellerName]) }}">
                    <i class="fas fa-shopping-cart"></i> <span>Orders</span>
                </a>
            </li>
            <li><a href="{{ route('logout') }}"><i class="fas fa-store"></i> <span>Price Structure Change</span></a></li>
            <li><a href="{{ route('logout') }}"><i class="fas fa-store"></i> <span>Users</span></a></li>
        </ul>
    </div>

    <section class="user-page content-wrapper" id="contentWrapper">
        <div class="container-fluid">
            @yield('content')
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    @stack('scripts')

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#sidebarToggle").click(function() {
                $("#sidebar").toggleClass("collapsed");
                $("#contentWrapper").toggleClass("expanded");
            });
        });
    </script>
</body>
</html>