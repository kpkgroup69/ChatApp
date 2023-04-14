<?php
require_once __DIR__ . '/db.php';
if (count($_FILES) > 0)
{
    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        $room = $_POST['room'];
        $user = $_POST['user'];
        $mess = $_POST['mess'];
        $imgData = file_get_contents($_FILES['file']['tmp_name']);
        $imgType = $_FILES['file']['type'];
        $sql = "INSERT INTO messages_archive(name_room,name_user,content_message,content_image_type,content_image_data) VALUES(?, ?, ?, ?, ?)";
        $statement = $conn->prepare($sql);
        $statement->bind_param('sssss', $room, $user, $mess, $imgType, $imgData);
        $current_id = $statement->execute() or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_connect_error());
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
  </head>
  <body data-bs-theme="dark">
    
    <div class="container">
      
    <br><br>
      
    <form action="" method="post" id="usrform" enctype="multipart/form-data">
      
      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text">room@user</span>
          <input type="text" aria-label="Room" class="form-control" name="room" placeholder="Room">
          <span class="input-group-text">@</span>
          <input type="text" aria-label="Username" class="form-control" name="user" placeholder="Username">
        </div>
      </div>
      <div class="mb-3">
        <input class="form-control" name="mess" placeholder="Message">
      </div>
      <div class="mb-3">
        <div class="input-group">
          <input name="file" type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
          <button name="submit" type="submit" class="btn btn-outline-secondary" id="inputGroupFileAddon04">Submit</button>
        </div>
      </div>
      
    </form>

        <?php
        include_once 'db.php';
        
        echo "<ul class='nav nav-pills mb-3' id='pills-tab' role='tablist'><li class='nav-item' role='presentation'><button class='nav-link active' id='pills-full-tab' data-bs-toggle='pill' data-bs-target='#pills-full' type='button' role='tab' aria-controls='pills-full' aria-selected='true'>Chat full history</button></li><li class='nav-item' role='presentation'><button class='nav-link' id='pills-partial-tab' data-bs-toggle='pill' data-bs-target='#pills-partial' type='button' role='tab' aria-controls='pills-partial' aria-selected='false'>Chat rooms history</button></li></ul><div class='tab-content' id='pills-tabContent'><div class='tab-pane fade show active' id='pills-full' role='tabpanel' aria-labelledby='pills-full-tab' tabindex='0'>";
          
        $sql = "SELECT * FROM messages_archive";
        if($result = mysqli_query($conn, $sql)){
          if(mysqli_num_rows($result) > 0){
            
            echo "<table class='table'><thead><tr><th scope='col'>ID</th><th scope='col'>ROOM</th><th scope='col'>USER</th><th scope='col'>MESSAGE TEXT</th><th scope='col'>MESSAGE IMAGE</th></tr></thead><tbody>";
            
            while($row = mysqli_fetch_array($result))
            {
              echo "<tr><th scope='row'>".$row['id']."</th>";
              echo "<td>".$row['name_room']."</td>";
              echo "<td>".$row['name_user']."</td>";
              echo "<td>".$row['content_message']."</td>";
              
              $image = imagecreatefromstring($row['content_image_data']); 
              ob_start();
              imagejpeg($image, null, 80);
              $data = ob_get_contents();
              ob_end_clean();
              
              echo "<td><img height='50px' src='data:image/jpg;base64," .  base64_encode($data)  . "' /></td></tr>";
            }
            // Free result set
            mysqli_free_result($result);
            
            echo "</tbody></table>";
            
          } else{
            echo "No records matching your query were found.";
          }
        } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }
        
        echo "</div><div class='tab-pane fade' id='pills-partial' role='tabpanel' aria-labelledby='pills-partial-tab' tabindex='0'>";

        $sql = "SELECT DISTINCT name_room FROM messages_archive";
        if($result = mysqli_query($conn, $sql)){
          if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result))
            {
              $sql_inner = "SELECT * FROM messages_archive WHERE name_room = '".$row['name_room']."'";
              if($result_inner = mysqli_query($conn, $sql_inner)){
                if(mysqli_num_rows($result_inner) > 0){
                  
                  echo "<table class='table'><thead><tr><th scope='col'>ID</th><th scope='col'>ROOM</th><th scope='col'>USER</th><th scope='col'>MESSAGE TEXT</th><th scope='col'>MESSAGE IMAGE</th></tr></thead><tbody>";
                  
                  while($row_inner = mysqli_fetch_array($result_inner))
                  {
                    echo "<tr><th scope='row'>".$row_inner['id']."</th>";
                    echo "<td>".$row_inner['name_room']."</td>";
                    echo "<td>".$row_inner['name_user']."</td>";
                    echo "<td>".$row_inner['content_message']."</td>";

                    $image_inner = imagecreatefromstring($row_inner['content_image_data']);
                    ob_start();
                    imagejpeg($image_inner, null, 80);
                    $data_inner = ob_get_contents();
                    ob_end_clean();
                    
                    echo "<td><img height='50px' src='data:image/jpg;base64," .  base64_encode($data_inner)  . "' /></td></tr>";
                  }
                  // Free result set
                  mysqli_free_result($result_inner);
                  
                  echo "</tbody></table>";
                  
                } else{
                  echo "No records matching your query were found.";
                }
              } else{
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
              }
            }
            // Free result set
            mysqli_free_result($result);
            
          } else{
            echo "No records matching your query were found.";
          }
        } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }
  
        echo "</div></div>";
  
      $sql = "SELECT * FROM copyright_table WHERE id='1'";
      if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
          while($row = mysqli_fetch_array($result))
          {
            echo "<div class='text-center p-3'>© ".$row['year']." Copyright: ";
            echo "<a class='text-white' href='https://kpkgroup69.ct8.pl/'>".$row['company']."</a> &#x2022; <a class='text-white' href='https://debug.kpkgroup69.ct8.pl/hosting.php'>hosting</a></div>";
          }
          // Free result set
          mysqli_free_result($result);
        } else{
          echo "No records matching your query were found.";
        }
      } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
      }
        
        // Close connection
        mysqli_close($conn);
        ?>
    </div>    
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>
