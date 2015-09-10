<?php
    $subject = $_POST['subject'];
    $text    = $_POST['elvismail'];
    $from    = 'kira_r164@163.com';
    $dbc = mysqli_connect('localhost','root','root','elvis_store')
        or die('Can not connect database.');

    $query = "SELECT * FROM email_list";
    
    $result = mysqli_query($dbc,$query)
        or die('Error querying database.');

    while ($row = mysqli_fetch_array($result)){
        $first_name = $row['first_name'];
        $last_name  = $row['last_name'];

        $message = "Dear $first_name $last_name,\n$text";
        mail($row['email'],$subject,$message,'From:' . $from);
        echo 'Email sent to: '. $first_name . '<br / >';
    }

    mysqli_close($dbc);
?>
