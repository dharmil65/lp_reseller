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
		<input type="hidden" name="main_user_id" id="main_user_id" value="">
		<input type="hidden" name="main_user_name" id="main_user_name" value="">
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
									<th class="comments">Chat</th>
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

		<div class="modal chat_popup" id="new_chat_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="orderlabel_no">Order ID: #4045</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true"><i class="fas fa-times"></i></span>
						</button>
					</div>
					<div class="modal-body">
						<div class="send chat-content" id="message-box">
							<div class="messagesss temschat">
								<p> <b><span><img src="{{asset('assets/images/buyer-red.png')}}"></span>It is prohibited :</b><br>
									1. To establish any personal contact outside Link Publishers and share contact details. <br>
									2. To discuss about Link Publishers’ prices.<br>
									All messages exchanged here are monitored. Link Publishers holds the authority to suspend or ban your account if any unauthorized activity is noticed or anyone violates our guidelines.
								</p>
							</div>
							<div id="chat_body">
							</div>
							<div class="messagesss temschat complete_order_chat_msg" style="display:none;"><p> Chat is disabled as this order is completed. </p></div>
							<div class="messagesss temschat reject_order_chat_msg" style="display:none;"><p> Chat is disabled as this order is rejected. </p></div>
						</div>
						<div id="bottom" tabindex='1'>
						</div>
					</div>
					<div class="modal-footer">
						<div class="form-group">
							<input type="hidden" name="to_id" id="to_id" value="">
							<input type="hidden" name="order_id" id="order_id" class="order_id" value="">
							<textarea autocomplete="off" type="text" id="message" name="message" style="background: #F6F6F6;" placeholder="Type your message..."></textarea>
							<button id="send-message" class="btn chat-btn"><img src="{{asset('assets/images/chat-send.png')}}" /></button>
							<span id="msg-error-chat" class="error" style="display:none">Please enter message</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function () {
                var token = localStorage.getItem("api_token");
				var msgBox = $('#chat_body');
    			var wsUri = "wss://socket.elsnerdev.com/";

				getUnreadNewMsgCount();
				setInterval(function () {
					getUnreadNewMsgCount();
				}, 10000);

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
							$('#main_user_id').val(response.fetchUserID);
							$('#main_user_name').val(response.fetchUserName);
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
										<td>${order.price && order.price > 0 ? '$' + order.price.toLocaleString() : '--'}</td>
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
											<a href="javascript:void(0);" class="chat-icon" 
												data-userid="${order.fetchUserID}" 
												data-orderlabel="${order.order_lable}" 
												data-publisher="${order.publisher_id}" 
												data-oaid="${order.order_attr_id}" 
												data-status="1">
												<img src="{{ asset('assets/images/comment-icon.png') }}" alt="chat">
												${order.new_msg > 0 ? '<span class="has-comments"></span>' : ''}
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

							$("#newCount").text(`(${response.statusCounts[1] ?? 0})`);
							$("#inProgressCount").text(`(${response.statusCounts[2] ?? 0})`);
							$("#approveCount").text(`(${response.statusCounts[7] ?? 0})`);
							$("#delayCount").text(`(${response.statusCounts[5] ?? 0})`);
							$("#completeCount").text(`(${response.statusCounts[6] ?? 0})`);
							$("#rejectCount").text(`(${response.statusCounts[0] ?? 0})`);
							$("#allCount").text(`(${response.totalOrders ?? 0})`);

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

				$(document).on('click', '.checked', function () {
            
					var order_attribute_id = $(this).parent().attr('data-oaid');
					$('#multiAction'+order_attribute_id).css('display', 'none');
					$('#srch'+order_attribute_id).css('display', 'block');
					$(this).css('pointer-events', 'none');
					var parameters={
						'order_attribute_id':order_attribute_id
					}
					$('#loader-' + order_attribute_id).show();
					$.ajax({
						type: 'POST',
						url: "/api/client-approval-to-complete",
						data: {
							'param': parameters,
							"_token": "{{ csrf_token() }}"
						},
						headers: {
                            "Authorization": "Bearer " + token,
                        },
						dataType: 'json',
						success: function(data) {
							$('#multiAction'+order_attribute_id).css('display', 'block');
							window.setTimeout(function(){
								$('#completed-tab').trigger('click');
								fetchOrders(6);
							}, 500);
						}
					});
				});

				$(document).on('click', '.chat-icon', function() {
					$('#msg-error-chat').css('display', 'none');
					
					var orderlabel = $(this).attr('data-orderlabel');
					var order_attribute_id = $(this).attr('data-oaid');
					var publisher_id = $(this).attr('data-publisher');
					var status = $(this).attr('data-status');
					var user_id = $(this).attr('data-userid');
					
					getUnreadNewMsgCount();
					
					if(status == "6") {
						status = "completed";
					} else if(status == "0") {
						status = "rejected";
					}

					if(status == "completed"){
						$('#message').css('display', 'none');
						$('.chat-btn').css('display', 'none');
						$('.complete_order_chat_msg').css('display', 'block');
						$('.reject_order_chat_msg').css('display', 'none');
					} else if(status == "rejected"){
						$('#message').css('display', 'none');
						$('.chat-btn').css('display', 'none');
						$('.reject_order_chat_msg').css('display', 'block');
						$('.complete_order_chat_msg').css('display', 'none');
					} else {
						$('#message').css('display', 'block');
						$('.chat-btn').css('display', 'block');
						$('.complete_order_chat_msg').css('display', 'none');
						$('.reject_order_chat_msg').css('display', 'none');
					}

					$('#to_id').val(publisher_id);
					$('#order_id').val(order_attribute_id);

					$.ajax({
						type: 'POST',
						url: "/api/get-client-chat-message",
						data: {
							'order_attribute_id': order_attribute_id,
							'order_status': status,
							'user_id': user_id,
							"_token": "{{ csrf_token() }}"
						},
						headers: {
                            "Authorization": "Bearer " + token,
                        },
						dataType: 'json',
						success: function(data) {
							$('#orderlabel_no').text("Order ID: " + orderlabel);
							$('#chat_body').html(data.html);
							$('.chat_popup .modal-body').animate({scrollTop: $('.chat_popup .modal-body').prop("scrollHeight")}, 0);
							$('#new_chat_popup').modal({
								backdrop: true,
								keyboard: true
							});
							$('.chat-icon[data-oaid="'+order_attribute_id +'"]').find('span').removeClass("has-comments");
							$('.chat_popup .modal-body').animate({scrollTop: $('.chat_popup .modal-body').prop("scrollHeight")}, 0);
						}
					});
				});

				$('#new_chat_popup').on('hidden.bs.modal', function() {
					$('#message').val('');
					$('#msg-error-chat').css('display', 'none');
				});

				function getUnreadNewMsgCount() {
					$.ajax({
						type: "GET",
						url: "/api/client-unread-msg-counts",
                        headers: {
                            "Authorization": "Bearer " + token,
                        },
						dataType: 'json',
						success: function(data) {
							if (data.count > 0) {
								if (data.count > 1) {
									document.title = data.count + " New Messages";
								} else {
									document.title = data.count + " New Message";
								}
							}else{
								document.title = 'Orders';
							}
						}
					});
				}

				$('#send-message').on('click', function() {
					sendMessage();
				});

				$("#message").on("keydown", function(event) {
					if (event.which == 13) {
						sendMessage();
					}
				});

				var debounceTimeout;
				function sendMessage() {
					
					disableSending();
					$('#msg-error-chat').css('display', 'none');
					var message = $('#message').val();

					if (message.trim().length > 0) {
						$('#send-message').prop('disabled', true);
						$('#message').css('pointer-events', 'none');
						
						message = message.replace(/^\s+|\s+$/g, '');
						var to_id = $('#to_id').val();
						var order_id = $('#order_id').val();
						var from_id = $('#main_user_id').val();
						var type = "type";

						$.ajax({
							type: 'POST',
							url: "/api/send-message",
							headers: {
								"Authorization": "Bearer " + token,
							},
							data: {
								'message': message,
								'to_id': to_id,
								'from_id': from_id,
								'order_id': order_id,
								'type': type,
								"_token": "{{ csrf_token() }}"
							},
							dataType: 'json',
							success: function(data) {
								clearTimeout(debounceTimeout);
								$('#message').val('');

								debounceTimeout = setTimeout(function() {
									window.setTimeout(function(){
										$('#message').blur();
									}, 100);
									window.setTimeout(function(){
										$('.chat_popup .modal-body').animate({scrollTop: $('.chat_popup .modal-body').prop("scrollHeight")}, 0);
										$('#send-message').prop('disabled', false);
										$('#message').css('pointer-events', 'auto');
									}, 800);
									window.setTimeout(function() {
										$('#message').focus();
									}, 1000);
								}, 300);
								
								enableSending();

								var currentTime = new Date().toLocaleString([], {hour: '2-digit', minute: '2-digit'});
								var currentDate = new Date().toLocaleDateString('en-GB');

								var msgHtml = '';
								var checkDate = $('#chat_body h5').html();
								if (checkDate == undefined) {
									msgHtml += '<h5 style="margin-bottom:15px">' + currentDate + '</h5>';
								}
								msgHtml += '<div class="right-details"><p>' + message + '</p><br><h6>Advertiser, ' + currentTime + '</h6></div>';
								msgBox.append(msgHtml);
								msgBox[0].scrollTop = msgBox[0].scrollHeight;
							}
						});

						var msg = {
							message: message,
							name: $('#main_user_name').val() + ", " + new Date().toLocaleString([], {hour: '2-digit', minute: '2-digit'}),
							color: '<?php echo @$colors[$color_pick]; ?>',
							type: 'usermsg',
							from_id: $('#main_user_id').val(),
							order_id:order_id,
							to_id:to_id,
							user_role: "Advertiser",
							user_name: $('#main_user_name').val(),
							time:new Date().toLocaleString([], {hour: '2-digit', minute: '2-digit'}),
							content_msg_or_not: null
						};

						if (mySocket.readyState === WebSocket.OPEN) {
							mySocket.send(JSON.stringify(msg));
						}

					} else {
						$('#message').val('');
					}
				}

				function disableSending() {
					$('#send-message').prop('disabled', true);
					$("#message").prop('disabled', true);
					clearTimeout(debounceTimeout);
					debounceTimeout = setTimeout(enableSending, 500);
				}

				function enableSending() {
					$('#send-message').prop('disabled', false);
					$("#message").prop('disabled', false);
				}

				var mySocket;
				const socketMessageListener = (ev) => {
					getUnreadNewMsgCount();
					var id = $('#main_user_id').val();
					var order_id = $('#order_id').val();
					var response = JSON.parse(ev.data);

					if (containsUrl(response.message)) {
						response.message = makeUrlsClickable(response.message);
					} else {
						response.message = response.message;
					}

					console.log("response order_id: ", response.order_id);
					console.log("order_id: ", order_id);
					console.log("response type: ", response.type);
					console.log("response from_id: ", response.from_id);
					console.log("response to_id: ", response.to_id);
					console.log("response user_role: ", response.user_role);
					console.log("response message: ", response.message);

					if (response.order_id == order_id) {
						var currentDate = new Date().toLocaleDateString('en-GB');
						var checkDate = $('#chat_body h5').html();

						if (response.type == 'usermsg' && response.from_id == id && response.user_role == 'Advertiser') {
							if(checkDate == undefined){
								msgBox.append('<h5 style="margin-bottom:15px">' + currentDate + '</h5>');
							}
							msgBox.append('<div class="right-details"><p>' + response.message + '</p><br><h6>' + response.user_role + ', ' + response.time + '</h6></div>');
						} else if (response.type == 'status' && response.to_id == id) {
							if (checkDate == undefined) {
								msgBox.append('<h5 style="margin-bottom:15px">' + currentDate + '</h5>');
							}
							msgBox.append('<div class="left-details"><p>' + response.message + '</p><br><h6>' + response.name + '</h6></div>');
						}
						msgBox[0].scrollTop = msgBox[0].scrollHeight;
					}

					$('.chat_popup .modal-body').animate({scrollTop: $('.chat_popup .modal-body').prop("scrollHeight")}, 0);
				};

				function containsUrl(text) {
					var urlRegex = /(?:https?:\/\/)?(?:www\.)?[^\s]+\.[^\s]{2,}(?:\.[^\s]{2,})?(?:\/[^\s]*)?\b/gi;
					return urlRegex.test(text);
				}

				function makeUrlsClickable(text) {
					var urlRegex = /(?:https?:\/\/)?(?:www\.)?[^\s]+\.[^\s]{2,}(?:\.[^\s]{2,})?(?:\/[^\s]*)?\b/gi;
					var newText = text.replace(urlRegex, function(url) {
						if (!/^https?:\/\//i.test(url)) {
							newUrl = 'https://' + url;
						} else {
							newUrl = url;
						}
						return '<a href="' + newUrl + '" target="_blank">' + url + '</a>';
					});
					return newText;
				}

				const socketOpenListener = (ev) => {
					if (mySocket.readyState === WebSocket.OPEN) {
						let msg = { message: "Client connected.", type: 'system' };
						mySocket.send(JSON.stringify(msg));
					}
				};

				const socketCloseListener = (event) => {
					mySocket = new WebSocket(wsUri);
					mySocket.addEventListener('message', socketMessageListener);
					mySocket.addEventListener('close', socketCloseListener);
				};

				try {
					mySocket = new WebSocket(wsUri);
					mySocket.addEventListener('message', socketMessageListener);
					mySocket.addEventListener('close', socketCloseListener);
				} catch (e) {
					console.log("WebSocket error:", e);
				}
			});
		</script>
	</body>
</html>