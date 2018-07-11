<meta charset="utf-8">
<?php
   if(!isset($_GET['id'])) 
   {
      echo("아이디를 입력하세요.");
   }else{
      $id = $_GET['id'];
      include "../lib/dbconn.php";
  
      //member 테이블 생성
      $flag = "NO";
      $sql = "show tables from classDB";
      $result = mysqli_query($con, $sql) or die("실패원인:".mysqli_error($con));
      while($row=mysqli_fetch_row($result)){
          if($row[0]==="member"){
              $flag ="OK";
              break;
          }
      }
      if($flag !=="OK"){
          $sql= "create table member (
                  id char(15) not null,
                  pass char(15) not null,
                  name char(10) not null,
                  nick char(10) not null,
                  hp char(20) not null,
                  email char(80),
                  regist_day char(20),
                  level int,
                  primary key(id)
               )";
          if(mysqli_query($con,$sql)){
              echo "<script>alert('member 테이블이 생성되었습니다.')</script>";
          }else{
              echo "실패원인:".mysqli_query($con);
          }
      }
     
      $sql = "select * from member where id='$id' ";
      $result = mysqli_query($con,$sql)or die("실패원인:".mysqli_error($con));
      $num_record = mysqli_num_rows($result);
      if($num_record){
          echo("
           <script>
             window.alert('해당 아이디가 존재합니다. \\n다른아이디를 사용하세요');
             window.close();
           </script>
         ");
      }else{
          echo("
           <script>
             window.alert('사용가능한 아이디입니다. ');
             window.close();
           </script>
         ");
      }
      mysqli_close($con);
   }
?>

