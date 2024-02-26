<?php

$conn = mysqli_connect("localhost","root","","test") or die("Connection Failed");

$sql = "DELETE FROM students";

if(mysqli_query($conn, $sql)){
  echo 1;
}else{
  echo 0;
}

?>
