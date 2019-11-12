<!DOCTYPE html>
<html>
<head>
</head>
<body>

<form method="get">
<div id="choice" class="dropdown" name="choice">
                  <div class="dropbtn">Choices</div>
                  <div class="dropdown-content" id="choices" name="choices">
<input name="test[]" type="checkbox"></a>
<input name="test[]" type="checkbox">
<input name="test[]" type="checkbox">
<input name="test[]" type="checkbox">
</div>
</div>

<input name="choice[]" type="checkbox">
<input name="choice[]" type="checkbox">

<input type="submit" value="Submit">

</form>

<?php


// if form method="post", you would use $_POST["test"] to get the values
$test = $_GET["test"];
echo "<br>";

// if form method="post", you would use $_POST["test"] to get the values
$choices = $_GET["choices"];


echo count($test);


echo count($choices);


?>


</body>
</html>