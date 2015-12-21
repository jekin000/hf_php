<?php
    require_once('authorize.php');
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Guitar Wars - Appove a Score</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <h2>Guitar Wars - Appove a Score</h2>


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
        $name   = $_GET['name'];
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
        echo '<p class="error"> Sorry , no Score was specified for Approved.</p>';
    }

    if (isset($_POST['submit'])) {
        if ($_POST['confirm'] == 'Yes'){
            $dbc = mysqli_connect(DB_HST,DB_USR,DB_PWD,DB_NAM)
                or die("connect db failed.");
            $query = "UPDATE guitarwars SET approved=1 WHERE id=$id";
            mysqli_query($dbc,$query)
                or die("query failed.");

            mysqli_close($dbc);

            echo '<p>The Score of '.$score.' for '.$name.' was successfully approved.</p>';
        }
        else {
            echo '<p class="error">The score was not approved</p>';
        }
    }
    else if (isset($id)
        && isset($name)
        && isset($date)
        && isset($score)
        && isset($screenshot))
    {
        echo '<p>Are you sure you want to approve the following high score?</p>';
        echo '<p>'
            .'<strong>Name:</strong>'.$name.'</br>'
            .'<strong>Date:</strong>'.$date.'</br>'
            .'<strong>Score:</strong>'.$score.'</br>'
            .'</p>';
        echo '<form method="post" action="approvescore.php">';
        echo '<input type="radio" name="confirm" value="Yes"/> Yes';
        echo '<input type="radio" name="confirm" value="No" checked="checked"/> No <br />';
        echo '<input type="submit" name="submit" value="Submit" />';
        echo '<input type="hidden" name="id" value="'.$id.'" />';
        echo '<input type="hidden" name="name" value="'.$name.'" />';
        echo '<input type="hidden" name="score" value="'.$score.'" />';
        echo '</form>';
    }
 
    echo '<p><a href=admin.php>&lt;&lt; Back to admin page</a></p>'
?>
</body>
</html>


