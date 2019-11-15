<!DOCTYPE html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php
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



  /*

  following PHP code generated using MySQL Workbench (IMPORTANT: $password has to be set or else it won't be able to connect to database):
  Tools > Utilities > Copy as PHP Code (Connect to Server)

  */
  /*
  $host="127.0.0.1";
  $port=3306;
  $socket="";
  $user="c2375a05";
  $password="c2375aU!";
  $dbname="c2375a05test";

  $con = new mysqli($host, $user, $password, $dbname, $port, $socket)
     or die ('Could not connect to the database server' . mysqli_connect_error());
     */

  $file = $_FILES["fileToUpload"]["tmp_name"];
  $mime = mime_content_type($file);
  $valid = true;


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

    // prevent files larger than 3MB from being uploaded
    if($_FILES["fileToUpload"]["size"] > (1024*1024*3))
    {
      $valid = false;
      $msg = "Image size is too large to upload (3MB limit)";
    }

    if($valid)
    {
      if(!move_uploaded_file($file, $src))
      {
        $valid = false;
        $msg = "There was a problem uploading your file.";
      }
    }

  }

?>
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

    <br>
    Category: <?php echo $_POST["category"] . "\n"; ?>
    <br>

    <?php
      $choice = $_POST["choice"];
      echo "Choices (" . count($choice) . "): \n    <ul class='choices'>\n";

      foreach($choice as $name)
      {
        echo "      <li>" . $name . "</li>\n";
      }
      
      echo "    </ul>";
    ?>


    Preview:
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