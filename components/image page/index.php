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


$username=$_COOKIE["username"];

// array to maintain order of categories (not necessary)
$categories = array();

// array used to create a JSON string
//   keys: category
//   values: array of image names belonging to category
$images = array();

// sql query
//$query = "select category.name category, image.name image from c2375a05proj.image   inner join c2375a05proj.category     on image.category_id = category.id where   (category_id = 1 and choices in (1,2,3)) order by image.category_id, image.choices";
//$query = "select category.name category, image.name image from c2375a05proj.image   inner join c2375a05proj.category     on image.category_id = category.id where   (category_id = 1 and choices in (1,2,3))   or   (category_id = 2 and choices in (32,128,64,512)) order by image.category_id, image.choices";
$query = "select category.name category, image.name image " .
"from " .
  "(select name, category_id, sum(pow(2, choice_id-1)) choices, username " .
    "from image " .
    "group by username, category_id, name " .
    "order by username, category_id, choices, name) image " .
  "inner join category " .
    "on image.category_id = category.id AND image.username = category.username " .
"where lower(image.username)=lower('".$username."') and (";

$output = $_GET['output'];
$category_count = count($output);
for($i = 0; $i < $category_count; $i++)
{
  
  $category_item = (explode(",", $output[$i]));
  $item_count = count($category_item);

  for($j = 0; $j < $item_count; $j++)
  {

    if($j == 0)
      $query .= "(category_id = " . $category_item[0] . " and choices in (";
    elseif($j == ($item_count - 1))
      $query .= $category_item[$j];
    else
      $query .= $category_item[$j] . ",";

  }

  if($i == ($category_count - 1))
    $query .= ")) ) " .
    "order by category_id, choices, image.name";
  else
    $query .= ")) or ";
  
}
echo "//" . $query . "\n";


//$query = "select category.name category, image.name image from c2375a05proj.image   inner join c2375a05proj.category     on image.category_id = category.id where   (category_id = 1 and choices in (1,2,3))   or   (category_id = 2 and choices in (32,128,64,512)) order by image.category_id, image.choices";

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

</body>
</html>