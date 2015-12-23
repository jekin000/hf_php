<?php
    require_once('connectvars.php');
    
    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])){
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Basic realm=Mismatch');
        exit('<h2>Mismatch</h2>Sorry, you must enter your username and password'
            .' to login in and access this page.If you aren\'t a registered member,please <a href="signup.php">sign up</a>.'); 
    }

    $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME)
            or die('Connect DB failed.');

    $user_name      = mysqli_real_escape_string($dbc,trim($_SERVER['PHP_AUTH_USER']));
    $user_password  = mysqli_real_escape_string($dbc,trim($_SERVER['PHP_AUTH_PW']));

    $query = "SELECT user_id,username FROM mismatch_user WHERE username='$user_name'"
            ."AND password=SHA('$user_password')";
    $data = mysqli_query($dbc,$query);

    if (mysqli_num_rows($data) == 1){
        $row = mysqli_fetch_array($data);
        $user_id = $row['user_id'];
        $username = $row['username'];
    }
    else{
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Basic realm=Mismatch');
        exit('<h2>Mismatch</h2>Sorry, you must enter a valid username and password'
            .'to login in and access this page.'); 
    }

    echo('<p class="login">You are loggged in as '.$username . '.</p>');
?>
