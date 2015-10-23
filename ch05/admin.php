<?php
    require_once('appvars.php');
    require_once('connectvars.php');

    $dbc = mysqli_connect(DB_HST,DB_USR,DB_PWD,DB_NAM)
        or die("Can not connect to database.");
    $query = "SELECT * FROM guitarwars ORDER BY score DESC, date ASC";
    $data = mysqli_query($dbc, $query)
        or die("Query failed.");
    
    echo '<table>';
    while ($row=mysqli_fetch_array($data)){
        echo '<tr class="scorerow"><td><strong>'.$row['name'].'</strong></td>';
        echo '<td>'.$row['date'].'</td>';
        echo '<td><a href="removescore.php?id='.
                $row['id'].
                '&amp;data='.$row['date'].
                '&amp;name='.$row['name'].
                '&amp;score='.$row['score'].
                '&amp;screenshot='.$row['screenshot'].
                '">Remove</a></td></tr>';
    }
    echo '</table>';    
    mysqli_close($dbc);
?>
