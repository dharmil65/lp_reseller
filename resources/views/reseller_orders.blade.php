@extends('reseller_homepage')

@section('title', 'Orders')

<style>
    .subadmin-website.section-content {
        display: block;
    }
</style>

@section('content')
    <section class="section-dashboard section-content active admin-dashboard">
        <div class="res_dashboard_1">
            <div class="subadmin-website section-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" style="display:none;">
                            <div class="calendar-wrapper d-flex align-items-center">
                                <img src="{{asset('assets/images/calendar.svg')}}" alt="Calendar Icon" class="mr-2">
                                <span>Apply Date Filter </span> <i class="fa fa-caret-down ml-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="ms-panel">
                            <div class="ms-panel-header">
                                <h6>Orders</h6>
                                <div class="calander-filter">
                                </div>    
                            </div>
                            <div class="ms-panel-body">
                                <div class="box-body">
                                    <div class="box-header with-border bg-title border-header" style="margin-bottom: 20PX;">
                                        <div class="row">             
                                        </div>
                                    </div>
                                </div>
                                <div class="tab task_tab_list " id="tab_list">
                                    <div class="tab" id="tab_list" style="border: 0;">
                                        <button class="order_new tablinks active" data-status="1" id="New_id">
                                            New ({{ $statusCounts[1] ?? 0 }})
                                        </button>
                                        <button class="order_pending tablinks" data-status="2" id="Progress_id">
                                            In Progress ({{ $statusCounts[2] ?? 0 }})
                                        </button>
                                        <button class="order_delayed tablinks" data-status="5" id="Late_id">
                                            Delayed ({{ $statusCounts[5] ?? 0 }})
                                        </button>
                                        <button class="order_delivered tablinks" data-status="7" id="Delivered_id">
                                            Delivered ({{ $statusCounts[7] ?? 0 }})
                                        </button>
                                        <button class="order_complete tablinks" data-status="6" id="Complete_id">
                                            Completed ({{ $statusCounts[6] ?? 0 }})
                                        </button>
                                        <button class="order_reject tablinks" data-status="0" id="Rejected_id">
                                            Rejected ({{ $statusCounts[0] ?? 0 }})
                                        </button>
                                        <button class="order_all tablinks" data-status="all" id="All_id">
                                            All Orders ({{ array_sum($statusCounts->toArray()) }})
                                        </button>
                                    </div>
                                </div>
                                <div id="New" class="tabcontent" style="display:block">
                                <div class="box-body" id="new_table_list">
                                    @include('reseller_order_table')
                                    <div>
                                    </div>
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