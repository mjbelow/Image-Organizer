<!DOCTYPE html>
<head>
   <style>
      @import url("style.css"); /* Using a url */
   </style>
   <meta charset="utf-8">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <script>     
   </script>
</head>
<body>
<?php




/*

following PHP code generated using MySQL Workbench (IMPORTANT: $password has to be set or else it won't be able to connect to database):
Tools > Utilities > Copy as PHP Code (Connect to Server)

*/
$host="127.0.0.1";
$port=3306;
$socket="";
$user="c2375a05";
$password="c2375aU!";
$dbname="c2375a05test";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
   or die ('Could not connect to the database server' . mysqli_connect_error());
   
   $imageFile = $_POST["fileToUpload"]; // Image file being Uploaded.

?>

<div class="wrapper">
         <header>
            <h1>Image Uploaded</h1>
            <span></span>
         </header>
         
   <p class = uploadResults>
      <br>
      Image Name: <?php echo $imageFile; ?>
      <br>
      Category: <?php echo $_POST["category"]; ?>
      <br>
      
      Number of Choices Selected: 
      <?php $choices = $_POST["choice"];
         echo count($choices); ?>

     <br> Choices Selected: <br>
      <?php 
      
      
      $name = $_POST["choice"];
   
      foreach ($name as $choice){ 
         echo $choice."<br />";  
   } 
   

      //header("content-type:image/jpeg");
     // echo $_POST["fileToUpload"];
     ?>
   
     Image Uploaded : 
     <br>
     <?php
     $imageData = base64_encode(file_get_contents($imageFile));
     echo '<img class= "outputImage" src="data:image/jpeg;base64,'.$imageData.'" width="300" height="300">';
   
     ?>
     
</p>         
           
   

   <section>
   <input type="text" placeholder="Topic" id="topic"/>
   <textarea placeholder="something..." id="msg"></textarea>
   </section>
   </div>
   <footer>
   
   </footer> 
   </div>






</body>
</html>