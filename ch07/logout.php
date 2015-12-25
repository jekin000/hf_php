<?php
    require_once('appvars.php');
?>

<?php
    if (isset($_COOKIE['user_id'])){
        setcookie('user_id','',time()-3600);
        setcookie('username','',time()-3600);
    }
    else{
        echo '<p>no cookie.</p>';
    }
 
    $home_url = MM_HOME_URL;
    header('Location: ' . $home_url);
?>
