<?php

// Set your string somehow 
$string = strrev($_GET['string']); 

// Set font size 
$font_size = 4; 

// Create image width dependant on width of the string 
$width  = imagefontwidth($font_size)*strlen($string); 
// Set height to that of the font 
$height = imagefontheight($font_size); 
// Create the image pallette 
$img = imagecreate($width,$height); 
// Grey background 
$bg    = imagecolorallocate($img, 255, 255, 255);
// Black font color 
$color = imagecolorallocate($img, 0, 0, 0); 
// Length of the string 
$len = strlen($string); 
// Y-coordinate of character, X changes, Y is static 
$ypos = 0; 
// Loop through the string 
for($i=0;$i<$len;$i++){ 
    // Position of the character horizontally 
    $xpos = $i * imagefontwidth($font_size); 
    // Draw character 
    imagechar($img, $font_size, $xpos, $ypos, $string, $color); 
    // Remove character from string 
    $string = substr($string, 1);    
    
} 
// Return the image 
header("Content-Type: image/gif"); 
imagegif($img); 
// Remove image 
imagedestroy($img); 

?>