<!DOCTYPE html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<script>
<?php
  session_start();

  // function to generate file name: [0-9A-Za-z]{$n}
  function generate($n)
  {
    $arr = array();

    for($i = 0; $i < $n; $i++)
    {
      $sym = mt_rand(0, 61);
      if($sym < 10)
        $sym += 48;
      else if($sym < 36)
        $sym += 55;
      else
        $sym += 61;

      array_push($arr, chr($sym));
    }

    return join("", $arr);
  }

  $username=$_SESSION["username"];
  $category = $_POST["category"];
  $choice = $_POST["choice"];
  $choice_count = count($choice);
  $valid = true;

  if(!$category)
  {
    $valid = false;
    $msg = "Please assign a category to the image";
  }
  elseif($choice_count == 0)
  {
    $valid = false;
    $msg = "Please assign choice(s) to the image";
  }
  else
  {
    $host="127.0.0.1";
    $port=3306;
    $socket="";
    $user="c2375a05";
    $password="!c2375aU!";
    $dbname="c2375a05proj";

    $con = new mysqli($host, $user, $password, $dbname, $port, $socket);
    
    if($con->connect_error)
    {
      $valid = false;
      $msg = "Sorry, could not connect to the database";
    }
    else
    {
      $file = $_FILES["fileToUpload"]["tmp_name"];
      $mime = mime_content_type($file);


      if($mime == "image/jpeg")
        $file_ext = ".jpg";
      elseif($mime == "image/png")
        $file_ext = ".png";
      elseif($mime == "image/gif")
        $file_ext = ".gif";
      elseif($mime == "image/webp")
        $file_ext = ".webp";
      else
      {
        $valid = false;
        $msg = "Invalid image file: must be a jpeg, png, gif, or webp file";
      }
    }

    if($valid)
    {

      $dir = "../../images/";
      $file_name = generate(10);
      $src = $dir . $file_name . $file_ext;


      // generate new name if file already exists
      while(file_exists($src))
      {
        $file_name = generate(10);
        $src = $dir . $file_name . $file_ext;
      }

      // prevent files larger than 1MB from being uploaded
      if($_FILES["fileToUpload"]["size"] > (1024*1024*1))
      {
        $valid = false;
        $msg = "Image size is too large to upload (1MB limit)";
      }

      if($valid)
      {
        if(move_uploaded_file($file, $src))
        {
          $query = "select '" . $file_name . $file_ext . "', category.id, choice.id, category.username " .
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

          $con->prepare("insert into image (".$query.")")->execute();
          $con->close();

        }
        else
        {
          $valid = false;
          $msg = "There was a problem uploading your file.";
        }
      }

    }

  }
  
  if($valid)
    echo "parent.start(4, null, null);"

?>

</script>
<div class="wrapper">

  <header>
    <h1>
      <?php
        if($valid)
          echo "Image Uploaded\n";
        else
          echo "File could not be uploaded\n";
      ?>
    </h1>
  </header>

  <?php
    if(!$valid)
    {
      echo "<div class='uploadResults'>" . $msg . "</div>\n" .
      "<div style='display: none;'>\n";
    }
    else
    {
      echo "<div class='uploadResults'>\n";
    }
  ?>

    <div class="title">Category</div>
    <ul class="options">
      <li>
        <?php echo $category . "\n"; ?>
      </li>
    </ul>

    <div class="title">Choices
    <?php
      echo " (" . $choice_count . ")</div>\n    <ul class='options'>\n";

      foreach($choice as $name)
      {
        echo "      <li>" . $name . "</li>\n";
      }

      echo "    </ul>";
    ?>


    <div class="title">Preview</div>
    <div class="preview">
    <?php
      if($valid)
        echo "  <img style='max-width: 300px; max-height: 300px;' src='" . $src . "'>\n";
    ?>
    </div>

  </div>

</div>

</body>
</html>