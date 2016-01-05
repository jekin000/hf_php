<?php
    require_once('appvars.php');
    require_once('connectvars.php');
?>

<?php
    session_start();
?>

<?php
    $error_msg = "";

    if (!isset($_SESSION['user_id'])){
        if (isset($_POST['submit'])){
            $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME)
                    or die("Can not connect to Database.");

            $user_name      = mysqli_real_escape_string($dbc,trim($_POST['username']));
            $user_password  = mysqli_real_escape_string($dbc,trim($_POST['password']));
            
            if (!empty($user_name) && !empty($user_password)){
                $query = "SELECT user_id,username FROM mismatch_user WHERE username='$user_name'"
                    ."AND password=SHA('$user_password')";
                $data = mysqli_query($dbc,$query);

                if (mysqli_num_rows($data) == 1){
                    $row = mysqli_fetch_array($data);
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $row['username'];
                    setcookie('user_id',$row['user_id'],time()+3600); 
                    setcookie('username',$row['username'],time()+3600);

                    $home_url = MM_HOME_URL;
                    header('Location: '.$home_url);
                }
                else {
                    $error_msg = "Sorry , you must enter a valid username or password.";
                }
            }
            else{
                $error_msg = "Sorry , you must enter your username and password.";
            }
        }
   }
?>

<html>
    <head>
        <title>Mismatch - Log In</title>
        <link rel="stylesheet" type="text/css" href="style.css"/>
    </head>
    <body>
        <h3>Mismatch - Log In</h3>
        <?php 
            if(empty($_SESSION['user_id'])){
                echo '<p class="error">'.$error_msg.'</p>'
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <fieldset>
                <legend>Log In</legend>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" 
                    value="<?php if(!empty($user_name)) echo $user_name; ?>" /></br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="" /></br>
            </fieldset>
            <input type="submit" value="Log In" name="submit">
        </form>
        <?php
            }
            else{
                echo '<p class="login">You are logged in as '.$_SESSION['username'].'.</p>';
            }
        ?>
    </body>
</html>




