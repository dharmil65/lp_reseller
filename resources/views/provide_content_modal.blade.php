<div class="form-group m-0">
    <label for="language">Language*  
        <span class="language-note">
            @if(count($languageList) === 1) 
                Note: The publisher only accepts content in {{$languageList[0]}} 
            @endif
        </span>
    </label>
    <select name="language" id="language" class="form-control" @if(count($languageList) === 1) readonly disabled @endif>
        @if(count($languageList) > 1)
            <option value="">Select Language</option>
        @endif
        @foreach($languageList as $lan)
            <option @if ($cartDetails->language === trim($lan) || (count($languageList) === 1 && $cartDetails->language === null)) selected @endif value="{{ trim($lan) }}">
                {{ trim($lan) }}
            </option>
        @endforeach
    </select>
    <label id="language-error" style="display:none" class="invalid" for="language"></label>
</div>

<h6>Attachments* 
    <span>Note: Support Only Doc, Docx</span>
</h6>     
<input type="hidden" name="provide_content_cart_id" id="provide_content_cart_id" value="">
<input type="hidden" name="provide_content_website_id" id="provide_content_website_id" value="">
<input type="hidden" name="provide_content_marketplace_type" id="provide_content_marketplace_type" value="">
<input type="hidden" name="provide_content_quantity" id="provide_content_quantity" value="">

<div class="user-image" title="">
    <img src="{{asset('assets/images/cart-plus.svg')}}" alt="cart-plus.svg">
    <div class="edit-icon">
        <p>Drop files here or</p>
        <input title="Click to choose a Word file" id="attachment" type="file" name="attachment" value="@if($cartDetails->attachment){{$cartDetails->attachment}}@endif" accept=".doc,.docx" class="custom-file-input">
        <span>Browse</span>
    </div>
</div>

@if($cartDetails->attachment)
    <span name="hidefilesAttach" data-value="{{$cartDetails->attachment}}" id="hidefilesAttach" class="@if($cartDetails->attachment) attach-file-name @endif">
        <a id="download-attachment" href="/order/{{$cartDetails->attachment}}">
            @if($cartDetails->attachment) 
                {{$cartDetails->attachment}} 
                <i class="fas fa-download"></i> 
            </a>
        @endif
    </span>
@endif

<label id="attachment-error" style="display:none" class="invalid" for="attachment">The attachment field is required</label>

<div class="form-group">
    <label for="Special">Special Instructions</label>
    <textarea cols="5" rows="5" maxlength="400" id="instruction" name="instruction">{{$cartDetails->instruction}}</textarea>
</div>

<div class="modal-footer">
    <button type="button" data-id="{{$cartDetails->id}}" data-webid="{{$cartDetails->website_id}}" data-marketplace="{{$cartDetails->marketplace_type}}" cart-detailtype="provide_content" data-quantity="{{$cartDetails->quantity_no}}" class="btn button @if($cartDetails->content_writter) clear_cart_data @else disabled @endif">Clear</button>
    <button type="submit" id="" class="btn button btn-primary">Submit</button>
</div>