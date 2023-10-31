<?php
include '../includes/connection.php';
include "../includes/sessions.php";
include "../includes/setupUserData.php";
include '../includes/navbar.php';
//query is the sql statement that runs
$query = "SELECT * FROM businesses;";
echo "<b> <center>Latest posts</center> </b> <br> <br>";
//the database you got earlier, run that sql command
if ($result = $mysqli->query($query)) {
    //not exactly sure, but it gets each row of the database and saves them to temp vars
    while ($row = $result->fetch_assoc()) {
        $field1name = $row["business_id"];
        $field2name = $row["name"];
        $field3name = htmlspecialchars_decode($row["description"], ENT_QUOTES);
        $field4name = $row["category"];
        $field5name = $row["address"];
        $field6name = $row["phone"];
        $field7name = $row["website"];
        //then we can display those vars below however we want
        echo '<div class="c"> <h2> <center>'.$field2name. '</center></h2>';
        echo ' <h3> <center>'.$field4name. ' in ' . $field5name . '</center></h3>';
        echo ' <h5> <center>'.$field2name. '</center></h5>';
        echo ' <center>Contact: '. $field6name . ', <a href="' . $field7name . '">' . $field7name . '</a> </center></div>';
    }

/*freeresultset*/
$result->free();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>
    <link href='styles.css' rel='stylesheet'>
    <style>
        .c {
  display: inline-block;
  width: 25%;
  height:  25%;
  padding: 5px;
  border: 1px solid blue;    
}
    </style>
</head>
<body>
    
</body>
</html>