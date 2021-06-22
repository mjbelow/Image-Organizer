<?php
session_start();

$host="127.0.0.1";
$port=3306;
$socket="";
$user="root";
$password="";
$dbname="test";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

$username=$_SESSION["username"];
$category = $_POST["category"];
$img = $_POST["img_src"];

//mysqli_begin_transaction($con);
mysqli_autocommit($con, false);

$query = "delete from image where name='".$img."' and lower(username)=lower('".$username."')";
if($con->query($query))
{
  if(isset($_POST["choice_array"]))
  {

    $choice = json_decode($_POST["choice_array"]);
    $choice_count = count($choice);

    $query = "select '" . $img . "', category.id, choice.id, category.username " .
    "from category left join choice on ((category.id = choice.category_id) and (category.username = choice.username)) " .
    "where (lower(category.username) = lower('".$username."')) and (lower(category.name) = lower('".$category."')) and (";

    // build query using choices
    for($i = 0; $i < $choice_count; $i++)
    {
      if($i == ($choice_count - 1))
        $query .= "lower(choice.name) = lower('".$choice[$i]."')) and (";
      else
        $query .= "lower(choice.name) = lower('".$choice[$i]."') or ";
    }

    $query .= "true)";

    if($con->query("insert into image (".$query.")"))
    {
      echo true;
      // Commit transaction
      mysqli_commit($con);
    }
    else
    {
      echo false;
      // Rollback transaction
      mysqli_rollback($con);
    }

  }

  else
  {

    if(unlink("images/" . $_POST["img_src"]))
    {
      echo true;
      // Commit transaction
      mysqli_commit($con);
    }
    else
    {
      echo false;
      // Rollback transaction
      mysqli_rollback($con);
    }
    
  }

}
else
  echo false;

$con->close();


?>