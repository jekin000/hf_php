<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Guitar Wars - Remove a High Score</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <h2>Guitar Wars - Remove a High Score</h2>

<?php
    require_once('appvars.php');
    require_once('connectvars.php');

    // Receive data from href 
    if (isset($_GET['id'])
        && isset($_GET['date'])
        && isset($_GET['name'])
        && isset($_GET['score'])
        && isset($_GET['screenshot'])) 
    {
        $id     = $_GET['id'];
        $date   = $_GET['date']; 
        $score  = $_GET['score'];
        $screenshot = $_GET['screenshot'];
    }
    // Submit data to DB
    else if (isset($_POST['id'])
        && isset($_POST['name'])
        && isset($_POST['score'])) 
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $score = $_POST['score'];
    }
    else {
        echo '<p class="error"> Sorry , no high score was specified for removal.</p>';
    }

    if (isset($_POST['submit'])) {
        if ($_POST['confirm'] == true){
            @unlink(GW_UPLOADPATH.$screenshot);
            $dbc = mysqli_connect(DB_HST,DB_USR,DB_PWD,DB_NAM)
                or die("connect db failed.");
            $query = "SELECT * FROM guitarwars WHERE id=$id LIMIT 1";
            mysqli_query($dbc,$query);
            mysqli_close($dbc);

            echo '<p>The high score of '.$score.' for '.$name.'was successfully removed.</p>';
        }
        else {
            echo '<p class="error">The high score was not removed</p>';
        }
    }
    else if (isset($id)
        && isset($name)
        && isset($date)
        && isset($score)
        && isset($screenshot))
    {
        echo '<p>Are you sure you want to delete the following high score?</p>';
        echo '<p>'
            .'<strong>Name:</strong>'.$name.'</br>'
            .'<strong>Date:</strong>'.$date.'</br>'
            .'<strong>Score:</strong>'.$score.'</br>'
            .'</p>';
        echo '<form method="post" action="removescore.php">';
        echo '</form>';
    }
?>

</body>
</html>

