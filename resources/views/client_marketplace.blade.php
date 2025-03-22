<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Marketplace</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ url('/css/advertiser_custom_new.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="{{ asset('vendors/iconic-fonts/font-awesome/css/all.min.css') }}" rel="stylesheet">

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
            max-width: 200px;
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

        .marketplace-wrapper {
            padding: 0 15px;
            display: flex;
            align-items: baseline;
            gap: 0 10px;
        }  

        .marketplace-wrapper .side-wrapper {
            width: 20%;
            padding-left: 0;
        }

        .marketplace-wrapper .marketplace-details {
            width: 80%;
        }

        .marketplace-header .profile-wrapper .dropdown-menu.show {
            display: block;
        }

        .side-logo {
            display: flex;
            align-items: center;
            gap: 0 11px;
        }

        .side-logo h5 {
            margin-bottom: 0;
            color: #fff;
            font-size: 30px;
            font-weight: 700;
        }

        .marketplace-table table th {
            white-space: normal;
            word-break: break-word;
            line-height: 1.2;
        }

        .marketplace-table table td {
            text-align: center;
        }

        .marketplace-table table.dataTable thead .sorting,
        .marketplace-table table.dataTable thead .sorting_asc {
            background-image: none !important;
        }

        .table-detail td .cart_btn {
            min-width: 110px;
        }

        .table-header th:first-child, .table-detail td:first-child {
            width: auto !important;
            word-break: break-word;
        }

        .cart_wishlist_cta {
            width: 80px !important;
        }

        .website strong {
            color: #f2652d;
        }
    </style>
</head>
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
                <li><a href="{{ route('client_marketplace') }}" class="active">Marketplace</a></li>
                <li><a href="{{ route('client_orders') }}" class="">My Orders</a></li>
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
        <section class="marketplace-wrapper">
            <div class="marketplace-table">
                <table id="marketplaceTable" class="table" width="100%" border="0">
                    <thead>
                        <tr class="table-header">
                            <th>Website URL</th>
                            <th>DA</th>
                            <th>Org. Traffic</th>
                            <th>Total Visits</th>
                            <th>TAT</th>
                            <th>Backlinks</th>
                            <th>Guest Post</th>
                            <th>Link Insertion</th>
                            <th class="cart_wishlist_cta"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function () {
    let token = localStorage.getItem("api_token");
    if (!token) window.location.href = "{{ route('logout') }}";

    $('#marketplaceTable').DataTable({
        serverSide: true,
        ajax: {
            url: "{{ url('/api/fetch-marketplace-data') }}",
            type: "GET",
            dataType: "json",
            headers: {
                "Authorization": "Bearer " + token,
            },
            data: function(d) {
                d.search = $('#marketplace_search').val();
                d.marketplaceType = 0;
                d.page_per_size = 25;
                d.page = 1;
            },
            beforeSend: function () {
                cartStatus = {};
            },
            dataSrc: function (res) {
                if (!res.success) {
                    window.location.href = "{{ route('logout') }}";
                    return [];
                }

                cartStatus = res.cartStatus || {};

                if (res.hasOwnProperty('cartsTotal') && !isNaN(res.cartsTotal) && res.cartsTotal > 0) {
                    $('.notification-number').show();
                    $('#cartcount').text(res.cartsTotal);
                } else {
                    $('.notification-number').hide();
                }

                if (res.hasOwnProperty('walletBalance') && !isNaN(res.walletBalance) && res.walletBalance > 0) {
                    $('#walletBalance').text("$"+res.walletBalance);
                } else {
                    $('#walletBalance').text('$0');
                }

                return res.data || [];
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    let response = xhr.responseJSON;
                    if (response && response.logout) {
                        localStorage.removeItem("api_token");
                        window.location.href = "/logout";
                    }
                }
            }
        },
        pageLength: 25,
        pagingType: "simple",
        info: false,
        lengthChange: false,
        searching: false,
        columns: [
            {
                data: "host_url",
                render: function (data, type, row) {
                    let formattedUrl = data && !data.startsWith('http') ? 'https://' + data : data;
                    let hostUrl = data ? `<a href="${formattedUrl}" target="_blank"><strong>${data}</strong></a>` : '--';

                    let categories = row.category ? row.category.split(',').map(item => item.trim()) : [];
                    let firstCategory = categories.length > 0 ? categories[0] : 'N/A';
                    let extraCategories = categories.slice(1);

                    let categoryHtml = `<span>${firstCategory}</span>`;
                    if (extraCategories.length > 0) {
                        categoryHtml += `
                            <span class="category-tooltip" data-toggle="tooltip" data-html="true" title="${extraCategories.join(', ')}">
                                +${extraCategories.length}
                            </span>
                        `;
                    }

                    return `
                        <div class="website">${hostUrl}</div>
                        <div>Category: ${categoryHtml}</div>
                    `;
                }
            },
            { data: "da", defaultContent: '--' },
            { data: "ahref", defaultContent: '0' },
            { data: "semrush", defaultContent: '0' },
            { data: "tat", defaultContent: '--' },
            { data: "backlink_count", defaultContent: '--' },
            { 
                data: "guest_post_price", 
                defaultContent: '--',
                render: function(data, type, row) {
                    return data ? '$' + data : '--';
                }
            },
            { 
                data: "linkinsertion_price", 
                defaultContent: '--',
                render: function(data, type, row) {
                    return data ? '$' + data : '--';
                }
            },
            {
                data: "wishlist",
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    let isInWishlist = cartStatus[row.website_id] !== undefined && cartStatus[row.website_id] == 1;
                    return `
                        <a href="#" class="btn button btn-primary cart_wishlist_cta wishlist-btn ${isInWishlist ? 'active' : ''}"
                            data-wishlist="${row.website_id}" data-action="add" id="wishlist_${row.website_id}" 
                            data-name="${row.host_url}">
                            <i class="far fa-heart"></i>
                        </a>
                    `;
                }
            },
            {
                data: "cart",
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    let isInCart = cartStatus[row.website_id] !== undefined && cartStatus[row.website_id] == 0;
                    return `
                        <a rel="nofollow" class="btn button btn-primary cart_btn ${isInCart ? 'active' : ''}"
                            id="cart_${row.website_id}" data-cart="${row.website_id}" 
                            data-action="${isInCart ? 'delete' : 'add'}" data-name="${row.host_url}">
                            <img src="{{ asset('assets/images/buy.png') }}" alt="buy" id="img_${row.website_id}">
                            <span>${isInCart ? 'Added' : 'Add'}</span>
                        </a>
                    `;
                }
            }
        ],
        rowCallback: function(row, data, index) {
            $(row).addClass('table-detail');
        }
    });

    $(document).on('click', '.cart_btn', function () {
        var website_id = $(this).attr('data-cart');
        var action = $(this).attr('data-action');
        var host_url = $(this).attr('data-name');
        var clientId = $('#end_client_id').val();

        $.ajax({
            type: "POST",
            url: "/api/cart/store",
            contentType: "application/json",
            headers: {
                "Authorization": "Bearer " + localStorage.getItem("api_token"),
            },
            data: JSON.stringify({
                website_id: website_id,
                action: action,
                marketplaceType: 0,
                competitorsBacklinkAnalysis: true,
                clientId: clientId,
            }),
            dataType: 'json',
            success: function (response) {
                var newAction = action === 'add' ? 'delete' : 'add';
                var newText = newAction === 'add' ? 'Add' : 'Added';

                $('#cart_' + website_id).children('span').text(newText);
                $('#cart_' + website_id).toggleClass('active', newAction === 'delete');
                $('#cart_' + website_id).attr('data-action', newAction);
                toastr.success(response.message);

                if (newAction === 'delete') {
                    $('#wishlist_' + website_id).removeClass('active').attr('data-action', 'add')
                        .find('i').removeClass('fas fa-heart').addClass('far fa-heart');
                    $('#blocksites_' + website_id).addClass('disabled');
                } else {
                    $('#blocksites_' + website_id).removeClass('disabled');
                }                    
                
                $('#cartcount').text(response.cartTotal);
                if (response.cartTotal == 0) {
                    $('#cartcount').addClass('d-none').text('');
                } else {
                    $('#cartcount').show().removeClass('d-none').text(response.cartTotal);
                }
            },
            error: function (xhr) {
                if (xhr.status === 401) {
                    let response = xhr.responseJSON;
                    if (response && response.logout) {
                        localStorage.removeItem("api_token");
                        window.location.href = "/logout";
                    }
                } else {
                    toastr.error("Something went wrong");
                }
            }
        });
    });

    $(document).on('click', '#logout_advertiser', function (e) {
        e.preventDefault();
        localStorage.removeItem("api_token");
        window.location.href = this.href;
    });

    $('#cart_btn_header').on('click', function () {
        var cartCount = $('#cartcount').text().trim();
        var endClientId = $('#end_client_id').val();
        if (!cartCount || parseInt(cartCount) === 0) {
            toastr.info('Your Cart is Empty');
        } else {
            var token = localStorage.getItem("api_token");
            $.ajax({
                type: "GET",
                url: "/api/client-cart-data",
                headers: {
                    "Authorization": "Bearer " + token,
                },
                data: { end_client_id: endClientId },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const walletBalance = encodeURIComponent(response.walletBalance);
                        const cartTotal = encodeURIComponent(response.cartTotal);
                        const userid = encodeURIComponent(response.userid);
                        window.location.href = `{{ route('cart') }}?userid=${userid}&walletBalance=${walletBalance}&cartTotal=${cartTotal}`;
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 401) {
                        window.location.href = "{{ route('logout') }}";
                    }
                }
            });
        }
    });
});
</script>