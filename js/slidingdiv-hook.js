jQuery.noConflict();
jQuery(document).ready(function(){
	jQuery(".show_hide").click(function(){
		jQuery("#pxlcore-updates-wrap").slideToggle("slow");
		jQuery(this).toggleClass("active"); return false;
	});
});