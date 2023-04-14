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
    <title>ChatApp</title>
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
      
<div class="row">
  <div class="col-sm-auto">
    <div class="card">
      <div class="card-body">
        <div class="btn-group-vertical" role="group" aria-label="Vertical button group">

<?php
  include_once 'db.php';
  
  $sql = "SELECT DISTINCT name_room FROM messages_archive";
  if($result = mysqli_query($conn, $sql)){
    if(mysqli_num_rows($result) > 0){
      
      echo "<div class='btn-group-vertical' role='group' aria-label='Vertical button group'>";
      
      while($row = mysqli_fetch_array($result))
      {
        echo "<button class='btn btn-secondary' type='button' data-bs-toggle='collapse' data-bs-target='#".$row['name_room']."' aria-expanded='false' aria-controls='collapseExample'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-wechat' viewBox='0 0 16 16'><path d='M11.176 14.429c-2.665 0-4.826-1.8-4.826-4.018 0-2.22 2.159-4.02 4.824-4.02S16 8.191 16 10.411c0 1.21-.65 2.301-1.666 3.036a.324.324 0 0 0-.12.366l.218.81a.616.616 0 0 1 .029.117.166.166 0 0 1-.162.162.177.177 0 0 1-.092-.03l-1.057-.61a.519.519 0 0 0-.256-.074.509.509 0 0 0-.142.021 5.668 5.668 0 0 1-1.576.22ZM9.064 9.542a.647.647 0 1 0 .557-1 .645.645 0 0 0-.646.647.615.615 0 0 0 .09.353Zm3.232.001a.646.646 0 1 0 .546-1 .645.645 0 0 0-.644.644.627.627 0 0 0 .098.356Z'/><path d='M0 6.826c0 1.455.781 2.765 2.001 3.656a.385.385 0 0 1 .143.439l-.161.6-.1.373a.499.499 0 0 0-.032.14.192.192 0 0 0 .193.193c.039 0 .077-.01.111-.029l1.268-.733a.622.622 0 0 1 .308-.088c.058 0 .116.009.171.025a6.83 6.83 0 0 0 1.625.26 4.45 4.45 0 0 1-.177-1.251c0-2.936 2.785-5.02 5.824-5.02.05 0 .1 0 .15.002C10.587 3.429 8.392 2 5.796 2 2.596 2 0 4.16 0 6.826Zm4.632-1.555a.77.77 0 1 1-1.54 0 .77.77 0 0 1 1.54 0Zm3.875 0a.77.77 0 1 1-1.54 0 .77.77 0 0 1 1.54 0Z'/></svg>&nbsp;".$row['name_room']."</button>";
      }
      
      echo "</div>";
      
      // Free result set
      mysqli_free_result($result);
    } else{
      echo "No records matching your query were found.";
    }
  } else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
  }
?>

        </div>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card">
      <div class="card-body">
        <?php
        $sql = "SELECT DISTINCT name_room FROM messages_archive";
        if($result = mysqli_query($conn, $sql)){
          if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result))
            {              
              $sql_inner = "SELECT * FROM messages_archive WHERE name_room = '".$row['name_room']."'";
              if($result_inner = mysqli_query($conn, $sql_inner)){
                if(mysqli_num_rows($result_inner) > 0){
                  
                  echo "<div class='collapse' id='".$row['name_room']."'><span style='margin: 5px;' class='badge rounded-pill text-bg-secondary'>".$row['name_room']."</span><div class='card'><ul class='list-group list-group-flush'>";
                  
                  while($row_inner = mysqli_fetch_array($result_inner))
                  {
                    echo "<li class='list-group-item'><span class='badge rounded-pill text-bg-secondary'>".$row_inner['name_room']."</span>&nbsp;";
                    echo "<span class='badge rounded-pill text-bg-light'>".$row_inner['name_user']."</span><br>";
                    echo "".$row_inner['content_message']." ";
                    $image_inner = imagecreatefromstring($row_inner['content_image_data']);
                    ob_start();
                    imagejpeg($image_inner, null, 80);
                    $data_inner = ob_get_contents();
                    ob_end_clean();
                    echo "<img height='25px' src='data:image/jpg;base64," .  base64_encode($data_inner)  . "' /></li>";
                  }
                  // Free result set
                  mysqli_free_result($result_inner);
                  
                  echo "</ul></div></div>";
                  
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
        ?>
        
      </div>
    </div>
  </div>
</div>
       
<br>
      
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
        <div class="input-group">
          <input name="file" type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
        </div>
      </div>
      <div class="mb-3">
        <div class="input-group">
          <input class="form-control" name="mess" placeholder="Message">
          <button name="submit" type="submit" class="btn btn-outline-secondary" id="inputGroupFileAddon04">Submit</button>
        </div>
      </div>
    </form>

    </div>
    
<?php
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>
