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
    echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
    echo '<p>How do you feel about each topic?</p>';
    echo '<fieldset><legend>Hobby</legend>';
    echo '<label for="2">Movie</label>';
    echo '<input type="radio" id="2" name="2" value="1"/>Love ';
    echo '<input type="radio" id="2" name="2" value="2"/>Hate ';
    echo '</fieldset>';
    
    echo '<input type="submit" value="Save Questionnaire" name="submit">';
    echo '</form>';
?>
<?php
    require_once('footer.php');
?>

