<div class="cart-website-info">
    <div class="cart-website-title">
        <a target="_blank" href="{{$websiteDetail->website_url}}">{{$websiteDetail->host_url}}</a>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#information_modal">
            <img src="{{asset('assets/images/cart-information.svg')}}" alt="cart-information">
        </button>
        <a class="btn `button` guidelines-btn" data-toggle="modal" data-target="#cart-guidelines-modal">
            <img src="{{asset('assets/images/guidelines-question.svg')}}" alt="guidelines-question"> Guidelines
        </a>
        @isset($websiteDetail->status)
            @if(($websiteDetail->status == 1 || $websiteDetail->status == 2 || $usersDetail->vacation_mode == 1) && ($websiteDetail->deleted_at == '' && $usersDetail->deleted_at == '' && $websiteDetail->status != 3))
                <span>Publisher made modifications to the site, so it is under review. </span>
            @else
                <span>The site is currently unavailable.</span>
            @endif
        @endif
        @if($cartPrice != null)
            <p>Minimum Word Count:<span> {{($websiteDetail->article_count ? $websiteDetail->article_count : 500)}}</span></p>
        @endif
        @if(isset($websiteDetail->completion_ratio) && $websiteDetail->completion_ratio > 0)
            <p>Completion ratio: <span>{{ $websiteDetail->completion_ratio }}%</span></p>
        @endif
    </div>
    @if(($usersDetail->userDeleted == '' && $usersDetail->deleted_at == '' && $usersDetail->is_active == 0))
        @if(isset($websiteDetail->status))
            @if($websiteDetail->status != 3)
                <a id="add_to_wishlist" data-marketplace-type="{{$cartDetails[0]->marketplace_type}}" data-web-id="{{$websiteDetail->website_id}}" value="{{$websiteDetail->website_id}}" class="cart-site-wishlist add_to_wishlist cart-tooltip" info-title="wishlist">
                    <img src="{{asset('assets/images/cart-action-wishlist.svg')}}" alt="cart-action-wishlist">
                </a>
            @endif
        @else
            <a id="add_to_wishlist" data-marketplace-type="{{$cartDetails[0]->marketplace_type}}" data-web-id="{{$websiteDetail->website_id}}" value="{{$websiteDetail->website_id}}" class="cart-site-wishlist add_to_wishlist cart-tooltip" info-title="wishlist">
                <img src="{{asset('assets/images/cart-action-wishlist.svg')}}" alt="cart-action-wishlist">
            </a>
        @endif
    @endif
</div>

<?php $backlink = 0; ?>
@foreach($cartDetails as $cartDetail)
    <div class="cart-website-detail">
        <div class="cart-backlink-title">
            <?php $backlink++; ?>
            <h5><span id="cart_backlink_no_{{$cartDetail->id}}"> Backlink {{$backlink}} </span></h5>
            @if($cartDetail->content_writter == null || $cartDetail->content_writter == '')
                <div class="cart-listing-dott"></div>
            @else
                <div class="cart-listing-dott cart-dott-fill"></div>
            @endif
        </div>
        <ul class="cart-order-tab">
            @if($cartDetail->price != null)
                <li class="cart-order-item">
                    <form>
                        <div class="form-group @if($cartDetail->content_writter == 'provide_content' || $cartDetail->content_writter == '') provide_content @else info_msg_show_data_occupy @endif" data-toggle="modal" id="provide_content_{{$cartDetail->id}}" data-id="{{$cartDetail->id}}" data-quantity="{{$cartDetail->quantity_no}}" data-web="{{$websiteDetail->website_id}}" data-matketplace="{{$cartDetail->marketplace_type}}" data-price="{{$cartDetail->price}}">
                            <input type="checkbox" class="checkboxll" @if($cartDetail->content_writter=='provide_content' ) checked @endif name="cart" id="">
                            <label for="provide"><span>Provide Content</span></label>
                        </div>
                    </form>
                </li>
                <li class="cart-order-item">
                    <form>
                        <div class="form-group @if($cartDetail->content_writter == 'expert_writter' || $cartDetail->content_writter == '') hire_content @else info_msg_show_data_occupy @endif" data-toggle="modal" id="hire_content_{{$cartDetail->id}}" data-id="{{$cartDetail->id}}" data-quantity="{{$cartDetail->quantity_no}}" data-web="{{$websiteDetail->website_id}}" data-matketplace="{{$cartDetail->marketplace_type}}" data-publishprice="{{$cartDetail->price}}" data-price="{{$cartDetail->price + $cartDetail->expert_price}}">
                            <input type="checkbox" @if($cartDetail->content_writter=='expert_writter' ) checked @endif class="checkboxll" name="Hire" id="">
                            <label for="Hire"><span>Hire Content Writer</span></label>
                        </div>
                    </form>
                </li>
            @endif
            @if($cartDetail->link_insertion_price != null)
                <li class="cart-order-item">
                    <form>
                        <div class="form-group @if($cartDetail->content_writter == 'link_insertion' || $cartDetail->content_writter == '') link_content @else info_msg_show_data_occupy @endif" data-toggle="modal" id="link_content_{{$cartDetail->id}}" data-id="{{$cartDetail->id}}" data-quantity="{{$cartDetail->quantity_no}}" data-web="{{$websiteDetail->website_id}}" data-matketplace="{{$cartDetail->marketplace_type}}" data-price="{{$cartDetail->link_insertion_price}}">
                            <input type="checkbox" @if($cartDetail->content_writter=='link_insertion' ) checked @endif class="checkboxll" name="Link" id="">
                            <label for="Link"><span>Link Insertion</span></label>
                        </div>
                    </form>
                </li>
            @endif
        </ul>
        <h5>
            <div class="">
                $@if($cartDetail->content_writter == 'link_insertion') 
                    {{ $cartDetail->link_insertion_price}} 
                @elseif($cartDetail->content_writter == 'expert_writter') 
                    {{$cartDetail->price + $cartDetail->expert_price}} 
                @else 
                    @if($cartDetail->price) 
                        {{$cartDetail->price}} 
                    @else 
                        {{$cartDetail->link_insertion_price}} 
                    @endif 
                @endif 
                @php
                    $price = null;
                    if ($cartDetail->content_writter == 'link_insertion' && isset($websiteDetail->without_discount_linkinsertion_price)) {
                        if($cartDetail->marketplace_type == 1){
                            $price = $websiteDetail->without_discount_fc_linkinsertion_price;
                        }else{
                            $price = $websiteDetail->without_discount_linkinsertion_price;
                        }
                    } elseif ($cartDetail->content_writter == 'expert_writter' && isset($websiteDetail->without_discount_guest_post)) {
                        if($cartDetail->marketplace_type == 1){
                            $price = $websiteDetail->without_discount_fc_guest_post + $cartDetail->expert_price;
                        }else{
                            $price = $websiteDetail->without_discount_guest_post + $cartDetail->expert_price;
                        }
                    } elseif (isset($websiteDetail->without_discount_guest_post) || isset($websiteDetail->without_discount_linkinsertion_price)) {
                        if (isset($websiteDetail->without_discount_guest_post) && $websiteDetail->without_discount_guest_post != 0) {
                            if($cartDetail->marketplace_type == 1){
                                $price = $websiteDetail->without_discount_fc_guest_post;
                            }else{
                                $price = $websiteDetail->without_discount_guest_post;
                            }
                        }else{
                            if($cartDetail->marketplace_type == 1){
                                $price = $websiteDetail->without_discount_fc_linkinsertion_price;
                            }else{
                                $price = $websiteDetail->without_discount_linkinsertion_price;
                            }
                        }
                    } 
                @endphp
                @if($price !== null)
                    <span class="old-price"> ${{ $price }} </span>
                @endif
            </div>
        </h5>
        <div class="cart-order-edit">
            @if($cartDetail->content_writter != '')
                <a class="order-edit cart-tooltip" info-title="edit">
                    <img class="@if($cartDetail->content_writter == 'provide_content') provide_content @elseif($cartDetail->content_writter == 'expert_writter') hire_content @elseif($cartDetail->content_writter == 'link_insertion') link_content @endif" src="{{asset('assets/images/cart-edit.svg')}}" data-toggle="modal" id="provide_content_img_{{$cartDetail->id}}" data-id="{{$cartDetail->id}}" data-quantity="{{$cartDetail->quantity_no}}" data-web="{{$websiteDetail->website_id}}" data-matketplace="{{$cartDetail->marketplace_type}}" data-price="@if($cartDetail->content_writter == 'link_insertion') {{ $cartDetail->link_insertion_price}} @elseif($cartDetail->content_writter == 'expert_writter'){{$cartDetail->price + $cartDetail->expert_price}} @else @if($cartDetail->price) {{$cartDetail->price}} @else {{$cartDetail->link_insertion_price}} @endif @endif" data-publishprice="{{$cartDetail->price}}" alt="cart-edit">
                </a>
            @endif
            @if($cartDetail->quantity_no != 1)
                <a data-cart-id="{{$cartDetail->id}}" data-web-id="{{$cartDetail->website_id}}" data-content-type="{{$cartDetail->content_writter}}" class="order-remove cart_qty_delete cart-tooltip" info-title="delete">
                    <img src="{{asset('assets/images/cart-action-remove.svg')}}" alt="cart-action-remove">
                </a>
            @endif
        </div>
    </div>
@endforeach

<?php $count = (count($cartDetails) - 1) ?>
<?php $qty = (count($cartDetails) + 1) ?>

<div class="cart-order-add">
    @if($qty > 5)
    @elseif($cartDetails[$count]->content_writter == '')
        <a id="add_backlink_msg" class="btn button btn-primary">+Add Backlink </a>
    @else
        <a id="add_backlink" data-web="{{$websiteDetail->website_id}}" data-matketplace="{{$cartDetail->marketplace_type}}" data-quantity="{{$qty}}" class="btn button btn-primary add_backlink">+Add Backlink</a>
    @endif
    <div class="cart-tourguide cart-site-tourguide" style="display:none" id="stepThree">
        <div class="cart-tourguide-detail">
            <h6>Specify the number of backlinks for a site, with a maximum limit of 5 links.</h6>
        </div>
        <div class="tourguide-step">
            <p>Step <span>3</span> of 3</p>
            <div class="cart-tourguide-btn">
                <a id="cartInfoSubmit" class="btn button btn-primary cartInfoSubmit">Done</a>
            </div>
        </div>
    </div>
</div>