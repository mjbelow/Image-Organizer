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

$valid_user=false;
$exists=false;
$username="";

// sign up
if($_POST["method"] == 0)
{
  $username=$_POST["login_name"];
  $query = "select count(0) from user where lower(name)=lower('".$username."')";


  if($stmt = $con->prepare($query))
  {
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if($count == 0)
    {
      $con->prepare("insert into user values('".$username."','".hash("sha256", $_POST["login_pass"])."')")->execute();
      $_SESSION["username"] = $username;
      $_SESSION["password"] = hash("sha256", $_POST["login_pass"]);
      if($_POST["remember"])
      {
        setcookie("username", $username, time() + (86400 * 30), "/");
        setcookie("password", hash("sha256", $_POST["login_pass"]), time() + (86400 * 30), "/");
      }
      $valid_user=true;
    }
  }
}
// log in (manually: when user submits login form)
elseif($_POST["method"] == 1)
{
  $username=$_POST["login_name"];
  $query = "select count(0) from user where lower(name)=lower('".$username."') and pass='".hash("sha256", $_POST["login_pass"])."'";


  if ($stmt = $con->prepare($query))
  {
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if($count == 1)
    {
      $_SESSION["username"] = $username;
      $_SESSION["password"] = hash("sha256", $_POST["login_pass"]);
      if($_POST["remember"])
      {
        setcookie("username", $username, time() + (86400 * 30), "/");
        setcookie("password", hash("sha256", $_POST["login_pass"]), time() + (86400 * 30), "/");
      }
      $valid_user=true;
    }
  }
}
// log in (automatically: when page loads)
// or
// when user modifies options for their account (still have to verify or else they could just change their cookies to a different username and do whatever they want to other accounts)
else
{
  if(isset($_SESSION["username"]) && isset($_SESSION["password"]))
  {
    $username=$_SESSION["username"];
    $query = "select count(0) from user where lower(name)=lower('".$username."') and pass='".$_SESSION["password"]."'";
    $exists=true;
  }
  elseif(isset($_COOKIE["username"]) && isset($_COOKIE["password"]))
  {
    $_SESSION["username"] = $_COOKIE["username"];
    $_SESSION["password"] = $_COOKIE["password"];
    $username=$_COOKIE["username"];
    $query = "select count(0) from user where lower(name)=lower('".$username."') and pass='".$_COOKIE["password"]."'";
    $exists=true;
  }

  if ($exists && ($stmt = $con->prepare($query)))
  {
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if($count == 1)
    {
      $valid_user=true;
    }
  }
}


$index=array();
$categories = array();
$choices = array();
$debug="";

// if login credentials are wrong, or if the user was trying to sign up, don't build arrays for the user)
if(!$valid_user || $_POST["method"] == 0)
{
  echo json_encode(array($index, $categories, $choices, $debug, $valid_user, $username));
  $con->close();
  exit();
}


// if user is modifying categories and choices
if(isset($_POST["modify"]))
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

echo json_encode(array($index, $categories, $choices, $debug, $valid_user, $username));

$con->close();
?>