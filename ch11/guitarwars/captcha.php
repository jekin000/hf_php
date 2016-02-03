<?php
    session_start();
    
    define('CAPTCHA_NUMCHARS'   ,6);
    define('CAPTCHA_WIDTH'      ,100);
    define('CAPTCHA_HEIGHT'     ,25);
    define('CAPTCHA_NUMLINES'   ,5);
    define('CAPTCHA_NUMDOTS'    ,50);

    $pass_phrase = '';
    //Generate the random pass-phrase
    for ($i=0; $i<CAPTCHA_NUMCHARS; $i++){
        $pass_phrase .= chr(rand(97,122));
    }    
    
    //Store the encrypted pass-phrase in a session variable
    $_SESSION['pass_phrase'] = sha1($pass_phrase);

    //Create the image
    $img = imagecreatetruecolor(CAPTCHA_WIDTH,CAPTCHA_HEIGHT);

    //Set a white background with black text and gray graphics
    $bg_color       = imagecolorallocate($img,255,255,255);
    $text_color     = imagecolorallocate($img,0,0,0);
    $graphic_color  = imagecolorallocate($img,64,64,64);

    //Fill the background
    imagefilledrectangle($img,0,0,CAPTCHA_WIDTH,CAPTCHA_HEIGHT,$bg_color);

    //Draw some random line
    for ($i=0; $i<CAPTCHA_NUMLINES; $i++){
        imageline($img,0,rand()%CAPTCHA_HEIGHT,CAPTCHA_WIDTH,rand()%CAPTCHA_HEIGHT,$graphic_color);
    }

    //Draw some random dot
    for ($i=0; $i<CAPTCHA_NUMDOTS; $i++){
            $x = rand()%CAPTCHA_WIDTH;
            $y = rand()%CAPTCHA_HEIGHT;
            //echo "dot($x,$y)<br />";
        imagesetpixel($img,$x,$y,$graphic_color);
    }


    //Draw the pass-phrase string
    imagettftext($img,18,0,5,CAPTCHA_HEIGHT-5,$text_color,'courbd.ttf',$pass_phrase);
    
    /*No font style*/
    /* imagestring($img,5,10,5,$pass_phrase,$text_color);*/

    //Output th image as a PNG using a header
    header('Content-type: image/png');
    imagepng($img);

    //Clean up
    imagedestroy($img);
?>
