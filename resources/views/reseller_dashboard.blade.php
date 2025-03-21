@extends('reseller_homepage')

@section('title', 'Dashboard')

@section('content')
<section class="section-dashboard section-content active admin-dashboard">
    <div class="res_dashboard_1">
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="ms-card card-gradient-info website-wrapper ms-widget ms-infographics-widget redesign">
                    <div class="ms-card-body media">
                        <a href="" class="media-body">
                            <div class="media-content">
                                <h6>USERS</h6>
                                <p class="ms-card-change">{{ isset($totalResellerUsers) && $totalResellerUsers > 0 ? $totalResellerUsers : 0 }}</p>
                                <p class="fs-12"></p>
                            </div>
                            <div class="media-img">
                                <img src ="{{asset('assets/images/admin-user.png')}}" alt = "Users" loading="lazy"/>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="ms-card card-gradient-info amount-receive  ms-widget ms-infographics-widget redesign">
                    <div class="ms-card-body media">
                        <a href="" class="media-body">
                            <div class="media-content">
                                <h6>Order</h6>
                                <p class="ms-card-change">{{ isset($totalResellerOrders) && $totalResellerOrders > 0 ? $totalResellerOrders : 0 }}</p>
                                <p class="fs-12"></p>
                            </div>
                            <div class="media-img">
                                <img src ="{{asset('assets/images/admin-order.png')}}" alt="order" loading="lazy"/>
                            </div>
                        </a>
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
@endsection

@section('resellerId')
    {{ $resellerId }}
@endsection

@section('resellerName')
    {{ $resellerName }}
@endsection