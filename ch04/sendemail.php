<?php
    if (isset($_POST['submit'])) {
        $subject = $_POST['subject'];
        $text    = $_POST['elvismail'];
        $from    = 'kira_r164@163.com';
        $print_form = false;

        if (empty($subject) && empty($text)){
            echo 'You forget the email subject & body <br />';
            $print_form = true;
        }

        if (!empty($subject) && empty($text)){
            echo 'You forget the email body.<br />';
            $print_form = true;
        }

        if (empty($subject) && !empty($text)){
            echo 'You forget the email subject.<br />';
            $print_form = true;
        }

        if (!empty($subjet) && !empty($text)){
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
        }
    }
    else {
        $print_form = true;
    }
    if ($print_form){
?>            
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="subject">Subject of email:</label><br />
    <input id="subject" name="subject" type="text" size="30" 
        value="<?php echo $subject; ?>" /><br />
    <label for="elvismail">Body of email:</label><br />
    <textarea id="elvismail" name="elvismail" rows="8" cols="40"><?php echo $text; ?></textarea><br />
    <input type="submit" name="submit" value="Submit"/>
    </form>


<?php
    }
?>

