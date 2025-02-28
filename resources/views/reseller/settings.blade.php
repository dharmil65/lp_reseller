@extends('layouts.app')

@section('title', 'Settings')

<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .settings-section.section-content {
        display: block;
    }
    .dataTables_wrapper {
        overflow: auto !important;
        overflow-x: hidden !important; 
        max-height: 70vh;
    }
    .table thead {
        background: #fff;
        top: 0;
    }
</style>

@section('content')
    <div class="container">
        <section class="section-dashboard section-content active admin-dashboard">
            <div class="res_dashboard_1">
                <div class="row">
                    <div class="settings-section section-content">
                        <div class="alert alert-danger errorMsgClass" style="display:none;">
                            <span class="columnError"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="alert alert-success successMsgClass" style="display:none;">
                            <span class="columnSuccess"></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="marketplace-filter">
                            <input type="text" id="userInput" class="form-control" placeholder="Enter your API key">
                            <div class="filter-btn">
                                <button id="submitBtn" class="button btn btn-primary">Submit</button>
                                <button id="clearBtn" class="button btn btn-primary" style="display:none;">Clear</button>
                            </div>
                        </div>
                        <p id="errorMessage" class="text-danger mt-2" style="display: none;"></p>
                        <div id="errorMessage" class="text-danger" style="display: none;"></div>
                        <div class="commission-detail">
                            <div class="commission-title">
                                <h6>Enter Your Commission (%)</h6>
                            </div>
                            <div class="commission-data">
                                <p id="commission-value">0</p>
                                <form id="commission-form" style="display: none;">
                                    @csrf
                                    <input type="number" id="commission-input" value="0" min="0" max="100" required>
                                    <button type="submit" id="commission-submit">Submit</button>
                                </form>
                            </div>
                        </div>

                        <table id="marketplaceTable" class="display table table-bordered mt-4" style="width:100%; display:none;">
                            <thead>
                                <tr>
                                    <th>Host URL</th>
                                    <th>DA</th>
                                    <th>Category</th>
                                    <th>Sample Post</th>
                                    <th>Ahref</th>
                                    <th>Semrush</th>
                                    <th>TAT</th>
                                    <th>Backlink Count</th>
                                    <th>Backlink Type</th>
                                    <th>Guest Post Price</th>
                                    <th>Link Insertion Price</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#marketplaceTable input, #marketplaceTable textarea').on('copy paste cut', function (e) {
                e.preventDefault();
            });

            $('#marketplaceTable').css({
                'user-select': 'none',
                '-moz-user-select': 'none',
                '-webkit-user-select': 'none',
                '-ms-user-select': 'none'
            });

            var $commissionValue = $('#commission-value');
            var $commissionForm = $('#commission-form');
            var $commissionInput = $('#commission-input');

            $commissionValue.on('dblclick', function() {
                $commissionValue.hide();
                $commissionForm.show();
                $commissionInput.focus();
            });

            var identityToken = "{{ Auth::user()->identity_token }}";

            $commissionForm.on('submit', function(event) {
                event.preventDefault();
                var newValue = $commissionInput.val();

                $.ajax({
                    url: "/api/update-commission",
                    method: "POST",
                    headers: {
                        'Authorization': 'Bearer ' + identityToken,
                        'Accept': 'application/json'
                    },
                    data: {
                        commission: newValue,
                        user_name: '{{ Auth::user()->name }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.successMsgClass').css('display', 'block');
                            $('.columnSuccess').html(response.message);

                            setTimeout(function() {
                                $('.successMsgClass').fadeOut('slow');
                                window.location.reload();
                            }, 2000);
                        } else {
                            $('.errorMsgClass').css('display', 'block');
                            $('.columnError').html(response.message);

                            setTimeout(function() {
                                $('.errorMsgClass').fadeOut('slow');
                            }, 2000);
                        }
                    }
                });
            });

            $(document).on('click', function(event) {
                if (!$(event.target).closest('#commission-form').length && !$(event.target).is('#commission-value')) {
                    $commissionForm.hide();
                    $commissionValue.show();
                }
            });

            $('#submitBtn').click(function() {
                const userInput = $('#userInput').val().trim();
                const errorMessage = $('#errorMessage');
                errorMessage.hide().text('');

                if (userInput === '') {
                    errorMessage.show().text('Input cannot be empty or just spaces.');
                    $('#userInput').addClass('border-danger');
                    return;
                }

                $('#clearBtn').show();
                $('#userInput').removeClass('border-danger');

                if ($.fn.dataTable.isDataTable('#marketplaceTable')) {
                    $('#marketplaceTable').DataTable().destroy();
                    $('#marketplaceTable tbody').empty();
                }

                $.ajax({
                    url: "{{ route('submitAPIKey') }}",
                    type: 'POST',
                    data: {
                        input: userInput,
                        user: '{{ Auth::user()->name }}'
                    },
                    success: function(response) {
                        if (!response.success) {
                            errorMessage.show().text(response.message);
                            $('#userInput').css('border-color', 'red');
                            return;
                        }

                        $('#marketplaceTable').show();
                        $('#userInput').css('border-color', '');
                        window.setTimeout(function(){
                            $('#marketplaceTable_info').hide();
                        }, 300);

                        $('#marketplaceTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "{{ route('submitAPIKey') }}",
                                type: 'POST',
                                data: function(d) {
                                    d.input = userInput;
                                    d.user = '{{ Auth::user()->name }}';
                                }
                            },
                            columns: [
                                { data: 'host_url', name: 'host_url', defaultContent: '--' },
                                { data: 'da', name: 'da', defaultContent: '--' },
                                { data: 'category', name: 'category', defaultContent: '--' },
                                { data: 'sample_post', name: 'sample_post', defaultContent: '--' },
                                { data: 'ahref', name: 'ahref', defaultContent: '0' },
                                { data: 'semrush', name: 'semrush', defaultContent: '0' },
                                { data: 'tat', name: 'tat', defaultContent: '--' },
                                { data: 'backlink_count', name: 'backlink_count', defaultContent: '--' },
                                { data: 'backlink_type', name: 'backlink_type', defaultContent: '--' },
                                { data: 'guest_post_price', name: 'guest_post_price', defaultContent: '0' },
                                { data: 'linkinsertion_price', name: 'linkinsertion_price', defaultContent: '0' }
                            ],
                            pageLength: 25,
                            pagingType: 'simple',
                        });
                    },
                    error: function(xhr) {
                        errorMessage.show().text('An error occurred while validating the API key.');
                    }
                });
            });

            $(document).on('click', '#clearBtn', function(){
                window.location.href = "{{ route('res-settings-view') }}";
            });

            $(document).on('click', '#marketplaceTable_paginate', function(){
                $("html, body").animate({ scrollTop: 0 }, "slow");
            });
        });
    </script>
@endsection