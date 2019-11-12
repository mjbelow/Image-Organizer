<?php
$host="127.0.0.1";
$port=3306;
$socket="";
$user="c2375a05";
$password="!c2375aU!";
$dbname="c2375a05test";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

$username='mjbelow';
$user_password='pass';
//////////////////////////////////////////////////////////////////////////////
if($_POST["modify"])
{
  if($_POST["action"]=="create")
  {
    // new category
    if($_POST["option"] == "category")
    {
      // increase category positions above where new category is inserted
      $con->prepare
      (
        "update " . $_POST["option"] . 
        " set id=id+1" .
        " where id >= " . $_POST["position"] . " and lower(username) = lower('".$username."')" .
        " order by id desc"
      )->execute();
      // insert new category
      $con->prepare
      (
        "insert into " . $_POST["option"] .
        " values (" . $_POST["position"] . ", '" . $_POST["name"] . "', lower('".$username."'))"
      )->execute();
    }
    // new choice
    else
    {
      // increase choice positions above where new choice is inserted
        $debug = "update " . $_POST["option"] . 
        " set id=id+1" .
        " where id >= " . $_POST["position"] . " and category_id=" . $_POST["category_id"] . " and lower(username) = lower('".$username."')" .
        " order by id desc;";
      $con->prepare
      (
        "update " . $_POST["option"] . 
        " set id=id+1" .
        " where id >= " . $_POST["position"] . " and category_id=" . $_POST["category_id"] . " and lower(username) = lower('".$username."')" .
        " order by id desc"
      )->execute();
      // insert new category
        $debug .= "insert into " . $_POST["option"] .
        " values (" . $_POST["position"] . ", '" . $_POST["name"] . "', " . $_POST["category_id"] . ", lower('".$username."'));";
      $con->prepare
      (
        "insert into " . $_POST["option"] .
        " values (" . $_POST["position"] . ", '" . $_POST["name"] . "', " . $_POST["category_id"] . ", lower('".$username."'))"
      )->execute();
    }
  }
  else if($_POST["action"]=="update")
  {
    // update category
    if($_POST["option"] == "category")
    {
      // change name to user's input
      // set id to -1 so we can alter the other id's with no conflicts
      $con->prepare
      (
        "update " . $_POST["option"] .
        " set id=-1, name='" . $_POST["name"] . "'" .
        " where id=" . $_POST["category_id"] . " and lower(username) = lower('".$username."')"
      )->execute();

      //decrease categories above position by 1
      if($_POST["position"] > $_POST["category_id"])
      {
        $con->prepare
        (
          "update " . $_POST["option"] .
          " set id= id-1" .
          " where id >= " .  $_POST["category_id"] . " and id <= " . $_POST["position"] . " and lower(username) = lower('".$username."')" .
          " order by id asc"
        )->execute();
      }
      //increase categories below position by 1
      else
      {
        $con->prepare
        (
          "update " . $_POST["option"] .
          " set id= id+1" .
          " where id <= " .  $_POST["category_id"] . " and id >= " . $_POST["position"] . " and id != -1" . " and lower(username) = lower('".$username."')" .
          " order by id desc"
        )->execute();
      }

      // change id to user's input
      $con->prepare
      (
        "update " . $_POST["option"] .
        " set id=" . $_POST["position"] .
        " where id=-1" . " and lower(username) = lower('".$username."')"
      )->execute();
    }
    // update choice
    else
    {
      // change name to user's input
      // set id to -1 so we can alter the other id's with no conflicts
      $con->prepare
      (
        "update " . $_POST["option"] .
        " set id=-1, name='" . $_POST["name"] . "'" .
        " where id=" . $_POST["choice_id"] . " and category_id=" . $_POST["category_id"] . " and lower(username) = lower('".$username."')"
      )->execute();
      
      //decrease choices above position by 1
      if($_POST["position"] > $_POST["choice_id"])
      {
        $con->prepare
        (
          "update " . $_POST["option"] .
          " set id= id-1" .
          " where id >= " .  $_POST["choice_id"] . " and id <= " . $_POST["position"] . " and category_id=" . $_POST["category_id"] . " and lower(username) = lower('".$username."')" .
          " order by id asc"
        )->execute();
      }
      //increase choices below position by 1
      else
      {
        $con->prepare
        (
          "update " . $_POST["option"] .
          " set id= id+1" .
          " where id <= " .  $_POST["choice_id"] . " and id >= " . $_POST["position"] . " and category_id=" . $_POST["category_id"] . " and id != -1" . " and lower(username) = lower('".$username."')" .
          " order by id desc"
        )->execute();
      }

      // change id to user's input
      $con->prepare
      (
        "update " . $_POST["option"] .
        " set id=" . $_POST["position"] .
        " where id=-1" . " and lower(username) = lower('".$username."')"
      )->execute();
    }

  }
  else
  {
    // delete category
    if($_POST["option"] == "category")
    {
      $con->prepare
      (
        "delete from " . $_POST["option"] .
        " where id=" . $_POST["category_id"] . " and lower(username) = lower('".$username."')"
      )->execute();
      $con->prepare
      (
        "update " . $_POST["option"] . 
        " set id=id-1" .
        " where id >= " . $_POST["category_id"] . " and lower(username) = lower('".$username."')" .
        " order by id asc"
      )->execute();
    }
    // delete choice
    else
    {
      $con->prepare
      (
        "delete from " . $_POST["option"] .
        " where id=" . $_POST["choice_id"] . " and category_id=" . $_POST["category_id"] . " and lower(username) = lower('".$username."')"
      )->execute();
      $con->prepare
      (
        "update " . $_POST["option"] . 
        " set id=id-1" .
        " where id >= " . $_POST["choice_id"] . " and category_id=" . $_POST["category_id"] . " and lower(username) = lower('".$username."')" .
        " order by id asc"
      )->execute();
    }
  }

}
// sql query to build index
$query = "select category, choice, count from my_index where lower(username) = lower('".$username."')";

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
$query = "select id, category, choice from my_options where lower(username) = lower('".$username."')";

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

echo json_encode(array($index, $categories, $choices, $debug));

$con->close();
?>