/**
 * @version		$Id: $
 * @author		joomlaux!
 * @package		Joomla.Site
 * @subpackage	com_jux_real_estate
 * @copyright	Copyright (C) 2008 - 2012 by joomlaux. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 */

jQuery.noConflict();
function jp_Rotator(selector, scrollTime, pauseTime, nav){

  jQuery(selector+" li:first").css("display", "block"); //show the first list item
  var count = jQuery(selector+" li").size(); //get total number of list items
  if(count > 1){ //dont do anything if there is only one list item.

	  if(scrollTime == null){ var scrollTime=500; } //default scroll time (length of transition)
	if(pauseTime== null){ var pauseTime=5000; } //default pause time (how long to hold the image between transitions)

	jQuery(selector+" li").each(function( intIndex ){ jQuery(this).attr('rel', (intIndex+1)); }); //add the list position to the each of the items

	if(nav != null){
	  var i = 1;
	  jQuery(selector).append("<div id='bannerNav'></div>"); //create navigation buttons
	  while(i <= count){
		  if(i == jQuery(selector+" li:visible").attr('rel')){  //if its the nav item that belongs to the visible image, mark it as the active nav item
		  jQuery('#bannerNav').append("<a class='active' rel='"+i+"' href='#'></a> ");
		  }
		  else{
			jQuery('#bannerNav').append("<a rel='"+i+"' href='#'></a> ");
		  }
		  i++;
	  }
	  jQuery('#bannerNav').append("<span class='pause'></span> "); //pause button
	  jQuery('#bannerNav').append("<span href='#' class='play' style='display:none;'></span> "); //play button

	  jQuery("#bannerNav a").click(function () { //handle navigation by clicking nav items
		jQuery("#bannerNav a.active").removeClass('active');
		  jQuery(this).addClass('active'); //move the active nav item to this item
		  var currentClassName = jQuery(selector+" li:visible").attr('rel');
		  var nextClassName = jQuery(this).attr('rel');
		  var storedTimeoutID = jQuery("#bannerNav").attr('timeoutID');

		  clearTimeout(storedTimeoutID);//stop the images from looping when a nav button is pressed
		  jQuery("span.pause").hide();
		  jQuery("span.play").show();

		  if( nextClassName != currentClassName ){ //only change images if they clicked on a new item (not the one they are viewing)
			jQuery(selector+" li:visible").fadeOut(scrollTime);
			jQuery(selector+" li[rel="+nextClassName+"]").fadeIn(scrollTime);
		  }
		  return false;
	  });

	  jQuery("span.pause").click(function () { //stop the images looping on pause click
		var storedTimeoutID = jQuery("#bannerNav").attr('timeoutID');
		  clearTimeout(storedTimeoutID);
		  jQuery("span.pause").hide();
		  jQuery("span.play").show();
	  });

	  jQuery("span.play").click(function () { //start the images looping on play click
		scrollImages(count, selector, scrollTime, pauseTime);
		  jQuery("span.play").hide();
		  jQuery("span.pause").show();
	  });
	}

	var timeout = setTimeout(function(){
	  scrollImages(count, selector, scrollTime, pauseTime);
	}, pauseTime);

	jQuery("#bannerNav").attr('timeoutID', timeout); //save the timeout id so we can cancel the loop later if a nav button is pressed
  }
}

function scrollImages(count, selector, scrollTime, pauseTime){
  currentClass = jQuery(selector+" li:visible").attr('rel'); //get the list position of the current image
  nextClass = jQuery(selector+" li:visible").attr('rel'); //open a new variable for the next class
  if (currentClass == count ){ nextClass=1; } //if you've reached the end of the images... start from number 1 again
  else{ nextClass++; } //if not just add one to the last number

  jQuery(selector+" li[rel="+currentClass+"]").fadeOut(scrollTime); //fade out old image
  jQuery("#bannerNav a.active").removeClass('active'); //remove active class from our nav

  jQuery(selector+" li[rel="+nextClass+"]").fadeIn(scrollTime); //fade in new image
  jQuery("#bannerNav a[rel="+nextClass+"]").addClass('active'); //add new active class to the next nav item

  var timeout = setTimeout(function(){ scrollImages(count, selector, scrollTime, pauseTime); }, pauseTime); //scroll the banners again after waiting for pauseTime
  jQuery("#bannerNav").attr('timeoutID', timeout); //save the timeout id so we can cancel the loop later if a nav button is pressed
}

