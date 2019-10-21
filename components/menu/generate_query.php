<?php

$output = $_GET['output'];
$category_count = count($output);
for($i = 0; $i < $category_count; $i++)
{
  $get_category = (explode(":", $output[$i]));
  $get_choices = (explode(",", $get_category[1]));
  
  $choice_count = count($get_choices);
  
  echo "(category_id = " . $get_category[0] . " and choices in (";

  for($j = 0; $j < $choice_count; $j++)
  {
    
    if($j == ($choice_count - 1))
      echo $get_choices[$j];
    else
      echo $get_choices[$j] . ",";
    
  }
  
  if($i == ($category_count - 1))
    echo "))";
  else
    echo "))<br>or<br>";
  //echo $i . " " . $output[$i] . "<br>";
  //(category_id = 1 and choices in (1,2,3))   or   (category_id = 2 and choices in (32,128,64,512)
}


?>