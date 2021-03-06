<?php
    require_once('appvars.php');
    require_once('connectvars.php');
?>

<?php
    session_start();
?>

<?php
    $page_title = 'My Mismatch';
    require_once('header.php');

    if (!isset($_SESSION['user_id'])){
        echo '<p class="login">Please <a href="login.php">Log In</a> to access this page.</p>';
        exit();
    }
    require_once('navmenu.php');
?>

<?php
    $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME)
        or die("Connect DB failed.");
?>

<?php
    /* get user data*/
    $query = "SELECT mr.response_id, mr.response,mt.name"
            ." FROM mismatch_response as mr"
            ." INNER JOIN mismatch_topic as mt USING(topic_id)"
            ." WHERE mr.user_id = " . $_SESSION['user_id']
            ." ORDER BY mr.topic_id ASC";
    $data = mysqli_query($dbc,$query)
            or die($query);

    $user_response = array();

    while ($row = mysqli_fetch_array($data)) {
        array_push($user_response,$row);
    }
?>

<?php
    /* result data*/
    $mismatch_score = 0;
    $mismatch_user_id = -1;
    $mismtach_topic   = array();
?>

<?php
    /* all other user id */
    $query = "SELECT user_id FROM mismatch_user WHERE user_id<>"
            .$_SESSION['user_id'];
    $data = mysqli_query($dbc,$query)
            or die($query);

    $user_ids = array();
    while ($row=mysqli_fetch_array($data)){
        array_push($user_ids,$row['user_id']);
    }
?>

<?php
    /* search for all other mismatcher.*/
    foreach ($user_ids as $id) {
        $score = 0;
        $topics = array();
        $response = array();

        $query = "SELECT mr.response_id,mr.response,mt.name "
                ."FROM mismatch_response as mr "
                ."INNER JOIN mismatch_topic as mt "
                ."USING(topic_id) "
                ."WHERE mr.user_id=".$id
                ." ORDER BY mr.topic_id ASC";
        $data = mysqli_query($dbc,$query)
                or die($query);

        if (mysqli_num_rows($data) == 0){
            continue;
        }

        while ($row=mysqli_fetch_array($data)){
            array_push($response,$row);
        }

        for ($i=0; $i<count($user_response); $i++){
            if ($user_response[$i]['response']+$response[$i]['response']==3){
                $score++;
                array_push($topics,$response[$i]['name']);
            }
        }

        if ($score > $mismatch_score){
            $mismatch_score = $score;
            $mismatch_topic = array_slice($topics,0);
            $mismatch_user_id = $id;
        }
    }
?>

<?php
    /* print result.*/ 
    if ($mismatch_user_id == -1){
        echo 'Sorry, no mismatcher.<br /> ';
        echo 'If you do not finish the <a href="questionnaire.php">Questionnaire</a>,we suggest you finish it first.';
    }
    else{
        $query = "SELECT username,first_name,last_name,city,state,picture FROM mismatch_user"
                ." WHERE user_id=".$mismatch_user_id;
        $data = mysqli_query($dbc,$query)
            or die($query);
        if (mysqli_num_rows($data) !=1){
            echo 'Find wrong mismatcher, query data='.mysqli_num_rows($data);
        }
        else{
            $row = mysqli_fetch_array($data);
            echo '<table><tr><td class="label">';
            if (!empty($row['first_name']) && !empty($row['last_name'])){
                echo $row['first_name'].' '.$row['last_name'].'<br />';
            }
            if (!empty($row['city']) && !empty($row['state'])){
                echo $row['city'].' '.$row['state'].'<br />';
            }

            echo '</td><td>';
            if (!empty($row['picture'])){
                echo '<img src="'.MM_UPLOADPATH.$row['picture'].'" alt="Profile Picture" /><br />';
            }
            echo '</td><tr></table>';

            echo '<h4>You are mismatched on the following '.count($mismatch_topic).' topics:</h4>';
            foreach ($mismatch_topic as $tpc){
                echo $tpc.'<br />';
            }
            echo '<h4>View <a href=viewprofile.php?user_id='.$mismatch_user_id.'>'.$row['first_name'].'\'s Profile</a>.</h4>';
        }
    }
?>

<?php
    mysqli_close($dbc);
?>

<?php
    require_once('footer.php');
?>

