<?php
    $email = $_POST['email'];
    
    $dbc = mysqli_connect('localhost','root','root','elvis_store')
        or die('Can not connect to database.');

    $query = "DELETE FROM email_list WHERE email='$email'";
    mysqli_query($dbc,$query)
        or die('Query Database failed.');
    echo "Delete the Email:'$email'";
    mysqli_close($dbc);

?>
