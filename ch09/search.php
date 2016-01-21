<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Risky Jobs - Search</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <img src="riskyjobs_title.gif" alt="Risky Jobs" />
  <img src="riskyjobs_fireman.jpg" alt="Risky Jobs" style="float:right" />
  <h3>Risky Jobs - Search Results</h3>

<?php
function build_query($user_search)
{
   // Query to get the results
  $query = "SELECT * FROM riskyjobs ";
  $where_clause = '';

  $clean_search = str_replace(',',' ',$user_search);
  $search_words = explode(' ',$clean_search);
  $final_search_words = array();

  if (count($search_words) > 0)
    foreach ($search_words as $word){
        if (!empty($word)){
            $final_search_words[] = $word;
        }
  }

  $where_list = array();
  if (count($final_search_words) > 0){
    foreach($final_search_words as $word){
        $where_list[] = "description LIKE '%$word%'";
    }
  }  

  $where_clause = implode(' OR ',$where_list);
 
  if (!empty($where_clause)){
    $query .= "WHERE $where_clause";
  }
  return $query;
       
}
?>

<?php
function template_link($sort_num,$href_name,$user_search)
{
    $sort_links = '<td><a href="'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search
        .'&sort='.$sort_num.'">'.$href_name.'</a></td>';

    return $sort_links;
}

function generate_case_sort_links($job,$state,$date_posted,$user_search)
{
    $sort_links = '';
    $sort_links .= template_link($job,'Job Title',$user_search);
    $sort_links .= '<td>Description</td>';
    $sort_links .= template_link($state,'State',$user_search);
    $sort_links .= template_link($date_posted,'Date Posted',$user_search);
    return $sort_links;    
}

function generate_sort_links($user_search,$sort)
{
    $sort_links = '';
    switch ($sort){
        case 1:
            $sort_links = generate_case_sort_links(2,3,5,$user_search);
            break;
        case 3:
            $sort_links = generate_case_sort_links(1,4,5,$user_search);
            break;
        case 5:
            $sort_links = generate_case_sort_links(1,3,6,$user_search);
            break;
        default:
            $sort_links = generate_case_sort_links(1,3,5,$user_search);
    }
    return $sort_links;
}

?>

<?php
  // Grab the sort setting and search keywords from the URL using GET
  $sort = $_GET['sort'];
  $user_search = $_GET['usersearch'];

 // Start generating the table of results
  echo '<table border="0" cellpadding="2">';

  // Generate the search result headings
  echo '<tr class="heading">';
  echo generate_sort_links($user_search,$sort);
  echo '</tr>';

  // Connect to the database
  require_once('connectvars.php');
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die("Connect DB failed.");
  
  $query = build_query($user_search);
  $result = mysqli_query($dbc,$query )
        or die($query);
 
  while ($row = mysqli_fetch_array($result)) {
    echo '<tr class="results">';
    echo '<td valign="top" width="20%">' . $row['title'] . '</td>';
    echo '<td valign="top" width="50%">' . substr($row['description'],0,100) . '...</td>';
    echo '<td valign="top" width="10%">' . $row['state'] . '</td>';
    echo '<td valign="top" width="20%">' . substr($row['date_posted'],0,10) . '</td>';
    echo '</tr>';
  } 
  echo '</table>';

  mysqli_close($dbc);
?>

</body>
</html>
