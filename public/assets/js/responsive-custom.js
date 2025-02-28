$(document).ready(function() {
    $(".humberg-icon").click(function () {
		$("body").toggleClass("menu-open");
	});
	$('.close').on('click', function (e) {
	  $('body').removeClass("menu-open");
	});

    $(".filter-btn").click(function () {
		$("body").toggleClass("filter-open");
	});
	$('.close').on('click', function (e) {
	  $('body').removeClass("filter-open");
	});
});