/*** 
    Simple jQuery Slideshow Script
    Released by Jon Raasch (jonraasch.com) under FreeBSD license: free to use or modify, not responsible for anything, etc.  Please link out to me if you like it :)
***/

function slideSwitch() {
    var jQueryactive = jQuery('#slidepic IMG.active');

    if ( jQueryactive.length == 0 ) jQueryactive = jQuery('#slidepic IMG:last');

    // use this to pull the images in the order they appear in the markup
    var jQuerynext =  jQueryactive.next().length ? jQueryactive.next()
        : jQuery('#slidepic IMG:first');

    // uncomment the 3 lines below to pull the images in random order
    
    // var jQuerysibs  = jQueryactive.siblings();
    // var rndNum = Math.floor(Math.random() * jQuerysibs.length );
    // var jQuerynext  = jQuery( jQuerysibs[ rndNum ] );


    jQueryactive.addClass('last-active');

    jQuerynext.css({opacity: 0.0})
        .addClass('active')
        .animate({opacity: 1.0}, 1500, function() {
            jQueryactive.removeClass('active last-active');
        });
}

jQuery(function() {
    setInterval( "slideSwitch()", 5000 );
});