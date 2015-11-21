<?php

 // get domain name from $_SERVER-array (predefined php-var)
 $domain = $_SERVER['SERVER_NAME'];
 if ( substr ( $domain, 0, 4 ) == "www.") {
  $domain = substr ( $domain, 4 );
 }

 // include text-source script
require_once ('impr_img_text.php');

 // parameters
 $lineHeight  = 18;
 $imgWidth   = 300; // Breite des auszugebenden GIFs
 $fontsize = 8;

// Schriftfarbe f�r den Druck
 if($_REQUEST['print']){
	$textColor   = array (  0x00, 0x00, 0x00 );
	$bgColor    = array ( 0xff, 0xff, 0xff );

// Schriftfarbe f�r das Layout
}else{
		$textColor   = array (  0x00, 0x00, 0x00 );
	$bgColor    = array ( 0xff, 0xff, 0xff );



}
 if ($_REQUEST['bold']){
    $font     = 'fonts/Lucida Sans Typewriter Bold.ttf';
	$fontsize = 11;
 }
 else
	$font     = 'fonts/Lucida Sans Regular.ttf';
 
 // get and prepare source-text-array
 $text_select = $_GET["t"];
 

 if ( $text_select !== "") {
	$neuer_text = wordwrap($impr[$text_select - 1],65);
  $img_text = split ( "\n", $neuer_text );

  // H�he des GIFs berechnen
  $imgHeight = count ( $img_text )*$lineHeight;
  $img = ImageCreate( $imgWidth, $imgHeight );
  $_bc = ImageColorAllocate ( $img, $bgColor[0], $bgColor[1], $bgColor[2] );
  $_tc = ImageColorAllocate ( $img, $textColor[0], $textColor[1], $textColor[2] );
  imagecolortransparent ( $img, 0 );
  
  $z = 1;
  for ( $i=0; $i < count ($img_text); $i++) {
   //$img_text[$i]=substr ( $img_text[$i], 0, strlen ( $img_text[$i] ) -1 ) ;
   $img_text[$i]=preg_replace( "/[\s]+$/", "", $img_text[$i]);
   //Imagestring($img, 2, 1, $z, $img_text[$i], $_tc);
   $z += $lineHeight;
   ImageTTFText ($img, $fontsize, 0, 1, $z, $_tc, $font, $img_text[$i]);
   //imagettftext ( $img, 11, 0, 1, $z, $_tc, $font, $line );
  }
  ImageGIF ( $img );
   ImageDestroy($img);
 }
 

?>



