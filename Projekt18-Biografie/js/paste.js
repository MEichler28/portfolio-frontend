document.onkeydown = function(e) {
    if (e.ctrlKey &&
    (e.keyCode === 67 ||
    e.keyCode === 86 ||
    e.keyCode === 85 ||
    e.keyCode === 117)) {
        return false;
    } else {
        return true;
    }
    
};

$(document).keypress("u",function(e) {
    if(e.ctrlKey)
    {
        return false;
    }
    else
    {
        return true;
    }
});

(function($){ $(window).load(function(){ $("a[rel='load-content']").click(function(e){ e.preventDefault(); var url=$(this).attr("href"); $.get(url,function(data){
			$(".content .mCSB_container").append(data); $(".content").mCustomScrollbar("scrollTo","h2:last"); });}); $(".content").delegate("a[href='top']","click",function(e){
			e.preventDefault();	$(".content").mCustomScrollbar("scrollTo",$(this).attr("href"));});});})(jQuery);
			
			window.jQuery || document.write('<script src="../js/minified/jquery-1.11.0.min.js"><\/script>')
			
			function switch_container(id) {$("#container_" + id).siblings().hide();$("#container_" + id).fadeToggle('slow');$(".selected").removeClass('selected');$("#container_" + id + "_href").addClass('selected');}
			
			function wechselDich(id) { if (document.getElementById) { if (document.getElementById(id).style.display == 'none') {  document.getElementById(id).style.display = 'block'; }else { document.getElementById(id).style.display = 'none';}}}