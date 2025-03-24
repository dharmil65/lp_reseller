@php

$followLink = null;

if (isset($cartDetails->dofollow_link) && $cartDetails->dofollow_link != null) {
    $followLink = $cartDetails->dofollow_link;
} elseif (isset($cartDetails->nofollow_link) && $cartDetails->nofollow_link != null) {
    $followLink = $cartDetails->nofollow_link;
}

$selectedLanguage = trim($cartDetails->language);

$originalExpertPrice = $cartDetails->expert_price; // Save the original Expert price

@endphp

<div class="col-md-6">
    <div class="form-group">
        <label for="prefered_language">Language<span>*</span></label>
        <select name="prefered_language" class="prefered_language" id="prefered_language" @if(count($languageList) === 1) readonly disabled @endif>
            @foreach($languageList as $lan)
                <option @if ($selectedLanguage === trim($lan)) selected @endif value="{{ trim($lan) }}">{{ trim($lan) }}</option>
            @endforeach
        </select>
        <label id="prefered_language-error" style="display:none" class="invalid" for="prefered_language">The language field is required</label>
        <span class="language-note">@if(count($languageList) === 1)Note: The publisher only accepts content in {{$languageList[0]}} @endif</span>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="word">Word Count<span>*</span></label>
        <select name="word_count" class="word_count" id="word_count">
            <option value="">Select Word Count</option>
            @foreach($expertprice as $key => $val)
                @php
                    preg_match('/(\d+)\s+Words/', $val->name, $matches);
                    $wordCount = isset($matches[1]) ? (int)$matches[1] : 0;
                @endphp
                @if($wordCount >= $websiteDetail->article_count)
                    <option value="{{$val->name}}" data-id="{{$val->id}}" 
                        @if(($cartDetails->language == 'English' && $cartDetails->expert_price == $val->price) 
                        || ($cartDetails->language != 'English' && $cartDetails->expert_price == $val->non_english_price)) selected @endif 
                        data-price="{{$val->price}}" data-non-english-price="{{$val->non_english_price}}">{{$val->name}}
                    </option>
                @endif
            @endforeach
        </select>
        <a @if(!$cartDetails->expert_price)style="display:none"@endif class="expert_price_list" href="javascript:void(0)">Expert Price :<span class="expert_price">${{$originalExpertPrice}}</span></a>
        <label id="word_count-error" style="display:none" class="invalid" for="word_count">The word count field is required</label>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="suggestion">Title Suggestion</label>
        <input type="text" id="titlesuggestion" class="titlesuggestion" value="{{$cartDetails->title}}" name="titlesuggestion" placeholder="Suggest Title">
        <input type="hidden" id="follow_link_hire_content_writer" class="follow_link_hire_content_writer" value="{{ $followLink }}" name="follow_link_hire_content_writer">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="word">Category<span>*</span></label>
        <select name="category" class="category" id="category">
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" 
                    {{ $cartDetails->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <label id="category-error" style="display:none" class="invalid" for="category">The category field is required</label>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="word">Keywords<span>*</span></label>
        <input type="text" id="keywords" class="keywords" name="keywords" value="{{$cartDetails->keyword}}" placeholder="Provide Keywords; Separated by comma">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="referencelink">Reference Link<span>*</span></label>
        <input type="text" name="referencelink" id="referencelink" value="{{$cartDetails->refrence_link}}" placeholder="eg. https://example.com">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="anchortext">Anchor Text<span>*</span></label>
        <input type="text" name="anchortext" id="anchortext" value="{{$cartDetails->anchor_text}}" placeholder="Enter Anchor text">
        <label style="display: none;" id="anchortext-error" class="invalid" for="anchortext">The anchor text field is required</label>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group landing-group">
        <label for="targeturl">Landing Page URL<span>*</span></label>
        <input type="text" name="targeturl" id="targeturl" value="{{$cartDetails->reference}}"
            placeholder="Enter Landing Page URL">
        @if($followLink > 1)
        <button type="button" id="addInputBtn" class="btn btn-sm"><i class="fas fa-plus-circle"></i></button>
        @endif
    </div>
</div>

<div class="col-md-12" id="dynamicInputContainer">
    @for ($i = 1; $i <= 4; $i++)
        @if(!empty($cartDetails->{'anchor_text_' . $i}))
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="anchor_text_{{ $i }}" value="{{ $cartDetails->{'anchor_text_' . $i} }}" id="anchor_text_{{ $i }}" placeholder="Enter Anchor text" class="anchor_text_added">
                        <label style="display: none;" id="anchor_text-error_{{ $i }}" class="invalid" for="anchor_text_{{ $i }}">The anchor text field is required</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group landing-group">
                        <input type="text" name="target_url_{{ $i }}" value="{{ $cartDetails->{'target_url_' . $i} }}" id="target_url_{{ $i }}" placeholder="Enter Landing Page URL">
                        <button type="button" class="btn btn-sm removeInputBtn"><i class="fas fa-minus-circle"></i></button>
                    </div>
                </div>
            </div>
        @endif
    @endfor
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="country">Target Audience is from (Country)<span>*</span></label>
        <select name="target_audience" class="target_audience" id="target_audience">
            <option value="">Select Target Audience</option>
            @foreach($countries as $country)
                <option @if($cartDetails->target_audience == $country->id) selected @endif value="{{ $country->id }}">{{ $country->name }}</option>
            @endforeach
        </select>
        <label id="target_audience-error" style="display:none" class="invalid" for="target_audience">The target audience field is required</label>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="word">Brief Note</label>
        <textarea cols="20" rows="6" id="briefnote" name="briefnote" placeholder="Brief Note: Any additional notes required can be specified here in detail." class="specialinstruction">{{ $cartDetails->brief_note }}</textarea>
    </div>
</div>

<div class="col-md-12">
    <h5 class="advance_writing_info">Advance Writing Information</h5>
</div>

<div class="col-md-4 advance_writing_info_list">
    <div class="form-group">
        <label for="word">Choose Writing</label>
        <select name="choose_writing" class="choose_writing" id="choose_writing">
            <option @if($cartDetails->choose_content == 'article') selected @endif value="article">Article</option>
            <option @if($cartDetails->choose_content == 'website_content') selected @endif value="website_content">Website Content</option>
            <option @if($cartDetails->choose_content == 'seo_content') selected @endif value="seo_content">SEO Content</option>
            <option @if($cartDetails->choose_content == 'product_description_and_promotion') selected @endif value="product_description_and_promotion">Product Description & Promotion</option>
            <option @if($cartDetails->choose_content == 'blog') selected @endif value="blog">Blog</option>
            <option @if($cartDetails->choose_content == 'press_release') selected @endif value="press_release">Press Release</option>
            <option @if($cartDetails->choose_content == 'product_brand_review') selected @endif value="product_brand_review">Product/Brand Review</option>
            <option @if($cartDetails->choose_content == 'technical_writing') selected @endif value="technical_writing">Technical Writing</option>
        </select>
    </div>
</div>

<div class="col-md-4 advance_writing_info_list">
    <div class="form-group">
        <label for="word">Writing Style</label>
        <select name="writing_style" class="writing_style" id="writing_style">
            <option @if($cartDetails->writting_style == 'informative_and_educational') selected @endif value="informative_and_educational">Informative & Educational</option>
            <option @if($cartDetails->writting_style == 'lighthearted_and_conversational') selected @endif value="lighthearted_and_conversational">Lighthearted & Conversational</option>
            <option @if($cartDetails->writting_style == 'sales_driven_and_promotional') selected @endif value="sales_driven_and_promotional">Sales Driven & Promotional</option>
        </select>
    </div>
</div>

<div class="col-md-4 advance_writing_info_list">
    <div class="form-group">
        <label for="word">Preferred Voice</label>
        <select name="prefered_voice" class="prefered_voice" id="prefered_voice">
            <option @if($cartDetails->preferred_voice == 'first_person') selected @endif value="first_person">First Person</option>
            <option @if($cartDetails->preferred_voice == 'second_person') selected @endif value="second_person">Second Person</option>
            <option @if($cartDetails->preferred_voice == 'third_person') selected @endif value="third_person">Third Person</option>
            <option @if($cartDetails->preferred_voice == 'let_the_writer_decide') selected @endif value="let_the_writer_decide">Let The Writer Decide</option>
        </select>
    </div>
</div>

<div class="modal-footer">
    <button type="button" data-id="{{$cartDetails->id}}" data-webid="{{$cartDetails->website_id}}" data-marketplace="{{$cartDetails->marketplace_type}}" cart-detailtype="hire_content" data-quantity="{{$cartDetails->quantity_no}}" class="btn button @if($cartDetails->content_writter) clear_cart_data @else disabled @endif">Clear</button>
    <button type="submit" id="" class="btn button btn-primary">Submit</button>
</div>