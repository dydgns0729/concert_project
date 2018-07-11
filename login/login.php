<?php
    session_start();
    include "../lib/dbconn.php";
    echo "<meta charset='utf-8'>";
   // 이전화면에서 이름이 입력되지 않았으면 "이름을 입력하세요"
   // 메시지 출력
    $id=mysqli_real_escape_string($con,$_POST['id']);
    $pass=mysqli_real_escape_string($con,$_POST['pass']);
   if(empty($id)) {
     echo ("
           <script>
             window.alert('아이디를 입력하세요.');
             history.go(-1);
           </script>
         ");
         exit;  //무조건종료
   }

   if(empty($pass)) {
     echo("
           <script>
             window.alert('비밀번호를 입력하세요.');
             history.go(-1);
           </script>
         ");
         exit;
   }

   $sql = "select * from member where id='$id'";
   $result = mysqli_query($con,$sql);

   $num_match = mysqli_num_rows($result);

   if(!$num_match) 
   {
     echo("
           <script>
             window.alert('등록되지 않은 아이디입니다.');
             history.go(-1);
           </script>
         ");
    }
    else
    {
        $row = mysqli_fetch_array($result);

        $db_pass = $row[pass];

        if($pass != $db_pass)
        {
           echo("
              <script>
                window.alert('비밀번호가 틀립니다.');
                history.go(-1);
              </script>
           ");

           exit;
        }
        else
        {
           $userid = $row[id];
		   $username = $row[name];
		   $usernick = $row[nick];
		   $userlevel = $row[level];
		   
		   if($userid !== $id)
		   {
		       echo("
              <script>
                window.alert('아이디가 일치하지않습니다.');
                history.go(-1);
              </script>
           ");
		       
		       exit;
		   }

           $_SESSION['userid'] = $userid;
           $_SESSION['username'] = $username;
           $_SESSION['usernick'] = $usernick;
           $_SESSION['userlevel'] = $userlevel;

           echo("
              <script>
                location.href = '../index.php';
              </script>
           ");
        }
   }          
?>
