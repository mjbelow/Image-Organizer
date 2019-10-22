<!DOCTYPE html>
<html>
<head>
<title>Main Page</title>
<meta charset="UTF-8">
<style type="text/css">
.output {
  width: 100%;
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
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

<header>

  <form action="components/upload page/image.html" target="content" method="get">
    <button id="upload">Upload</button>
  </form>
  username
  <a href="#">Log out</a>

</header>


<main>
  <section id="menu">
    <form id="options" action="components/image page/index.php" target="content" method="get">

      <input type="submit" value="Show Images">
      <!-- <input type="reset" value="Reset"> -->

      <br>
      <br>

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

      //////////////////
      //              //
      //  MENU LOGIC  //
      //              //
      //////////////////
      
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
            group_category.indeterminate=true;
          else
            group_category.indeterminate=false;


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
                  selection.add(group_keys[k]);
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

          // disable output for a category if no choices are selected
          var output = Array.from(selection);
          
          if(output.length == 0)
          {
            group_output.disabled=true;
            group_output.value = group;
          }
          else
          {
            group_output.disabled=false;
            group_output.value = group + "," + output;
          }

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
    </form>
  </section>
  <iframe id="frame" name="content"></iframe>


</main>

<script type="application/javascript">
document.getElementById("frame").src="";
</script>

</body>
</html>