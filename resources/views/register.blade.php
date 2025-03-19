<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Register')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('css/custom.css') }}" /> 
    <link rel="stylesheet" href="{{ url('css/responsive.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('css/reseller_custom.css') }}">
    @stack('styles')
</head>

<section class="user-page section-content" style="display:block;">
    <div class="container-fluid">
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible" style="margin: 10px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible" style="margin: 10px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session()->get('error') }}
            </div>
        @endif
        <div class="alert alert-danger errorMsgClass" style="display:none;">
            <span class="columnError"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="login-sign-detail" id="registerForm">
            <div class="form-wrapper">
                <div class="login-sign-wrapper">
                    <div class="login-sign-form">
                        <div class="logo-section">
                            <img src="{{ asset('assets/latest_assets_new/images/new-logo.svg') }}" loading="lazy" alt="logo"></a>
                        </div>
                         <h2>Sign up for free</h2>
                        <form id="register_user" action="{{ url('/api/reg-client') }}" method="POST">
                            @csrf
                            <input type="hidden" name="reseller_id" id="reseller_id" value="{{ isset($reseller->id) ? $reseller->id : '' }}">
                            <input type="hidden" name="reseller_name" id="reseller_name" value="{{ isset($reseller->name) ? $reseller->name : '' }}">

                            @if (isset($errors))
                                <div class="form-group form-icon name_class {{ $errors->has('name') ? 'error' : '' }}">
                                    <input type="text" name="name" placeholder="Full name*" id="name" required>
                                    @error('name')
                                        <label class="error backend" for="name">{{ $message }}</label>
                                    @enderror
                                </div>

                                <div class="form-group form-icon email_class {{ $errors->has('email') ? 'error' : '' }}">
                                    <input type="email" name="email" id="email" placeholder="Email Address*" maxlength="100" value="{{ old('email') }}" required>
                                    @error('email')
                                        <label class="error backend" for="email">{{ $message }}</label>
                                    @enderror
                                </div>
                            @endif

                            <div class="form-group form-icon form-password login-password">
                                <input type="password" name="password" id="password" placeholder="Password*" required>
                                <i class="far fa-eye fa-eye-slash" id="togglePassword"></i>
                                <div class="password-error" id="passID" style="display: none;">
                                    <div class="password-title">
                                        <p class="pwdHeading">Weak Password</p>
                                        <div class="password-status">
                                            <span class="weak-password"></span>
                                            <span class="average-password"></span>
                                            <span class="good-password"></span>
                                            <span class="strong-password"></span>
                                        </div>
                                    </div>
                                    <ul>
                                        <h6>It’s better to have:</h6> 
                                        <li><span class="eight-character"><i class="far fa-times-circle close-error"></i></span>At least 8
                                        characters</li>
                                        <li><span class="one-special-char"><i class="far fa-times-circle close-error"></i></span>At least one
                                        special character</li>
                                        <li><span class="one-number"><i class="far fa-times-circle close-error"></i></span>At least one
                                        number</li>
                                        <li><span class="low-upper-case"><i class="far fa-times-circle close-error"></i></span>Upper &amp; lower
                                        characters</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class=" button btn btn-primary">Sign Up Now</button>
                            </div>
                        </form>
                        <div class="for-new-user form-group">
                            <div class="sign-note">
                                <p>Already have an account? <a href="{{ route('login-client') }}">Log in</a></p>
                            </div>
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

    <script type="text/javascript">
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function(e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
        window.setTimeout(function() {
            $('.alert').hide();
        }, 3000);
    </script>

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('#resellerUsersTable').DataTable();

            let dataTable;

            jQuery.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) ||  /^[a-zA-Z\s]*$/i.test(value);
            }, "Name format is Invalid");

            $.validator.addMethod("pwcheck", function(value) {
                return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/.test(value);
            });

            $.validator.addMethod("chkemail", function (value, element) {
                var urlRegEx = new RegExp(re=
                (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,20})+$/));
                
                $('#url').addClass('error');
                return this.optional(element) || urlRegEx.test(value);
            }, "Please enter a valid Email.");

            $('#register_user').validate({
                errorClass: 'error',
                validClass: 'valid',
                highlight: function(element, errorClass, validClass) {
                    if ($(element).attr("type") === "radio") {
                        $('#role-select').addClass(errorClass).removeClass(validClass);
                    } else {
                        $(element).parent().addClass(errorClass).removeClass(validClass);
                    }
                },
                unhighlight: function(element, errorClass, validClass) {
                    if ($(element).attr("type") === "radio") {
                        $('#role-select').removeClass(errorClass).addClass(validClass);
                    } else {
                        $(element).parent().removeClass(errorClass).addClass(validClass);
                    }
                },
                ignore:'',
                rules: { 
                    name: {
                        required: {
                            depends:function(){
                                return true;
                            }
                        },
                        lettersonly: true,
                    },
                    email: {
                        required: {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }
                        },
                        chkemail: true,
                    },
                    password: {
                        required: true,
                        pwcheck: true,
                        minlength: 8,
                    }
                },
                messages: {
                    name:{
                        required: 'Name is required.',
                        lettersonly: 'The name field must contain only alphabets.',
                    },
                    email:{
                        required: 'Email is required.',
                        email: 'Please enter a valid email address.',
                        chkemail: 'Please enter a valid email address.',
                    },
                    password:{
                        required: 'Password is required.',
                        minlength: 'Password must be 8 character long.',
                        pwcheck: 'It must contain atleast 1 upper&lower,1 special character & 1 digit',
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                },
            });

            $("#password").focusin(function() {
                $('.password-error').show();
            }).focusout(function() {
                $(".password-error").hide();
            });
            
            $('#password').on('keyup input', function() {
                var password = $('#password').val();
                var strength = 0;

                if (password.length >= 8) {
                    strength += 1;
                    $('.eight-character i').removeClass('fa-times-circle').addClass('fa-check-circle').addClass('text-success');
                    $('.eight-character').removeClass('invalid').addClass('valid');
                } else {
                    $('#password').addClass('invalid');
                    $('.eight-character i').removeClass('fa-check-circle').removeClass('text-success').addClass('fa-times-circle');
                    $('.password-status span').css('background-color', '');
                }

                if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password)) {
                    strength += 1;
                    $('.one-special-char i').removeClass('fa-times-circle').addClass('fa-check-circle').addClass('text-success');
                    $('.one-special-char').removeClass('invalid').addClass('valid');
                } else {
                    $('#password').addClass('invalid');
                    $('.one-special-char i').removeClass('fa-check-circle').removeClass('text-success').addClass('fa-times-circle');
                    $('.password-status span').css('background-color', '');
                }

                if (/\d/.test(password)) {
                    strength += 1;
                    $('.one-number i').removeClass('fa-times-circle').addClass('fa-check-circle').addClass('text-success');
                    $('.one-number').removeClass('invalid').addClass('valid');
                } else {
                    $('#password').addClass('invalid');
                    $('.one-number i').removeClass('fa-check-circle').removeClass('text-success').addClass('fa-times-circle');
                    $('.password-status span').css('background-color', '');
                }

                if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
                    strength += 1;
                    $('.low-upper-case i').removeClass('fa-times-circle').addClass('fa-check-circle').addClass('text-success');
                    $('.low-upper-case').removeClass('invalid').addClass('valid');
                } else {
                    $('#password').addClass('invalid');
                    $('.low-upper-case i').removeClass('fa-check-circle').removeClass('text-success').addClass('fa-times-circle');
                    $('.password-status span').css('background-color', '');
                }

                var strengthLabels = ['Weak Password', 'Average Password', 'Good Password', 'Strong Password'];
                var strengthColors = ['#FBAD4E', '#FFDB35', '#00B98B', '#1D7963'];

                $('.pwdHeading').text(strengthLabels[strength - 1]);
                $('.password-status span').css('background-color', '');

                var colorIndex = Math.min(strength - 1, strengthColors.length - 1);
                for (var i = 0; i <= colorIndex; i++) {
                    $('.password-status span:nth-child(' + (i + 1) + ')').css('background-color', strengthColors[colorIndex]);
                }

                if (strength === 4) {
                    window.setTimeout(function() {
                        $('#password').removeClass('error');
                    }, 500);
                }
            });

            $('#register_user').on('submit', function (e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            sessionStorage.setItem('success_message', response.message);
                            window.location.href = response.redirect_url;
                        } else {
                            sessionStorage.setItem('error_message', response.message);
                            if (response.redirect_url) {
                                $('.errorMsgClass').css('display', 'block');
                                $('.columnError').html(response.message);
                                window.setTimeout(function(){
                                    $('.errorMsgClass').fadeOut('slow');
                                    window.location.href = response.redirect_url;
                                }, 2000);
                            }
                        }
                    }
                });
            });

            $("body").on('keyup', '#name', function(){
                $(this).val($(this).val().trimStart());
            });
        });
    </script>
</body>

</html>