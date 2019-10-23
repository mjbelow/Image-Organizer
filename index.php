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
// Performs difference operation between 
// called set and otherSet 
Set.prototype.difference = function(otherSet) 
{ 
    // creating new set to store difference 
     var differenceSet = new Set(); 
  
    // iterate over the values 
    for(var elem of this) 
    { 
        // if the value[i] is not present  
        // in otherSet add to the differenceSet 
        if(!otherSet.has(elem)) 
            differenceSet.add(elem); 
    } 
  
    // returns values of differenceSet 
    return differenceSet; 
}

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
      <fieldset>
        <legend>Include</legend>
        <input type="radio" name="in_operator" class="operator in" checked>
        OR
        <input type="radio" name="in_operator" class="operator in">
        AND
        <input type="radio" name="in_operator" class="operator in">
        XOR
      </fieldset>
      
      <br>
      
      <fieldset>
        <legend>Exclude</legend>
        <input type="radio" name="ex_operator" class="operator ex" checked>
        OR
        <input type="radio" name="ex_operator" class="operator ex">
        AND
        <input type="radio" name="ex_operator" class="operator ex">
        XOR
      </fieldset>
    
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
          
          // choice checkbox option (include)
          var choice_checkbox = document.createElement("input");
          choice_checkbox.type="checkbox";
          choice_checkbox.className="choice in";
          choice_checkbox.dataset.group=(i+1);
          choice_checkbox.dataset.bin=Math.pow(2, j);

          // append (include) to choice item
          choice_item.appendChild(choice_checkbox);
          
          // choice checkbox option (exclude);
          choice_checkbox = choice_checkbox.cloneNode(false);
          choice_checkbox.className="choice ex";
          
          // append (exclude) to choice item
          choice_item.appendChild(choice_checkbox);
          
          // add choice name
          choice_item.innerHTML += choice[j] + " (<span></span>)";
          
          // append to choice list
          choice_list.appendChild(choice_item);
        }
        
        // append to category item
        category_item.appendChild(category_output);
        
        
        category_item.appendChild(category_checkbox);
        category_checkbox = category_checkbox.cloneNode(false);
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

      var ex_or_op = true;
      var ex_and_op = false;

      var choice = document.getElementsByClassName("choice");
      var count = choice.length;

      for(var i = 0; i < count; i++)
      {

        choice[i].onchange=function()
        {

          // include logic
          var group_category = this.parentElement.parentElement.parentElement.getElementsByClassName("category")[0];
          var group_choice = this.parentElement.parentElement.parentElement.getElementsByClassName("choice in");    
          
          // exclude logic
          var ex_group_category = this.parentElement.parentElement.parentElement.getElementsByClassName("category")[1];
          var ex_group_choice = this.parentElement.parentElement.parentElement.getElementsByClassName("choice ex");
          
          var group_output = this.parentElement.parentElement.parentElement.getElementsByClassName("output")[0];
          var group_count = group_choice.length;
          var group = this.dataset.group;
          
          // include logic
          var image_keys = new Set();
          var selection = new Set();
          
          // exclude logic
          var ex_image_keys = new Set();
          var ex_selection = new Set();
          
          var group_selected = 0;

          // include logic
          var active = 0;
          
          // exclude logic
          var ex_active = 0;

          var max = 0;
          
          for(var j = 0; j < group_count; j++)
          {
          
            // include logic
            if(group_choice[j].checked)
              active |= group_choice[j].dataset.bin;
            
            //exclude logic
            if(ex_group_choice[j].checked)
              ex_active |= ex_group_choice[j].dataset.bin;
            
            max |= group_choice[j].dataset.bin;
          }

          // change state of category checkbox
          // include logic
          if(active == max)
            group_category.checked=true;
          else
            group_category.checked=false;
          if(active != max && active != 0)
            group_category.indeterminate=true;
          else
            group_category.indeterminate=false;
          
          // exclude logic
          if(ex_active == max)
            ex_group_category.checked=true;
          else
            ex_group_category.checked=false;
          if(ex_active != max && ex_active != 0)
            ex_group_category.indeterminate=true;
          else
            ex_group_category.indeterminate=false;


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
            // include logic
            var selected = 0;
            
            // exclude logic
            var ex_selected = 0;
            
            var total = 0;
            var bin = group_choice[j].dataset.bin;
            
            // include logic
            var n;
            if(or_op | and_op)
              n = active | bin;
            else
              n = active & ~bin;
            
            // exclude logic
            var ex_n;
            if(ex_or_op | ex_and_op)
              ex_n = ex_active | bin;
            else
              ex_n = ex_active & ~bin;
            
            // include logic
            var choice_checked = group_choice[j].checked;
            
            // exclude logic
            var ex_choice_checked = ex_group_choice[j].checked;
            
            for(var k = 0; k < group_keys_count; k++)
            {
              // include logic
              var bool;
              if(and_op)
                bool = ((n & group_keys[k]) == n);
              else if(or_op)
                bool = ((bin & group_keys[k]) == bin);
              else
                bool = (((n & group_keys[k]) == 0) && ((bin & group_keys[k]) == bin));
              
              // exclude logic
              var ex_bool;
              if(ex_and_op)
                ex_bool = ((ex_n & group_keys[k]) == ex_n);
              else if(ex_or_op)
                ex_bool = ((bin & group_keys[k]) == bin);
              else
                ex_bool = (((ex_n & group_keys[k]) == 0) && ((bin & group_keys[k]) == bin));
              
              if((bin & group_keys[k]) == bin)
                total += index[group][group_keys[k]];
              
              // include logic
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
              
              // exclude logic
              if(ex_bool)
              {
                if(ex_choice_checked)
                {
                  ex_image_keys.add(k);
                  ex_selection.add(group_keys[k]);
                }
                ex_selected += index[group][group_keys[k]];
                //selected -= index[group][group_keys[k]];
              }
            
            }
            
            var info = group_choice[j].parentElement.getElementsByTagName("span")[0];
            //info.innerHTML = selected + " / " + total + ") (" + ex_selected + " / " + total;

            //if(ex_active)
            //  selected -= ex_selected;
            //selected = selected < 0 ? 0 : selected;

            info.innerHTML = selected + " / " + total;
            group_choice[j].dataset.total = total;
          }
          
          image_keys = image_keys.difference(ex_image_keys);
          
          var image_keys_values = image_keys.values();
          var image_keys_count = image_keys.size;

          var group_info = group_category.parentElement.getElementsByTagName("span")[0];
          var group_selected = 0;
          
          for(var j = 0; j < image_keys_count; j++)
          {
            var key = image_keys_values.next().value;
            
            
            group_selected += index[group][group_keys[key]];
          
          }
          
          
          for(var j = 0; j < group_count; j++)
          {
            
            var image_keys_values = image_keys.values();
            var bin = group_choice[j].dataset.bin;
            var selected = 0;
            var choice_checked = group_choice[j].checked;
            
            for(var k = 0; k < image_keys_count; k++)
            {
              
              var key = image_keys_values.next().value;
              
              
              if(((bin & group_keys[key]) == bin) && choice_checked)
              {
                selected += index[group][group_keys[key]];
              }
              

              
            }
            
            
            var info = group_choice[j].parentElement.getElementsByTagName("span")[0];
            //info.innerHTML = selected + " / " + total + ") (" + ex_selected + " / " + total;

            //if(ex_active)
            //  selected -= ex_selected;
            //selected = selected < 0 ? 0 : selected;

            info.innerHTML = selected + " / " + group_choice[j].dataset.total;
            
          }
          

          group_info.innerHTML = group_selected + " / " + group_total;

          // disable output for a category if no choices are selected
          
          selection = selection.difference(ex_selection);
          
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

        // include category checkbox
        if(i % 2 == 0)
        {
          category[i].onchange=function()
          {
            toggle_all(this.checked, this.parentElement.getElementsByClassName("choice in"));
            this.parentElement.getElementsByClassName("choice")[0].onchange();
          }
          
          // initiate menu
           category[i].onchange();
        }
        // exclude category checkbox
        else
        {
          category[i].onchange=function()
          {
            toggle_all(this.checked, this.parentElement.getElementsByClassName("choice ex"));
            this.parentElement.getElementsByClassName("choice")[0].onchange();
          }
        }

      }

      function toggle_all(checked, choice)
      {

        var count = choice.length;

        for(var i = 0; i < count; i++)
        {
          choice[i].checked = checked;
        }

      }

      // include logic
      var operator = document.getElementsByClassName("operator in");
      count = operator.length;

      for(var i = 0; i < count; i++)
      {

        operator[i].onchange=function()
        {
          or_op = operator[0].checked;
          and_op = operator[1].checked;
          
          var category_count = category.length;
          for(var j = 0; j < category_count; j++)
          {
            category[j].parentElement.getElementsByClassName("choice")[0].onchange();
          }
          
        }
        
        // initialize operator values
        or_op = operator[0].checked;
        and_op = operator[1].checked;

      }

      // exclude logic
      var ex_operator = document.getElementsByClassName("operator ex");
      count = ex_operator.length;

      for(var i = 0; i < count; i++)
      {

        ex_operator[i].onchange=function()
        {
          ex_or_op = ex_operator[0].checked;
          ex_and_op = ex_operator[1].checked;
          
          var category_count = category.length;
          for(var j = 0; j < category_count; j++)
          {
            category[j].parentElement.getElementsByClassName("choice")[0].onchange();
          }
          
        }
        
        // initialize operator values
        ex_or_op = ex_operator[0].checked;
        ex_and_op = ex_operator[1].checked;

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