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
                array_push($topics,$response['name']);
            }
        }

        if ($score > $mismatch_score){
            $mismatch_score = $score;
            $mismatch_topic = array_slice($topics);
            $mismatch_user_id = $id;
        }
    }

    if ($mismatch_user_id > -1){
        echo 'Your mismatcher is '.$mismatch_user_id;
    }
    else{
        echo 'Sorry ,no mismatcher';
    }
?>

<?php
    mysqli_close($dbc);
?>


<?php
    require_once('footer.php');
?>

