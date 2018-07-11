<?php
   include "../lib/dbconn.php";

   $num=$_GET['num'];
   $sql = "delete from memo where num = $num";
   mysqli_query($con,$sql);

   mysqli_close($con);

   echo "
	   <script>
	    location.href = 'memo.php';
	   </script>
	";
?>

