<!-- ----------------------------------------------------------------------- -->
<!-- Image Upload Page-->

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
   <form action="uploadcss.php" method="post">
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
                  <div class="dropdown-content" id="choices" name="choices">
   <!-- <form method="get">
   </form> -->
   </div>
   </div>
   <div class="image-upload-wrap">
   <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" id="fileToUpload" name="fileToUpload"/>
   
   <div class="drag-text">
   <h3 id = "test1">Drag and Drop or Click to Browse</h3>
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
        <!--  <img id="blah" src="#" alt="your image" /> -->
   <input class="button buttonReset" type="reset" value="Reset">
   <input class="button buttonReset" type="submit" value="Submit">
   </ul>
   </footer> 
   </div>
   </form>
   <div class="notification"></div>
   <footer></footer>
   <script>
   
      var my_categories = ["animals", "season"];
      var my_choices = [["dog","cat","penguin"], ["summer","fall","winter","spring"]];

      
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
          a.setAttribute("name","choice[]");
          checkBox.type = "checkbox";
          checkBox.value = my_choices[category.selectedIndex][i];
          checkBox.setAttribute("name", "choice[]");
          checkBox.name = "choice[]";
          a.appendChild(checkBox);
         // a.setAttribute("name", "choice[]");
          myDiv.appendChild(a);
          a.appendChild(document.createTextNode(my_choices[category.selectedIndex][i]));  
      	}     
      }
      
      category.onchange();

      function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#blah').attr('src', e.target.result);
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