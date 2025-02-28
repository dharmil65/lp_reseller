@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="section-dashboard section-content active admin-dashboard">
            <div class="res_dashboard_1">
                <div class="row">
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="ms-card card-gradient-info website-wrapper ms-widget ms-infographics-widget redesign">
                            <div class="ms-card-body media">
                                <a href="" class="media-body">
                                    <div class="media-content">
                                        <h6>USERS</h6>
                                        <p class="ms-card-change">0</p>
                                        <p class="fs-12"></p>
                                    </div>
                                    <div class="media-img">
                                        <img src ="{{asset('template_elsner/images/admin-user.png')}}" alt = "Users" loading="lazy"/>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="ms-card card-gradient-info amount-receive  ms-widget ms-infographics-widget redesign">
                            <div class="ms-card-body media">
                                <div class="media-body">
                                    <div class="media-content">
                                        <h6>Order</h6>
                                        <p class="ms-card-change">0</p>
                                        <p class="fs-12"></p>
                                    </div>
                                    <div class="media-img">
                                        <img src ="{{asset('template_elsner/images/admin-order.png')}}" alt="order" loading="lazy"/>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-card-body media" style="cursor:pointer">
                                <div class="media-contentbody">
                                    <ul>
                                        <li>
                                            <a href="">
                                                <div class="detail-wrapper">
                                                    <span class="title">New</span>
                                                    <span class="total-number">0</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="">
                                                <div class="detail-wrapper">
                                                    <span class="title">InProgress</span>
                                                    <span class="total-number">0</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="">
                                                <div class="detail-wrapper">
                                                    <span class="title">Delay</span>
                                                    <span class="total-number">0</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="">
                                                <div class="detail-wrapper">
                                                    <span class="title">Delivered</span>
                                                    <span class="total-number">0</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="">
                                                <div class="detail-wrapper">
                                                    <span class="title">Completed</span>
                                                    <span class="total-number">0</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="">
                                                <div class="detail-wrapper">
                                                    <span class="title">Rejected</span>
                                                    <span class="total-number">0</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    @stack('scripts')

    <script>
        $(document).ready(function() {
            
        });
    </script>
@endsection