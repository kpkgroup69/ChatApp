<?php
    $servername='mysql.ct8.pl';
    $username='m34461_kpkgroup';
    $password='iYj1q!$!y$FB0(#IP3Ux';
    $dbname = "m34461_kpkgroup";
    $conn=mysqli_connect($servername,$username,$password,$dbname);
      if(!$conn){
          die('Could not Connect MySql Server:' .mysql_error());
        }
?>