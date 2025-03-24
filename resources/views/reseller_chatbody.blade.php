@foreach($chatMessage as $key => $val)
    @if(isset($chatMessage[$key - 1]))
        @if(strtotime(date('d/m/Y', strtotime($val->created_at))) > strtotime(date('d/m/Y', strtotime($chatMessage[$key - 1]->created_at))))
            <h5 style="margin-bottom: 15px">{{ date('d/m/Y', strtotime($val->created_at)) }}</h5>
        @endif
    @else
        <h5 style="margin-bottom: 15px">{{ date('d/m/Y', strtotime($val->created_at)) }}</h5>
    @endif

    @if($user_id == $val->from_id)
        <div class="chat-advertiser">
            @if ($val->status != 2)
                @if(isset($val->body_with_links))
                    <p>{!! $val->body_with_links !!}</p>
                @else
                    <p>{{ $val->body }}</p>
                @endif
            @else
                <p class="deleted_msg">{{ $val->body }}</p>
                <a class="deleted-msg-tooltip chat-tooltip" buyer-title="Deleted message: Your message is in violation of the terms and conditions.">
                    <img src="{{ asset('assets/images/buyer-icon.png') }}">
                </a>
            @endif
            <div class="advertiser-details">
                <span class="advertiser-user">
                    <img src="{{ asset('assets/images/buyer-icon.png') }}">
                    <p>
                        {{ "Publisher" }} <b>{{ ($user_id == $val->from_id) ? 'Advertiser' : 'Publisher' }}</b>, {{ date('h:i A', strtotime($val->created_at)) }}
                    </p>   
                </span>
            </div>
        </div>
    @else
        <div class="chat-advertiser chat-publisher">
            @if ($val->status != 2)
                @if(isset($val->body_with_links))
                    <p>{!! $val->body_with_links !!}</p>
                @else
                    <p>{{ $val->body }}</p>
                @endif
            @else
                <p class="deleted_msg">{{ $val->body }}</p>
                <a class="deleted-msg-tooltip chat-tooltip" buyer-title="Deleted message: Your message is in violation of the terms and conditions.">
                    <img src="{{ asset('assets/images/buyer-icon.png') }}">
                </a>
            @endif
            <div class="advertiser-details">
                <span class="advertiser-user">
                    <img src="{{ asset('assets/images/buyer-icon.png') }}">
                    <p>
                        {{ "Advertiser" }} <b>{{ ($user_id == $val->from_id) ? 'Advertiser' : 'Publisher' }}</b>, {{ date('h:i A', strtotime($val->created_at)) }}
                    </p>   
                </span>
            </div>
        </div>
    @endif
@endforeach

@if(isset($order_status) && ($order_status == 0 || $order_status == 6))
    <div class="messagesss temschat">
        @if($order_status == 0)
            <p>Chat is disabled as this order is rejected.</p>
        @elseif($order_status == 6)
            <p>Chat is disabled as this order is completed.</p>
        @endif
    </div>
@endif