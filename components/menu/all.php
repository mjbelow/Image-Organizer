<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style type="text/css">
html,body {
  height: 100%;
}
ul {
  list-style:none;
}
.output {
  display: block;
  width: 100%;
}

iframe {
  width: 100%;
  height: 100%;
}
</style>

<script type="application/javascript">
var index = {};
index[1] = {};
index[1][1]=4;
index[1][2]=8;
index[1][4]=12;
index[1][8]=54;
index[2] = {};
index[2][1]=1;
index[2][2]=2;
index[2][3]=2;
index[2][4]=2;
index[2][5]=1;
index[2][6]=1;
index[2][7]=1;
index[3] = {};
index[3][2]=1
index[3][5]=1
index[3][6]=1
index[3][7]=1
index[3][9]=1
index[3][13]=1
index[3][14]=1
index[3][17]=1
index[3][19]=1
index[3][19]=2
index[3][21]=1
index[3][25]=1
index[3][25]=2
index[3][26]=1
index[3][30]=1
index[3][30]=2
<?php
$host="127.0.0.1";
$port=3306;
$socket="";
$user="c2375a05";
$password="!c2375aU!";
$dbname="c2375a05proj";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

// index to store values needed for interactive menu
//$index = array();

// sql query to build index
$query = "select category, choice, count from my_index";

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($category, $choice, $count);
    while ($stmt->fetch()) {

      $index[$category][$choice] = $count;

    }
    $stmt->close();
}


// sql query to build menu
$query = "select category, choice from my_options";

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($category, $choice);
    while ($stmt->fetch()) {
      
      if(!isset($options[$category])) {
        
        $options[$category] = array();
        
      }

      array_push($options[$category], $choice);
      
    }
    $stmt->close();
}




echo "index = JSON.parse('" . json_encode($index) . "');";
echo "var my_options = JSON.parse('" . json_encode($options) . "');";



$con->close();


?>
</script>

</head>

<body>

<form id="options" target="output" method="get" action="generate_query.php">

<input type="radio" name="operator" class="operator" checked>
OR
<input type="radio" name="operator" class="operator">
AND
<input type="radio" name="operator" class="operator">
XOR

<script type="application/javascript">
// category list
var category_list = document.createElement("ul");

var category = Object.keys(my_options);
var category_count = category.length;

for(var i = 0; i < category_count; i++)
{
  // category item
  var category_item = document.createElement("li");
  
  // category checkbox option
  var category_checkbox = document.createElement("input");
  category_checkbox.type="checkbox";
  category_checkbox.className="category";
  
  // category output (used to handle form data)
  var category_output = document.createElement("input");
  category_output.className="output";
  category_output.name="output[]";
  
  // category choice list
  var choice_list = document.createElement("ul");
  
  var choice = my_options[category[i]];
  var choice_count = choice.length;
  
  
  for(var j = 0; j < choice_count; j++)
  {
    // choice item
    var choice_item = document.createElement("li");
    
    // choice checkbox option
    var choice_checkbox = document.createElement("input");
    choice_checkbox.type="checkbox";
    choice_checkbox.className="choice";
    choice_checkbox.dataset.group=(i+1);
    choice_checkbox.dataset.bin=Math.pow(2, j);
    
    // append to choice item
    choice_item.appendChild(choice_checkbox);
    choice_item.innerHTML += choice[j] + " (<span></span>)";
    
    // append to choice list
    choice_list.appendChild(choice_item);
  }
  
  // append to category item
  category_item.appendChild(category_output);
  category_item.appendChild(category_checkbox);
  category_item.innerHTML += category[i] + " (<span></span>)";
  category_item.appendChild(choice_list);
  
  // append to category list
  category_list.appendChild(category_item);
  
  // append to document
  document.getElementById("options").appendChild(category_list);
}
</script>
<!--
<ul>

<li>
  <input type="checkbox" class="category">Seasons (<span>78</span>)
  <ul>
  <li><input type="checkbox" class="choice" data-group=1 data-bin=1>Spring (<span>4</span>)</li>
  <li><input type="checkbox" class="choice" data-group=1 data-bin=2>Summer (<span>8</span>)</li>
  <li><input type="checkbox" class="choice" data-group=1 data-bin=4>Fall (<span>12</span>)</li>
  <li><input type="checkbox" class="choice" data-group=1 data-bin=8>Winter (<span>54</span>)</li>
  </ul>
</li>
<li>
  <input type="checkbox" class="category">Animals (<span>11</span>)
  <ul>
  <li><input type="checkbox" class="choice" data-group=2 data-bin=1>Cat (<span>5</span>)</li>
  <li><input type="checkbox" class="choice" data-group=2 data-bin=2>Dog (<span>6</span>)</li>
  <li><input type="checkbox" class="choice" data-group=2 data-bin=4>Penguin (<span>5</span>)</li>
  </ul>
</li>
<li>
  <input type="checkbox" class="category">Category (<span>16</span>)
  <ul>
  <li><input type="checkbox" class="choice" data-group=3 data-bin=1>A (<span>10</span>)</li>
  <li><input type="checkbox" class="choice" data-group=3 data-bin=2>B (<span>9</span>)</li>
  <li><input type="checkbox" class="choice" data-group=3 data-bin=4>C (<span>8</span>)</li>
  <li><input type="checkbox" class="choice" data-group=3 data-bin=8>D (<span>8</span>)</li>
  <li><input type="checkbox" class="choice" data-group=3 data-bin=16>E (<span>9</span>)</li>
  </ul>
</li>

</ul>
-->
<button>Submit</button>

</form>

<script type="application/javascript">



var or_op = true;
var and_op = false;
var xor_op = false;




var choice = document.getElementsByClassName("choice");
var count = choice.length;

for(var i = 0; i < count; i++)
{

  choice[i].onchange=function()
  {
  
    var group_output = this.parentElement.parentElement.parentElement.getElementsByClassName("output")[0];
    var group_category = this.parentElement.parentElement.parentElement.getElementsByClassName("category")[0];
    var group_choice = this.parentElement.parentElement.parentElement.getElementsByClassName("choice");    
    var group_count = group_choice.length;
    var group = this.dataset.group;
    
    var image_keys = new Set();
    var selection = new Set();
    
    group_output.value = group + ",";

    
    var active = 0;
    var max = 0;
  
    for(var j = 0; j < group_count; j++)
    {
    
      if(group_choice[j].checked)
      {
        active |= group_choice[j].dataset.bin;
      }
      
      max |= group_choice[j].dataset.bin;
    }

    // change state of category checkbox
    if(active == max)
      group_category.checked=true;
    else
      group_category.checked=false;
    if(active != max && active != 0)
    {
      group_category.indeterminate=true;
      group_output.disabled=false;
    }
    else
    {
      group_category.indeterminate=false;
      group_output.disabled=true;
    }

    var group_keys = Object.keys(index[group]);
    var group_keys_count = group_keys.length;

    var group_total = 0;

    for(var j = 0; j < group_keys_count; j++)
    {
      
      group_total += index[group][group_keys[j]];
    
    }
    
    // update value for all choices
    for(var j = 0; j < group_count; j++)
    {
      
      var selected = 0;
      var total = 0;
      var n;
      var bin = group_choice[j].dataset.bin;
      
      if(or_op | and_op)
        n = active | bin;
      else
        n = active & ~bin;
      
      
      var choice_checked = group_choice[j].checked;
      
      for(var k = 0; k < group_keys_count; k++)
      {
        
        var bool;
        if(and_op)
          bool = ((n & group_keys[k]) == n);
        else if(or_op)
          bool = ((bin & group_keys[k]) == bin);
        else
          bool = (((n & group_keys[k]) == 0) && ((bin & group_keys[k]) == bin));
        
        if((bin & group_keys[k]) == bin)
          total += index[group][group_keys[k]];
        
        
        if(bool)
        {
          //console.log("item " + j + ":\t" + group_keys[k])
          if(choice_checked)
          {
            image_keys.add(k);
            selection.add(Math.pow(2,k))
          }
          selected += index[group][group_keys[k]];
        }
      
      }
      
      var info = group_choice[j].parentElement.getElementsByTagName("span")[0];
      info.innerHTML = selected + " / " + total;

    }
    
    var image_keys_values = image_keys.values();
    var image_keys_count = image_keys.size;

    var group_info = group_category.parentElement.getElementsByTagName("span")[0];
    var group_selected = 0;
    
    for(var j = 0; j < image_keys_count; j++)
    {
      var key = image_keys_values.next().value;
      
      
      group_selected += index[group][group_keys[key]];
    
    }

    group_info.innerHTML = group_selected + " / " + group_total;
    
    //(category_id = 1 and choices in (1,2,3))   or   (category_id = 2 and choices in (32,128,64,512)
    group_output.value += Array.from(selection);
  }

}


var category = document.getElementsByClassName("category");
count = category.length;

for(var i = 0; i < count; i++)
{

  category[i].onchange=function()
  {
    toggle_all(this.checked, this.parentElement.getElementsByClassName("choice"));
    this.parentElement.getElementsByClassName("choice")[0].onchange();
  }

  //category[i].onchange();

}

function toggle_all(checked, choice)
{

  var count = choice.length;

  for(var i = 0; i < count; i++)
  {
    choice[i].checked = checked;
  }

}

var operator = document.getElementsByClassName("operator");
count = operator.length;



for(var i = 0; i < count; i++)
{

  operator[i].onchange=function()
  {
    or_op = operator[0].checked;
    and_op = operator[1].checked;
    xor_op = operator[2].checked;
    
    var category_count = category.length;
    for(var j = 0; j < category_count; j++)
    {
      category[j].parentElement.getElementsByClassName("choice")[0].onchange();
    }
    
  }

  operator[i].onchange();
}



</script>

<iframe name="output"></iframe>

</body>

</html>
