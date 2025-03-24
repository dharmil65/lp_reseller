<link rel="stylesheet" type="text/css" href="{{ url('css/advertiser_custom_new.css') }}?v={{ config('css_versions.advertiser_custom_new_version') }}">
<style>
    .temschat{
        background-color: #dcdcdc4f;
        padding: 15px;
        margin: 0px;
        border-radius: 15px;
        color: red;
        word-break: break-word;
    }
    #message-box button{
        border: 0;
        background: #fff;
        padding: 0;
    }
    .completed_msg{
        background-color: #fff;
        padding: 15px;
        margin: 15px 0;
        border-radius: 15px;
        color: green;
        word-break: break-word;
    }
    .completed_msg p{
        color: red;
        text-align: left!important;
    }

    .notiP
    {
        background-color: #fc6527;
        border-radius: 50%;
        position: absolute;
        color: #fff;
        height: 16px;
        width: 16px;
    }
    #myTable tr td a.chat {
        position: relative;
    }
    #show_all_table_list .table .order-type {
        white-space: nowrap;
    }
    .hide_column {
        display : none;
    }
</style>
<input type="hidden" name="res_id" id="res_id" value="">
<div class="box-body " id="order_table_list">
    <table class="table table-striped thead-primary w-100" id="myTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Order ID</th>
                <th>LP Order ID</th>
                <th>Website</th>
                <th>Content Type</th>
                <th>Language</th>
                <th>Due Date</th>
                <th>Due Time</th>
                <th id="statusColumn">Status</th>
                <th>Price</th>
                <th class="comments">Chat</th>
            </tr>
        </thead>
    </table>
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
                    <div id="chat_body">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <input type="hidden" name="to_id" id="to_id" value="">
                    <input type="hidden" name="order_id" id="order_id" class="order_id" value="">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var res_id = "{{ $resellerId }}";
        $('#res_id').val(res_id);
        let table = $('#myTable').DataTable({
            serverSide: true,
            ajax: {
                url: "/api/reseller-order-data",
                data: function(d) {
                    let status = $('.tablinks.active').data('status');
                    d.status = (status !== undefined) ? status : 'all';
                    d.reseller_id = "{{ $resellerId }}";
                }
            },
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'reseller_order_lable', name: 'reseller_order_lable' },
                { data: 'order_lable', name: 'order_lable' },
                { data: 'website_id', name: 'website_id', orderable: false, searchable: false },
                { data: 'content_writter', name: 'content_writter' },
                { data: 'Preferred_language', name: 'language', defaultContent: 'English' },
                { data: 'due_date', name: 'due_date' },
                { data: 'due_time', name: 'due_time' },
                { data: 'status', name: 'status', visible: false },
                { data: 'total', name: 'price' },
                { data: 'chat', name: 'chat' }
            ],
            order: [[0, 'desc']]
        });

        $('.tablinks').on('click', function() {
            $('.tablinks').removeClass('active');
            $(this).addClass('active');

            let status = $(this).data('status') || 'all';

            if ($(this).attr('id') === 'All_id') {
                $('#statusColumn').show();
                table.column(8).visible(true);
            } else {
                $('#statusColumn').hide();
                table.column(8).visible(false);
            }

            table.ajax.url('/api/reseller-order-data').load();
            table.ajax.reload(null, false);
        });

        $(document).on('click', '.chat-icon', function() {

            var orderlabel = $(this).attr('data-orderlabel');
            var order_attribute_id = $(this).attr('data-oaid');
            var publisher_id = $(this).attr('data-publisher');
            var status = $(this).attr('data-status');
            var user_id = $(this).attr('data-userid');
            
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
                    'reseller_id': res_id,
                    "_token": "{{ csrf_token() }}"
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
    });
</script>
@endpush

@section('resellerId')
    {{ $resellerId }}
@endsection

@section('resellerName')
    {{ $resellerName }}
@endsection