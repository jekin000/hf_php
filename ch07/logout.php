<?php
    require_once('appvars.php');
?>

<?php
    session_start();
?>

<?php
    if (isset($_SESSION['user_id'])){
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])){
            setcookie(session_name(),'',time()-3600);
        }

        session_destroy();
        setcookie('user_id','',time()-3600);
        setcookie('username','',time()-3600);
    }
 
    $home_url = MM_HOME_URL;
    header('Location: ' . $home_url);
?>
