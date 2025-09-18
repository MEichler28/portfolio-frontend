function show(id) {
		document.getElementById("h1").style.display="none";
		document.getElementById("h2").style.display="none";
		document.getElementById("h3").style.display="none";
		document.getElementById("h4").style.display="none";
		document.getElementById(id).style.display="block";
	}
			$(function() {

	$('.activater').hover(
		function() {
			$(this).siblings().show().stop().animate({
				height: '125'
			}, "fast");
			$(this).siblings().children().show().css('opacity', 1);
		},
		function() {
			$(this).siblings().stop().animate({
					height: '1'
				}, "fast", 
				function() {
					$(this).hide();
				});
			$(this).siblings().children().hide();
		}
	);
		
	$('.activater').click(function() {
		var my_id = $(this).attr('id');
		
		$('.darkener').fadeIn('normal', function() {
			$('.text_container[id="' + my_id + '"]').slideDown('normal');
		});		
	});
	
	
	
	var clickInBiotext = false;
	$('.text_container').click(function() {
		clickInBiotext = true;
	});
	
	$('.darkener').click(function(){
		if(!clickInBiotext) {
			$('.text_container').slideUp('normal', function() {
				$('.darkener').fadeOut('normal', function() {
					$(this).hide();
					$('.text_container').hide();
				});
			});
		}
		
		clickInBiotext = false;
	});
});