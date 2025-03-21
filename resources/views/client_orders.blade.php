<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>Orders</title>
	    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
	    <link rel="stylesheet" type="text/css" href="{{ url('css/advertiser_custom_new.css') }}?v={{ config('css_versions.advertiser_custom_new_version') }}">
	    <link rel="stylesheet" type="text/css" href="{{ url('css/reseller_custom.css') }}?v={{ config('css_versions.reseller_custom_css_version') }}">
	    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
	    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	</head>
	<body>
		<div class="main-wrapper">
		<header class="site-header marketplace-header">
			<div class="side-logo">
				<a href="#"><img src="{{asset('assets/images/side-logo.png')}}" alt="side-logo" loading="lazy"></a>
			</div>
			<nav class="main-navigation">
				<ul>
					<li><a href="#" class="">Dashboard</a></li>
					<li><a href="{{ route('client_marketplace') }}" class="">Marketplace</a></li>
					<li><a href="{{ route('client_orders') }}" class="active">My Orders</a></li>
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
		  <section class="order-main">
		        <div class="container">
		         
		        <div class="order-inner order-custom-tabs">
		        <div class="order-heading">
		            <h2>My Orders</h2> 
		        </div>
		        <div class="custom-bs-tabs">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" data-status="1" id="new-tab" href="#">New <span id="newCount">()</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-status="2" id="progress-tab" href="#">In Progress <span id="inProgressCount">()</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-status="7" id="approval-tab" href="#">Your Approval <span id="approveCount">()</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-status="5" id="delayed-tab" href="#">Delayed <span id="delayCount">()</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-status="6" id="completed-tab" href="#">Completed <span id="completeCount">()</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-status="0" id="rejected-tab" href="#">Rejected <span id="rejectCount">()</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-status="all" id="all-tab" href="#">All <span id="allCount">({{ $totalOrders ?? 0 }})</span></a>
						</li>
					</ul>

		            <div class="tab-content order-data" id="myTabContent">
		                <div class="search-panel d-flex align-items-center justify-content-between flex-wrap dropdown">
		                    <p class="noteClass" style="display:none;"></p>
		                    <div class="search-content1" style="display: none">
		                        <div class="form-group" style="display:block">
		                        </div>
		                        <div class="form-group">
		                            <select id="projectDropDown" data-select2-id="select2-data-projectDropDown" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
		                                <option value="" disabled="" selected="" data-select2-id="select2-data-2-went">Please select project</option>
		                                                                                                            <option value="3442">Petsnurturing</option>
		                                                                            <option value="3540">Test</option>
		                                                                            <option value="3693">Lowprice</option>
		                                                                            <option value="4174">Myappgurus</option>
		                                                                            <option value="4286">Mj</option>
		                                                                            <option value="4291">Loop</option>
		                                                                            <option value="4303">New</option>
		                                                                            <option value="4335">Promostore</option>
		                                                                            <option value="4336">Promocenter</option>
		                                                                                                                                </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-1-3lpq" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-projectDropDown-container" aria-controls="select2-projectDropDown-container"><span class="select2-selection__rendered" id="select2-projectDropDown-container" role="textbox" aria-readonly="true" title="Please select project">Please select project</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
		                        </div>
		                        <div class="select-tooltip">
		                            <a href="javascript:void(0)"><i class="fas fa-times projectDropDownTooltip"></i></a>
		                            <p>From here, you can choose a project and move the orders you need</p>
		                            <button class="btn button btn-primary projectDropDownTooltip">Done</button>
		                        </div>
		                    </div>
		                    <div class="search-content" style="display: none;"></div>
		                    
		                </div>
						<table class="table thead-primary w-100 order-table dataTable no-footer" id="advertiser_order" aria-describedby="advertiser_order_info">
							<thead>
								<tr>
									<th><input type="checkbox" name="projectshift[]" class="selectAll"></th>
									<th>Order Date</th>
									<th>Order ID</th>
									<th>Website</th>
									<th>Price</th>
									<th>Language</th>
									<th>Type</th>
									<th class="approval-only">Live Link</th>
									<th class="approval-only">Action</th>
									<th>Chat</th>
								</tr>
							</thead>
							<tbody></tbody>
		                </table>
		            </div>
		        </div>
		        
		        </div>
		        </div>
		    </section>
		</div>
		<script>
			$(document).ready(function () {
                var token = localStorage.getItem("api_token");

				fetchOrders(1);

				function fetchOrders(status) {
					$.ajax({
						url: "/api/fetch-client-orders",
						type: "GET",
						data: { status: status },
                        headers: {
                            "Authorization": "Bearer " + token,
                        },
						success: function (response) {
							let rows = '';

							if (response.orders.length > 0) {
								$.each(response.orders, function (index, order) {
									let formattedUrl = order.host_url && !order.host_url.startsWith('http')
										? 'https://' + order.host_url
										: order.host_url;

									rows += `<tr>
										<td><input type="checkbox" name="projectshift[]" class="selectAll"></td>
										<td>${new Date(order.created_at).toLocaleDateString()}</td>
										<td>${order.reseller_order_lable ?? '-'}</td>
										<td>${order.host_url !== '-' ? `<a href="${formattedUrl}" target="_blank">${order.host_url}</a>` : '-'}</td>
										<td>${order.total && order.total > 0 ? '$' + order.total.toLocaleString() : '--'}</td>
										<td>${order.prefered_language ?? 'English'}</td>
										<td>${order.content_type ?? '-'}</td>`;

									if (status == 7) {
										rows += `
											<td><a href="${order.live_link}" target="_blank">${order.live_link}</a></td>
											<td>${order.action_column}</td>
										`;
									}

									rows += `
										<td>
											<a href="javascript:void(0);" class="chat-icon" data-oaid="${order.order_attr_id}">
												<img src="{{ asset('assets/images/comment-icon.png') }}" alt="chat">
											</a>
										</td>
									</tr>`;
								});
							} else {
								rows = `<tr>
									<td colspan="10" class="text-center">
										<div class="no-data">
											<img src="{{ asset('assets/images/no-data.png') }}" alt="no-data" loading="lazy">
											<h2>No data found!</h2>
										</div>
									</td>
								</tr>`;
							}

							$('#advertiser_order tbody').html(rows);

							if (status == 7) {
								$('.approval-only').show();
							} else {
								$('.approval-only').hide();
							}
						}
					});
				}

				$(".nav-link").click(function (e) {
					e.preventDefault();
					$(".nav-link").removeClass("active");
					$(this).addClass("active");
					let status = $(this).data("status");
					fetchOrders(status);
				});
			});
		</script>
	</body>
</html>