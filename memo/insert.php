<?php session_start(); ?>
<meta charset="utf-8">
<?php
    $userid=$_SESSION['userid'];
    $content=$_POST['content'];
    if(empty($userid)) {
		echo("
		<script>
	     window.alert('로그인 후 이용해 주세요.')
	     history.go(-1)
	   </script>
		");
		exit;
	}
	// history.go(-1) = 이전값을 유지하고 뒤로 돌리고 location.href = 그냥 다시 초기화한다.
	if(empty($_POST['content'])) {
		echo("
	   <script>
	     window.alert('내용을 입력하세요.')
	     history.go(-1)
	   </script>
		");
	 exit;
	}

	include "../lib/dbconn.php";       // dconn.php 파일을 불러옴
	$regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장

    $sql = "select * from member where id='$userid'";
    $result = mysqli_query($con,$sql);
	$row = mysqli_fetch_array($result);    //연관배열로 값을 저장
    //var_dump($row); 값을 다 보여준다(디버깅 함수) 
	
	$name = $row[name];
	$nick = $row[nick];

	$sql = "insert into memo (id, name, nick, content, regist_day) ";
	$sql .= "values('$userid', '$name', '$nick', '$content', '$regist_day')";

	mysqli_query($con,$sql);  // $sql 에 저장된 명령 실행

	mysqli_close($con);                // DB 연결 끊기

	echo "
	   <script>
	    location.href = 'memo.php';
	   </script>
	";
?>

  
