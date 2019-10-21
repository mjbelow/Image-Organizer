<?php

$output = $_GET['output'];
$category_count = count($output);
for($i = 0; $i < $category_count; $i++)
{
  $category_item = (explode(",", $output[$i]));
  
  $item_count = count($category_item);
  

  for($j = 0; $j < $item_count; $j++)
  {
    
   
    if($j == 0)
      echo "(category_id = " . $category_item[0] . " and choices in (";
    elseif($j == ($item_count - 1))
      echo $category_item[$j];
    else
      echo $category_item[$j] . ",";
    
  }
  
  if($i == ($category_count - 1))
    echo "))";
  else
    echo "))<br>or<br>";
  //echo $i . " " . $output[$i] . "<br>";
  //(category_id = 1 and choices in (1,2,3))   or   (category_id = 2 and choices in (32,128,64,512)
}


?>