<!DOCTYPE html>
<head>
   <style>
      @import url("style.css"); /* Using a url */
   </style>
   <meta charset="utf-8">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <script>
   <?php
      $host="127.0.0.1";
      $port=3306;
      $socket="";
      $user="c2375a05";
      $password="!c2375aU!";
      $dbname="c2375a05test";

      $con = new mysqli($host, $user, $password, $dbname, $port, $socket)
        or die ('Could not connect to the database server' . mysqli_connect_error());

      $username=$_COOKIE["username"];

      $categories = array();
      $choices = array();

      // sql query to build menu
      $query = "select id, category, choice from my_options where lower(username) = lower('".$username."')";

      if ($stmt = $con->prepare($query)) {
          $stmt->execute();
          $stmt->bind_result($id, $category, $choice);
          while ($stmt->fetch()) {

            $categories[$id]=$category;

            if(!isset($choices[$id])) {

              $choices[$id] = array();

            }

            if($choice)
              array_push($choices[$id], $choice);

          }
          $stmt->close();
      }

      echo "var my_categories = JSON.parse('" . json_encode($categories) . "');";
      echo "var my_choices = JSON.parse('" . json_encode($choices) . "');";


   ?>
   </script>
</head>
<body>
   <form action="uploadcss.php" method="post" enctype="multipart/form-data">
      <div class="wrapper">
         <header>
            <h1>Upload Images</h1>
            <span></span>
         </header>
         <div class="sections">
            <section class="active">
               <input type="text" placeholder="Image Title" id="title" name="imageName"/>
               <select id="category" onchange="change_choices(this.value)" name="category">
               </select>
               <div id="choice" class="dropdown" name="choice">
                  <div class="dropbtn">Choices</div>
                  <div class="dropdown-content" id="choices" name="choices"></div>
                </div>
                
   <div class="image-upload-wrap">
     <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/png,image/gif,image/jpeg,image/webp" id="fileToUpload" name="fileToUpload"/>

     <div class="drag-text">
      <img id="preview" alt="DRAG AND DROP OR CLICK TO BROWSE">
     </div>
     
   </div>
   
   </section>

   <section>
   <input type="text" placeholder="Topic" id="topic"/>
   <textarea placeholder="something..." id="msg"></textarea>
   </section>
   </div>
   <footer>
   <ul>
   <input class="button buttonReset" type="reset" value="Reset">
   <input class="button buttonReset" type="submit" value="Submit">
   </ul>
   </footer>
   </div>
   </form>
   <div class="notification"></div>
   <footer></footer>
   <script>

      var category = document.getElementById("category");

      var choices = document.getElementById("choices");

      var select = document.getElementById("category");


      for(var i = 0; i < my_categories.length; i++) {
          var opt = my_categories[i];
          var el = document.createElement("option");
          el.textContent = opt;
          el.value = opt;
          select.appendChild(el);
      }

      function change_choices(value)
      {

      var myDiv = document.getElementById("choices");

      myDiv.innerHTML = "";

      for(var i = 0; i < my_choices[category.selectedIndex].length; i++) {
          var checkBox = document.createElement("input");
          var a = document.createElement("a");
          checkBox.type = "checkbox";
          checkBox.value = my_choices[category.selectedIndex][i];
          checkBox.name = "choice[]";
          a.appendChild(checkBox);
          myDiv.appendChild(a);
          a.appendChild(document.createTextNode(my_choices[category.selectedIndex][i]));
      	}
      }

      category.onchange();

      function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#preview').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
    document.getElementById("test1").innerHTML = document.getElementById("fileToUpload").value.split(/(\\|\/)/g).pop();


  }
}

$("#imgInp").change(function() {
  readURL(this);
});


   </script>
</body>
</html>