<?php
$host="127.0.0.1";
$port=3306;
$socket="";
$user="c2375a05";
$password="!c2375aU!";
$dbname="c2375a05test";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

// index to store values needed for interactive menu
//$index = array();

$username='mjbelow';

// sql query to build index
$query = "select category, choice, count from my_index where lower(username)=lower('".$username."')";

$index=array();

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($category, $choice, $count);
    while ($stmt->fetch()) {

      // hacky solution . " " so because we need a string for this value so it can convert to BigInt easily
      // if we don't add a space, javascript automatically interprets it as a number_format
      $index[$category][$choice . " "] = $count;

    }
    $stmt->close();
}

$categories = array();
$choices = array();

// sql query to build menu
$query = "select id, category, choice from my_options where lower(username)=lower('".$username."')";

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($id, $category, $choice);
    while ($stmt->fetch()) {
      
      $categories[$id]=$category;
      
      if(!isset($choices[$id])) {
        
        $choices[$id] = array();
        
      }

      if($choice)
        array_push($choices[$id], $choice);
      
    }
    $stmt->close();
}


echo json_encode(array($index, $categories, $choices));


$con->close();

?>