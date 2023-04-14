<?php
require_once __DIR__ . '/db.php';
if (count($_FILES) > 0)
{
    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
        $imgData = file_get_contents($_FILES['userImage']['tmp_name']);
        $imgType = $_FILES['userImage']['type'];
        $sql = "INSERT INTO tbl_image(imageType,imageData) VALUES(?, ?)";
        $statement = $conn->prepare($sql);
        $statement->bind_param('ss', $imgType, $imgData);
        $current_id = $statement->execute() or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_connect_error());
    }
}
?>
<html>
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
  <br><br>
  <div class="container">
    <form name="frmImage" enctype="multipart/form-data" action="" method="post">
            <div class="mb-3">
              <div class="input-group">
                <input name="userImage" type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                <button name="submit" type="submit" class="btn btn-outline-secondary" id="inputGroupFileAddon04">Submit</button>
              </div>
            </div>
      <br><br>
            <div class="mb-3">
        <?php
        $sql = "SELECT * FROM tbl_image";
        if($result = mysqli_query($conn, $sql)){
          if(mysqli_num_rows($result) > 0){
            
            echo "<table class='table'><thead><tr><th scope='col'>imageId</th><th scope='col'>imageType</th><th scope='col'>imageData</th></tr></thead><tbody>";
            
            while($row = mysqli_fetch_array($result))
            {
              echo "<tr><th scope='row'>".$row['imageId']."</th>";
              echo "<td>".$row['imageType']."</td>";
              
              $image = imagecreatefromstring($row['imageData']);
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
  
      echo "<br><br>";
  
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
  
        ?>
          </div>
    </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>
</html>