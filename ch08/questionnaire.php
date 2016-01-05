<?php
    require_once('appvars.php');
    require_once('connectvars.php');
?>

<?php
    session_start();
?>

<?php
    $page_title = 'Questionnaire';
    require_once('header.php');

    if (!isset($_SESSION['user_id'])){
        echo '<p class="login">Please <a href="login.php">Log In</a> to access this page.</p>';
        exit();
    }
    require_once('navmenu.php');
?>

<?php
    $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME)
            or die("Connect Database failed.");

    $query = "SELECT * from mismatch_response"
            ." WHERE user_id=".$_SESSION['user_id'];
    $data  = mysqli_query($dbc,$query)
            or die($query);

    /* init user-id to response*/
    if (mysqli_num_rows($data) == 0){
        $query = 'SELECT topic_id from mismatch_topic ORDER BY category,topic_id';
        $data_topic = mysqli_query($dbc,$query)
                or die('Query failed for mismatch_topic');
        
        $topic_ids = array();
        while ($row = mysqli_fetch_array($data_topic)){
            array_push($topic_ids,$row['topic_id']);
        }

        foreach ($topic_ids as $topic_id){
            $query = "INSERT INTO mismatch_response(user_id,topic_id)"
                    ."VALUES('".$_SESSION['user_id']."'"
                    .",'".$topic_id."')";
            mysqli_query($dbc,$query)
                or die("INSERT INTO mismatch_response failed.");
        }

    }

    $query = "SELECT response_id,topic_id,response FROM mismatch_response "
            ."WHERE user_id=".$_SESSION['user_id'];
    $data = mysqli_query($dbc,$query)
            or die($query);

    $responses = array();

    while ($row=mysqli_fetch_array($data)){
        $query = "SELECT name,category FROM mismatch_topic "
                ."WHERE topic_id=".$row['topic_id'];
        $data2 = mysqli_query($dbc,$query)
                or die($query);
        $row2 = mysqli_fetch_array($data2);
        $row['topic_name'] = $row2['name'];
        $row['topic_category'] = $row2['category'];
        array_push($responses,$row);
    }

    mysqli_close($dbc);
?>
<?php
    echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
    echo '<p>How do you feel about each topic?</p>';

    /* loop tag.*/
    $category = $responses[0]['topic_category'];
    echo '<fieldset><legend>'.$category.'</legend>';
    foreach($responses as $response){
        if ($category != $response['topic_category']){
            $category = $response['topic_category'];
            echo '</fieldset><fieldset><legend>'.$category.'</legend>';
        }
        $label_id = $response['topic_id'];
        echo '<label for="'.$label_id.'">'.$response['topic_name'].':</label>';
        echo '<input type="radio" id="'.$label_id.'" name="'.$label_id.'" value="1"/>Love ';
        echo '<input type="radio" id="'.$label_id.'" name="'.$label_id.'" value="2"/>Hate </br>';
    }
    echo '</fieldset>';
    echo '<input type="submit" value="Save Questionnaire" name="submit">';
    echo '</form>';
?>
<?php
    require_once('footer.php');
?>

