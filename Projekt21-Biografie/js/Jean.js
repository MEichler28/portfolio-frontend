function switch_container(id) {
	$("#container_" + id).siblings().hide();
	$("#container_" + id).fadeToggle('slow'); 
}