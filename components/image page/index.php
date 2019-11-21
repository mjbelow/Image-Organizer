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

.category, .dropdown {
  width: 100%;
  margin-bottom: 4px;
}

.category, .dropbtn {
  background-color: white;
  color: #555555;
  padding: 5px;
  font-size: 16px;
  border: 2px solid #555555;
  cursor: pointer;
}


.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: white;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 1;
}

.dropdown-content label {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}


.dropdown-content label:hover {
  background-color: #555555;
  color: #eee;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #555555;
  color: white;
}

.buttons {
  text-align: center;
}
</style>
<script type="application/javascript" src="js/jquery.min.js"></script>
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
$query = "select category.name category, image.name image, image.category_id, image.choice_array " .
"from " .
  "(select name, category_id, sum(pow(2, choice_id-1)) choices, username, group_concat(choice_id) choice_array " .
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
    $stmt->bind_result($category, $image, $category_id, $choice_array);
    while ($stmt->fetch()) {

      // if key doesn't exist for a category, assign one a new array and add to categories array
      if(!isset($images[$category])) {

        $images[$category] = array();
        array_push($categories, $category);

      }

      array_push($images[$category], array($image, $category_id, $choice_array));

    }
    $stmt->close();
}


  $my_categories = array();
  $my_choices = array();

  // sql query to build menu
  $query = "select id, category, choice from my_options where lower(username) = lower('".$username."')";

  if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($id, $category, $choice);
    while ($stmt->fetch()) {

      $my_categories[$id]=$category;

      if(!isset($my_choices[$id])) {

        $my_choices[$id] = array();

      }

      if($choice)
        array_push($my_choices[$id], $choice);

    }
    $stmt->close();
  }

$con->close();



echo "var my_categories = JSON.parse('" . json_encode($my_categories) . "');\n";
echo "var my_choices = JSON.parse('" . json_encode($my_choices) . "');\n";

echo "var images = JSON.parse('" . json_encode($images) . "');\n";
echo "var categories = " . json_encode($categories) . ";\n";

?>
var category_count = my_categories.length;
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
    var category_index = images[group][j][1] - 1;
    var choices_array = (images[group][j][2]).split(",");

    var img_container = document.createElement("div");
    img_container.className="img_container";

    var img_info = document.createElement("input");
    img_info.type = "button";
    img_info.value = "i";
    img_info.className="img_info";
    img_info.title="view / modify image data";

    var img = document.createElement("img");
    img.src = "images/" + images[group][j][0];
    img.dataset.category=category_index;
    img.dataset.choices=choices_array;
    img.onclick=enlarge(img.src);

    img.alt = "image"+pad(j+1);


    img_container.appendChild(img);
    img_container.appendChild(img_info);



    var info = document.createElement("div");
    info.className="wrapper";
    info.style.display="none";

    var category_select = document.createElement("select");
    category_select.className="category";

    for(var k = 0; k < category_count; k++)
    {

      var option = document.createElement("option");
      option.innerHTML=my_categories[k];
      option.value=my_categories[k];
      category_select.appendChild(option);
    }

    category_select.selectedIndex=category_index;


    var dropdown = document.createElement("div");
    dropdown.className="dropdown";
    dropdown.innerHTML="<div class='dropbtn'>Choices</div>";

    var choices = document.createElement("div");
    choices.className="dropdown-content";

    var choices_count = my_choices[category_index].length;

    for(var k = 0; k < choices_count; k++)
    {
      var label = document.createElement("label");
      label.setAttribute("for", images[group][j][0] + k);
      var checkBox = document.createElement("input");
      checkBox.type = "checkbox";
      checkBox.value = my_choices[category_select.selectedIndex][k];
      checkBox.name = "choice[]";
      checkBox.id=images[group][j][0] + k;
      if((k+1) == choices_array[0])
      {
        checkBox.checked=true;
        choices_array.shift();
      }
      label.appendChild(checkBox);
      label.appendChild(document.createTextNode(my_choices[category_select.selectedIndex][k]));
      choices.appendChild(label);
    }

    dropdown.appendChild(choices);

    $(category_select).change(update_choices(choices, images[group][j][0]));

    info.appendChild(category_select);
    info.appendChild(dropdown);


    $(info).append
    (

      $("<div>").addClass("buttons")
        .append($("<button>").html("Delete").click(delete_img(img_container, category_select, images[group][j][0])))
        .append($("<button>").html("Modify").click(modify_img(img_container, category_select, choices, images[group][j][0])))

    );

/*
<div class="wrapper">

  <select id="category" onchange="update_choices()" name="category"><option value="Tennis">Tennis</option><option value="Animals">Animals</option></select>

  <div id="choice" class="dropdown" name="choice">

    <div class="dropbtn">Choices</div>

    <div class="dropdown-content" id="choices" name="choices">
      <label for="1"><a><input type="checkbox" value="Rafael Nadal" name="choice[]" id="1">Rafael Nadal</a></label>
    </div>

  </div>

</div>
*/

    img_container.appendChild(info);

    img_info.onclick = img_meta(img, info);
    //$(img_info).click(alrt(j))


    div.appendChild(img_container);

  }

}

function update_choices(choices, img)
{
  return function()
  {
    
    var choices_count = my_choices[this.selectedIndex].length;
    
    choices.innerHTML="";
    for(var i = 0; i < choices_count; i++)
    {
      var label = document.createElement("label");
      label.setAttribute("for", img + i);
      var checkBox = document.createElement("input");
      checkBox.type = "checkbox";
      checkBox.value = my_choices[this.selectedIndex][i];
      checkBox.name = "choice[]";
      checkBox.id=img + i;
      label.appendChild(checkBox);
      label.appendChild(document.createTextNode(my_choices[this.selectedIndex][i]));
      choices.appendChild(label);
    }
    
  }
}

function delete_img(container, category, img_src)
{
  return function()
  {
    $.post("modify.php", {category: category.value, img_src: img_src}, function(success)
    {
      if(success)
      {
        parent.start(5, null, null);
        $(container).remove();
      }
    })
  }
}


function modify_img(container, category, choices, img_src)
{
  return function()
  {
    var choice_array = [];
    $(choices).find("input:checked").each(function()
      {
        choice_array.push($(this).val());
      }
    );
    
    $.post("modify.php", {category: category.value, choice_array: JSON.stringify(choice_array), img_src: img_src},
    function(success)
    {
      if(success)
      {
        parent.start(6, null, null);
        $(container).css("border-color", "rgb(116, 163, 255)");
      }
    });
    
  }
}

function alrt(j)
{
  return function()
  {
    alert(j);
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

function img_meta(img, info)
{

  return function()
  {
    //info.style.display=info.style.display=="none"?"block":"none";
    $(info).toggle();
  }

}
</script>

</body>
</html>