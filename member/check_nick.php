<meta charset="utf-8">
<?php
   if(!isset($_GET['nick'])) 
   {
      echo("닉네임을 입력하세요.");
   }
   else
   {
      $nick = $_GET['nick'];
      include "../lib/dbconn.php";
 
      $sql = "select * from member where nick='$nick' ";

      $result = mysqli_query($con,$sql);
      $num_record = mysqli_num_rows($result);

      if ($num_record){
         echo "닉네임이 중복됩니다.<br>";
         echo "다른 닉네임을 사용하세요.<br>";
      }else{
         echo "사용가능한 닉네임입니다.";
      }
    
      mysqli_close($con);
   }
?>

