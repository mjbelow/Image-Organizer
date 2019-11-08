<?php

/*
echo "<div style='color:#fff'>";
echo $_POST["option"];
echo "<br>";
echo $_POST["action"];
echo "<br>";
echo $_POST["category"];
echo "<br>";
echo $_POST["choice"];
echo "<br>";
echo $_POST["position"];
echo "<br>";
echo $_POST["name"];
echo "</div>";
*/

/*
delete from category where id = 1;
update category set id = id-1
where id > 1;




-- after delete
update category
set id = id-1
order by id asc;

-- before insert
update category
set id = id+1
order by id desc;


-- create (example)
update category
set id = id+1
where id >= 2
order by id desc;
insert into category values (2, "Space");

-- delete (example)
delete from category where id = 2;
update category
set id = id-1
where id >= 2
order by id asc;



-- update (example)
update category
set name='Space'
where id=3;
*/


echo "<div style='color:#fff'>";



if($_POST["action"]=="create")
{
  echo "Creating option<hr>";
  $query = "update " . $_POST["option"] . 
  " set id=id+1" .
  " where id >= " . $_POST["position"] .
  " order by id desc;" .
  "<br>" .
  "insert into " . $_POST["option"] .
  " values (" . $_POST["position"] . ", '" . $_POST["name"] . "');";
}
else if($_POST["action"]=="update")
{
  echo "Updating option<hr>";
  $query = "update " . $_POST["option"] .
    " set name='" . $_POST["name"] . "'" .
    " where id=" . $_POST["position"] . ";";
}
else
{
  echo "Deleting option<hr>";
  $query = "delete from " . $_POST["option"] .
  " where id=" . $_POST["position"] . ";" . 
  "<br>" .
  "update " . $_POST["option"] . 
  " set id=id-1" .
  " where id >= " . $_POST["position"] .
  " order by id desc;";
}


  echo $query;

echo "</div>";
?>