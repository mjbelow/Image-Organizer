<!DOCTYPE html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<script>
<?php

  $host="127.0.0.1";
  $port=3306;
  $socket="";
  $user="c2375a05";
  $password="!c2375aU!";
  $dbname="c2375a05proj";

  $con = new mysqli($host, $user, $password, $dbname, $port, $socket)
    or die ('Could not connect to the database server' . mysqli_connect_error());

  $username=$_COOKIE["username"];

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

  echo "var my_name = '" . $username . "';\n";
  echo "var my_categories = JSON.parse('" . json_encode($categories) . "');\n";
  echo "var my_choices = JSON.parse('" . json_encode($choices) . "');\n";

?>
</script>
</head>
<body>
<form action="uploadcss.php" method="post" enctype="multipart/form-data" onreset="document.getElementById('preview').removeAttribute('src');">
  <div class="wrapper">
    <header>
      <h1>Upload Image</h1>
    </header>
    <div class="sections">

      <section class="active">
        <select id="category" onchange="update_choices()" name="category"></select>
        <div id="choice" class="dropdown" name="choice">
          <div class="dropbtn">Choices</div>
          <div class="dropdown-content" id="choices" name="choices"></div>
        </div>

        <div class="image-upload-wrap">

          <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/png,image/gif,image/jpeg,image/webp" id="fileToUpload" name="fileToUpload">

          <div class="drag-text">
            <img id="preview" alt="DRAG AND DROP OR CLICK TO BROWSE">
          </div>

        </div>
      </section>

      <section>
        <input type="text" placeholder="Topic" id="topic"/>
        <textarea placeholder="something..." id="msg"></textarea>
      </section>

    </div>

    <footer>
      <ul>
      <input class="button buttonReset" type="reset" value="Reset">
      <input class="button buttonReset" type="submit" value="Submit">
      </ul>
    </footer>
  </div>
</form>

<script>
var category = document.getElementById("category");
var choices = document.getElementById("choices");
var select = document.getElementById("category");

var category_count = my_categories.length;

for(var i = 0; i < category_count; i++)
{
  var opt = my_categories[i];
  var el = document.createElement("option");
  el.textContent = opt;
  el.value = opt;
  select.appendChild(el);
}

function update_choices()
{

  choices.innerHTML = "";
  var choice_count = my_choices[category.selectedIndex].length;

  for(var i = 0; i < choice_count; i++)
  {
    var checkBox = document.createElement("input");
    var a = document.createElement("a");
    checkBox.type = "checkbox";
    checkBox.value = my_choices[category.selectedIndex][i];
    checkBox.name = "choice[]";
    a.appendChild(checkBox);
    choices.appendChild(a);
    a.appendChild(document.createTextNode(my_choices[category.selectedIndex][i]));
  }

}

update_choices();

function readURL(input)
{
  if (input.files && input.files[0])
  {
    var reader = new FileReader();

    reader.onload = function(e)
    {
      document.getElementById("preview").src = e.target.result;
    }

    reader.readAsDataURL(input.files[0]);

  }
}
</script>

</body>
</html>