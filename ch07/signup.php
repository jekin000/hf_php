<?php
    require_once('appvars.php');
    require_once('connectvars.php');

    $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME)
            or die("Can not connect to Database.");

    if (isset($_POST['submit'])){
        $username = mysqli_real_escape_string($dbc,trim($_POST['username']));
        $password1 = mysqli_real_escape_string($dbc,trim($_POST['password1']));
        $password2 = mysqli_real_escape_string($dbc,trim($_POST['password2']));

        if (!empty($username) && !empty($password1) && !empty($password2)
                && ($password1==$password2)){
            $query = "SELECT * FROM mismatch_user WHERE username='$username'";
            $data = mysqli_query($dbc,$query)
                or die('query failed.');
            if (mysqli_num_rows($data) == 0){
                $query = 'INSERT INTO mismatch_user(username,password,join_date)'
                        ."VALUES('$username',SHA('$password1'),NOW())";
                mysqli_query($dbc,$query)
                        or die('Insert into database failed.');
                echo '<p>Your new account has been successfully created. You\'re now'.
                       'ready to log in and '.'<a href="editprofile.php">edit your profile</a>. </p>';
            }
            else{
                echo '<p class="error">Username exists, please input a different username.</p>';
            }
        }
        else{
            echo '<p class="error"> You must enter all of the sign-up data, including the desired password twice.</p>';
        }
    }
    mysqli_close($dbc);
?>


<p>Please enter your Username and Password.</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
    <fieldset>
        <legend>Registration Info</legend>
        <label for="username">Username: </label>
        <input type="text" id="username" name="username"
        value="<?php if(!empty($username)) echo $username; ?>"></br>
        <label for="password1">Password:</label>
        <input type="password" id="password1" name="password1"/></br>
        <label for="password2">Password(retry):</label>
        <input type="password" id="password2" name="password2"/></br>
    </fieldset>
    <input type="submit" value="Sign Up" name="submit">
</form>
