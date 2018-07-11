<meta charset="utf-8">
<?php
   include "../lib/dbconn.php";       // dconn.php 파일을 불러옴

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
   if(isset($_POST["id"])){
       $id=$_POST["id"];
       $pass=$_POST["pass"];
       $name=$_POST["name"];
       $nick=$_POST["nick"];
       $hp1=$_POST["hp1"];
       $hp2=$_POST["hp2"];
       $hp3=$_POST["hp3"];
       $email1=$_POST["email1"];
       $email2=$_POST["email2"];
   }
   
   $hp = $hp1."-".$hp2."-".$hp3;
   $email = $email1."@".$email2;
   $regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장
   $ip = $REMOTE_ADDR;         // 방문자의 IP 주소를 저장
 
   $sql = "select * from member where id='$id'";
   $result = mysqli_query($con,$sql)or die("실패원인:".mysqli_error($con));
   $exist_id = mysqli_num_rows($result);

   if($exist_id) {
     echo("
           <script>
             window.alert('해당 아이디가 존재합니다.')
             history.go(-1)
           </script>
         ");
         exit;
   }else{            // 레코드 삽입 명령을 $sql에 입력
	    $sql = "insert into member(id, pass, name, nick, hp, email, regist_day, level) ";
		$sql .= "values('$id', '$pass', '$name', '$nick', '$hp', '$email', '$regist_day', 9)";
		mysqli_query($con,$sql)or die("실패원인:".mysqli_error($con)); // $sql 에 저장된 명령 실행
   }
   mysqli_close($con);                // DB 연결 끊기
   echo "<script>alert('회원가입되었습니다');</script>";
   echo "
	   <script>
	    location.href = '../index.php';
	   </script>
	";
?>

   
