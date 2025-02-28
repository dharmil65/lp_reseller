$(document).ready(function() {

	$('#sort-by').select2();
	$('#sort-by').css('display', 'block');
	$('#quick-filters').css('display', 'block');
	$('#quick-filters').select2();

	var $range = $(".js-range-slider"),
    $from = $(".from"),
    $to = $(".to"),
    range,
    min = $range.data('min'),
    max = $range.data('max'),
    from,
    to;

	var updateValues = function () {
		$from.prop("value", from);
		$to.prop("value", to);
	};

	$range.ionRangeSlider({
		onChange: function (data) {
		from = data.from;
		to = data.to;
		updateValues();
		
		}
	});

	range = $range.data("ionRangeSlider");
	var updateRange = function () {
		range.update({
			from: from,
			to: to
		});
	};

	$from.on("input", function () {
		from = +$(this).prop("value");
		if (from < min) {
			from = min;
		}
		if (from > to) {
			from = to;
		}
		updateValues();    
		updateRange();
	});

	$to.on("input", function () {
		to = +$(this).prop("value");
		if (to > max) {
			to = max;
		}
		if (to < from) {
			to = from;
		}
		updateValues();    
		updateRange();
	});
});

let arrow = document.querySelectorAll(".arrow");
for (var i = 0; i < arrow.length; i++) {
  arrow[i].addEventListener("click", (e)=>{
 let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
 arrowParent.classList.toggle("showMenu");
  });
}
let sidebar = document.querySelector(".main-wrapper");
let sidebarBtn = document.querySelector(".ms-toggler");
// console.log(sidebarBtn);
sidebarBtn.addEventListener("click", ()=>{
  sidebar.classList.toggle("side-close");
});

$(window).scroll(function() {
  
  
	if ($(window).width() > 991) {
	 
		var sticky = $('.site-header'),
			scroll = $(window).scrollTop();
  
		if (scroll >= 1){
  
	
		  sticky.addClass('sticky');
		} 
		else {sticky.removeClass('sticky') };
	}
});

// new 
