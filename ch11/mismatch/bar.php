<?php
function draw_bar_graph($width,$height,$data,$max_value,$filename)
{
    /*1. Create the empty graph image*/
    $img = imagecreatetruecolor($width,$height);

    /*2. set a white background with black text and gray graphic*/
    $bg_color = imagecolorallocate($img,255,255,255); //white
    $text_color = imagecolorallocate($img,255,255,255);
    $bar_color = imagecolorallocate($img,0,0,0);   //black
    $border_color = imagecolorallocate($img,192,192,192);  //light gray
    
    /*3. fill the background */
    imagefilledrectangle($img,0,0,$width,$height,$bg_color);

    /*4. Draw the bars */
    $bar_width = $width/(count($data)*2+1);
    for ($i=0; $i<count($data); $i++){
        imagefilledrectangle($img,$i*$bar_width*2+$bar_width,$height
            ,$i*$bar_width*2+$bar_width*2,$height-$height/$max_value*$data[$i][1],$bar_color);
        imagestringup($img,5,$i*$bar_width*2+$bar_width,$height-5,$data[$i][0],$text_color);
    }
    /*5. Draw a rectangle around the whole thing*/
    imagerectangle($img,0,0,$width-1,$height-1,$border_color);

    /*6. Draw the range up the left side of graph*/
    for ($i=1; $i<=$max_value; $i++){
        imagestring($img,5,0,$height-($i*($height/$max_value)),$i,$bar_color);
    }

    /*7. output image to a file.*/
    imagepng($img,$filename,5);

    /*8. destroy image.*/
    imagedestroy($img);
}

?>
