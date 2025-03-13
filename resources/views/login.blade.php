<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Login | LP')</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('css/custom.css') }}" /> 
    <link rel="stylesheet" href="{{ url('css/responsive.css') }}" defer/>
    <link rel="stylesheet" type="text/css" href="{{ url('css/reseller_custom.css') }}">
    @stack('styles')

</head>

<section class="user-login section-content" style="display:block;">
    <div class="container-fluid">
        <div class="login-form" id="loginForm">
            <div class="form-wrapper">
                <div class="login-sign-wrapper">
                    <div class="login-sign-form">
                        @if(session()->has('error'))
                            <div class="alert alert-danger alert-dismissible" style="margin: 10px;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        <div class="alert alert-success alert-dismissible" style="margin: 10px;display:none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ session()->get('success') }}
                        </div>
                        <div class="logo-section">
                            <img src="{{ asset('assets/latest_assets_new/images/new-logo.svg') }}" loading="lazy" alt="logo"></a>
                        </div>
                        <h2>Log In</h2>
                        <form id="login_user" action="{{ route('login-client.post') }}" method="POST">
                            @csrf
                            <input type="hidden" name="reseller_id" id="reseller_id" value="{{ isset($reseller->id) ? $reseller->id : '' }}">
                            <input type="hidden" name="reseller_name" id="reseller_name" value="{{ isset($reseller->name) ? $reseller->name : '' }}">

                            <div class="form-group form-icon email_val {{ $errors->has('email') ? 'error' : '' }}">
                                <input type="email" name="email" id="email" placeholder="Email Address*" maxlength="100" value="{{ old('email') }}" required>
                                @error('email')
                                    <label class="error backend" for="email">{{ $message }}</label>
                                @enderror
                            </div>

                            <div class="form-group form-icon form-login-password login-password">
                                <input type="password" name="login_password" id="login_password" placeholder="Password*" required>
                                <i class="far fa-eye fa-eye-slash" id="toggleLogInPassword"></i>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn button btn-primary log_in_btn">Log In</button>
                            </div>
                        </form>
                        <div class="form-group">
                            <p class="sign-note">Don’t have an account? <a href="{{ route('register-client-form') }}">Sign up for free</a></p>
                        </div>
                    </div>
                    <div class="client-review-wrapper">
                        <div class="christmas-login-wrapper" style="display:none">
                            <img src="{{ asset('assets/latest_assets_new/images/christmas_Login_Signup.svg') }}" loading="lazy" alt="christmas_Login_Signup">
                        </div>
                        <div class="background-animation">
                            <img src="{{ asset('assets/latest_assets_new/images/review-first.svg') }}" loading="lazy" class="d1" alt="review-first">
                            <img src="{{ asset('assets/latest_assets_new/images/review-second.svg') }}" loading="lazy" class="d2" alt="review-second">
                            <img src="{{ asset('assets/latest_assets_new/images/review-thard.svg') }}" loading="lazy" class="d3" alt="review-thard">
                        </div>
                        <div class="advertiser-publisher-info">
                            <div class="client-advertiser">
                                <div class="advertiser-info">
                                    <img src="{{ asset('assets/latest_assets_new/images/advertiser.svg') }}" loading="lazy" alt="advertiser">
                                    <h5>advertiser</h5>
                                </div>
                                <ul>
                                    <li>Build links By Guest Posting</li>
                                    <li>Access to 50000+ websites</li>
                                    <li>Improve Website Rankings</li>
                                </ul>
                            </div>

                            <div class="client-publisher">
                                <div class="publisher-info">
                                    <img src="{{ asset('assets/latest_assets_new/images/publisher.svg') }}" loading="lazy" alt="publisher">
                                    <h5>publisher</h5>
                                </div>
                                <ul>
                                    <li>List your Website for Free</li>
                                    <li>Earn by selling Guest Posts</li>
                                    <li>Gain good-quality articles</li>
                                </ul>
                            </div>
                        </div>

                        <div class="client-excellent">
                            <h3>Excellent</h3>

                            <div class="excellent-review">
                                <ul>
                                    <li><img src="{{ asset('assets/latest_assets_new/images/rating-star-full.svg') }}" loading="lazy" alt="rating-star-full"></li>
                                    <li><img src="{{ asset('assets/latest_assets_new/images/rating-star-full.svg') }}" loading="lazy" alt="rating-star-full"></li>
                                    <li><img src="{{ asset('assets/latest_assets_new/images/rating-star-full.svg') }}" loading="lazy" alt="rating-star-full"></li>
                                    <li><img src="{{ asset('assets/latest_assets_new/images/rating-star-full.svg') }}" loading="lazy" alt="rating-star-full"></li>
                                    <li><img src="{{ asset('assets/latest_assets_new/images/rating-star-half.svg') }}" loading="lazy" alt="rating-star-half"></li>
                                </ul>
                                <p>4.8 out of 5</p>
                                <img src="{{ asset('assets/latest_assets_new/images/Trustpilot-logo.svg') }}" loading="lazy" alt="Trustpilot-logo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<body>
    <div class="wrapper">
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    
    @stack('scripts')

    <script>
        $(document).ready(function() {

            let successMessage = sessionStorage.getItem('success_message');
            if (successMessage) {
                $('.alert-success').show().text(successMessage).show();
                sessionStorage.removeItem('success_message');
            }

            var redirectTo = "{{ session('redirectTo') }}";
            if (redirectTo && redirectTo == "user-login") {
                window.setTimeout(function() {
                    $('.user-login').trigger('click');
                }, 300);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $("body").on('keyup', '#name', function(){
                $(this).val($(this).val().trimStart());
            });

            $("#login_user").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    login_password: {
                        required: true,
                        minlength: 8
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address"
                    },
                    login_password: {
                        required: "Please enter your password",
                        minlength: "Password must be at least 8 characters long"
                    }
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("error-message");
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            $('#toggleLogInPassword').click(function() {
                var passwordField = $('#login_password');
                var type = passwordField.attr('type');
                console.log(type);

                if (type === 'password') {
                    passwordField.attr('type', 'text');
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    passwordField.attr('type', 'password');
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });

            window.setTimeout(function(){
                $('.alert').hide();
            }, 3000);
        });
    </script>
</body>

</html>