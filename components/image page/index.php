<!DOCTYPE html>
<html>
<head>
<base href="../../">
<title>Image Page</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/style.css">
<style type="text/css">
body {
background: none;
}
</style>
<script type="application/javascript">

<?php
$host="127.0.0.1";
$port=3306;
$socket="";
$user="c2375a05";
$password="!c2375aU!";
$dbname="c2375a05proj";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());




// array to maintain order of categories
$categories = array();

// array used to create a JSON string
//   keys: category
//   values: array of image names belonging to category
$images = array();

// sql query
//$query = "select category.name category, image.name image from c2375a05proj.image   inner join c2375a05proj.category     on image.category_id = category.id where   (category_id = 1 and choices in (1,2,3)) order by image.category_id, image.choices";
$query = "select category.name category, image.name image from c2375a05proj.image   inner join c2375a05proj.category     on image.category_id = category.id where   (category_id = 1 and choices in (1,2,3))   or   (category_id = 2 and choices in (32,128,64,512)) order by image.category_id, image.choices";


if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($category, $image);
    while ($stmt->fetch()) {
      
      // if key doesn't exist for a category, assign one a new array and add to categories array
      if(!isset($images[$category])) {
        
        $images[$category] = array();
        array_push($categories, $category);
        
      }
      
      array_push($images[$category], $image);
      
    }
    $stmt->close();
}


$con->close();

// echo json_encode($images);
// echo "<br>";
// echo json_encode($categories);

echo "var images = JSON.parse('" . json_encode($images) . "');";
echo "\n";
echo "var categories = " . json_encode($categories) . ";";

?>

</script>
</head>

<body>

<script type="application/javascript">


var category = Object.keys(images);

var count = category.length;

var i, j;

for(i = 0; i < count; i++)
{

  var group = category[i];
  image_count = images[group].length;

  var div = document.createElement("div");

  var h3 = document.createElement("h3");
  var title = document.createTextNode(group + " (" + image_count + ")");
  h3.appendChild(title);

  var hr = document.createElement("hr");

  div.appendChild(h3);
  div.appendChild(hr);


  document.body.appendChild(div);


  for(j = 0; j < image_count; j++)
  {
    var img_container = document.createElement("div");
    img_container.className="img_container";

    var img_info = document.createElement("input");
    img_info.type = "button";
    img_info.value = "i";
    img_info.className="img_info";
    img_info.title="view / modify image data";

    var img = document.createElement("img");
    img.src = "images/" + images[group][j];
    img.onclick=enlarge(img.src);

    img.alt = "image"+pad(j+1);


    img_container.appendChild(img);
    img_container.appendChild(img_info);




    img_info.onclick = img_meta(img);


    div.appendChild(img_container);

  }

}

function pad(n)
{

  if(n < 10)
    return "00"+n;
  else if(n < 100)
    return "0"+n;
  return n;

}

function enlarge(src)
{

  return function()
  {
    var cover = document.createElement("div");
    cover.id = "cover";
    var img = document.createElement("img");
    img.src = src;

    cover.appendChild(img);
    document.body.style.overflow="hidden";

    cover.onclick=function() {
      this.remove();
      document.body.style.overflow="visible";
    }

    document.body.appendChild(cover);


  }

}

function img_meta(img)
{

  return function()
  {
    alert(img.alt);
  }

}

</script>

<script type="application/javascript">

<?php

// array to maintain order of categories
$categories = array();

// array used to create a JSON string
// keys: category
// values: array of image names belonging to category
$images = array();

$images["Tennis"] = [14,2,3,4];
$images["Animals"] = [5,6,7,8];
array_push($images["Tennis"],"plaese");
echo "var mine = JSON.parse('";
echo json_encode($images);
echo "');";

?>

</script>
<!--
<?php
$host="127.0.0.1";
$port=3306;
$socket="";
$user="c2375a05";
$password="148kpQ98*";
$dbname="c2375a05proj";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());



$images = array();

$categories = array();

$images["Tennis"] = array();

array_push($images["Tennis"], 1, "test");

$query = "select category.name category, image.name image from c2375a05proj.image   inner join c2375a05proj.category     on image.category_id = category.id where   (category_id = 1 and choices in (1,2,3)) order by image.category_id, image.choices";


if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($category, $image);
    while ($stmt->fetch()) {
      // if key doesn't exist for a category, assign one a new array and add to categories array
      /*
      if(!isset($images[$category])) {
        
        $images[$category] = array();
        $categories.push($category);
        array_push();
      }
      */
        //printf("%s, %s\n", $category, $images);
        
      //echo "$category";
        //printf("%s, %s\n", $category, $image);
      array_push($images[$category], 1, "test");
    }
    $stmt->close();
}

$con->close();

echo json_encode($images);

echo "yesy";
?>
-->
</body>
</html>