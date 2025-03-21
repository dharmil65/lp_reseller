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
                <th>Status</th>
                <th>Price</th>
            </tr>
        </thead>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
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
                { data: 'status', name: 'status' },
                { data: 'total', name: 'price' }
            ],
            order: [[0, 'desc']]
        });

        $('.tablinks').on('click', function() {
            $('.tablinks').removeClass('active');
            $(this).addClass('active');

            let status = $(this).data('status') || 'all';

            table.ajax.url('/api/reseller-order-data').load();
            table.ajax.reload(null, false);
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