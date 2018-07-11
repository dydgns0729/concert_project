<?php
    session_start();
    
    define(SCALE, 5);   //상수 정의
    include_once "../lib/dbconn.php";
    $userid=$_SESSION['userid'];
    
    $flag = "NO";
    $sql = "show tables from classDB";
    $result = mysqli_query($con, $sql) or die("실패원인:".mysqli_error($con));
    while($row=mysqli_fetch_row($result)){
        if($row[0]==="memo"){
            $flag ="OK";
            break;
        }
    }
    
    if($flag !=="OK"){
        $sql= "create table memo (
                  num int not null auto_increment,
                  id char(15) not null,
                  name char(10) not null,
                  nick char(10) not null,
                  content text not null,
                  regist_day char(20),
                  primary key(num)
               )";
        if(mysqli_query($con,$sql)){    //mysqli_query = db처리후 결과 or 처리후 성공 실패여부
            echo "<script>alert('memo 테이블이 생성되었습니다.')</script>";
        }else{
            echo "실패원인:".mysqli_query($con);
        }
    }
    $flag2 = "NO";
    $sql = "show tables from classDB";
    $result = mysqli_query($con, $sql) or die("실패원인:".mysqli_error($con));  //$result = 결과값을 table구조로 받는다.
    while($row=mysqli_fetch_row($result)){
        if($row[0]==="memo_ripple"){
            $flag2 ="OK";
            break;
        }
    }
    
    if($flag2 !=="OK"){
        $sql= "create table memo_ripple (
                  num int not null auto_increment,
                  parent int not null,
                  id char(15) not null,
                  name char(10) not null,
                  nick char(10) not null,
                  content text not null,
                  regist_day char(20),
                  primary key(num)
               )";
        if(mysqli_query($con,$sql)){
            echo "<script>alert('memo_ripple 테이블이 생성되었습니다.')</script>";
        }else{
            echo "실패원인:".mysqli_query($con);
        }
    }
    
    if (empty($_GET['page'])){  // 페이지번호($page)가 0 일 때
        $page = 1;              // 페이지 번호를 1로 초기화
    }else{
        $page = $_GET['page'];
    }
    
    $sql="select * from memo order by num desc";  //내림차순정렬
    $result=mysqli_query($con, $sql);   //$result에는 선택된 table을가져오는데 첫번째 레코드를 가리키고있다
    $total_record=mysqli_num_rows($result);//전체 글의 개수
    
    //전체 페이지 수($total_page) 계산
    if(($total_record % SCALE)==0){
        $total_page=floor($total_record/SCALE);
    }else{
        $total_page=floor($total_record/SCALE)+1;
    }
    
        
        // 표시할 페이지($page)에 따라 $start 계산
    $start = ($page - 1) * SCALE;   //전체 페이지에서 시작위치를 설정하기위한 변수
        //auto로 num을 저장하기때문에 출력할때 
    $number = $total_record - $start;
?>
<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
<link href="../css/common.css" rel="stylesheet" type="text/css" media="all">
<link href="../css/memo.css" rel="stylesheet" type="text/css" media="all">
</head>

<body>
<div id="wrap">
  <div id="header">
    <?php include "../lib/top_login2.php"; ?>
  </div>  <!-- end of header -->

  <div id="menu">
	<?php include "../lib/top_menu2.php"; ?>
  </div>  <!-- end of menu --> 

  <div id="content">    
	<div id="col1">
		<div id="left_menu">
<?php
			include "../lib/left_menu.php";
?>
		</div>
	</div>
	<div id="col2">  
		<div id="title">
			<img src="../img/title_memo.gif">
		</div>

		<div id="memo_row1">
       	<form  name="memo_form" method="post" action="insert.php"> 
			<div id="memo_writer"><span >▷ <?= $_SESSION['usernick'] ?></span></div>
			<div id="memo1"><textarea rows="6" cols="95" name="content"></textarea></div>
			<div id="memo2"><input type="image" src="../img/memo_button.gif"></div>
		</form>	
		</div> <!-- end of memo_row1 -->
<?php
for ($i=$start; $i<$start+SCALE && $i < $total_record; $i++)                    
   {
      mysqli_data_seek($result, $i);  
      $row = mysqli_fetch_array($result);       
	
	  $memo_id      = $row[id];
	  $memo_num     = $row[num];
      $memo_date    = $row[regist_day];
	  $memo_nick    = $row[nick];

	  $memo_content = str_replace("\n", "<br>", $row[content]);
	  $memo_content = str_replace(" ", "&nbsp;", $memo_content);
?>
		<div id="memo_writer_title">
		<ul>
		<li id="writer_title1"><?= $number ?></li>
		<li id="writer_title2"><?= $memo_nick ?></li>
		<li id="writer_title3"><?= $memo_date ?></li>
		<li id="writer_title4"> 
		      <?php
					if($userid=="admin" || $userid==$memo_id)
			          echo "<a href='delete.php?num=$memo_num'>[삭제]</a>"; 
			  ?>
		</li>
		</ul>
		</div>
		<div id="memo_content"><?= $memo_content ?>
		</div>
		<div id="ripple"> 
			<div id="ripple1">덧글</div>
			<div id="ripple2">
<?php
	    $sql = "select * from memo_ripple where parent='$memo_num'";
	    $ripple_result = mysqli_query($con,$sql);

		while ($row_ripple = mysqli_fetch_array($ripple_result))
		{
			$ripple_num     = $row_ripple[num];
			$ripple_id      = $row_ripple[id];
			$ripple_nick    = $row_ripple[nick];
			$ripple_content = str_replace("\n", "<br>", $row_ripple[content]);
			$ripple_content = str_replace(" ", "&nbsp;", $ripple_content);
			$ripple_date    = $row_ripple[regist_day];
?>
				<div id="ripple_title">
				<ul>
				<li><?= $ripple_nick ?> &nbsp;&nbsp;&nbsp; <?= $ripple_date ?></li>
				<li id="mdi_del">
					<?php
						if($userid=="admin" || $userid==$ripple_id)
				            echo "<a href='delete_ripple.php?num=$ripple_num'>삭제</a>";
					?>
				</li>
				</ul>
				</div>
				<div id="ripple_content"> <?= $ripple_content ?></div>
<?php
		}
?>
				<form  name="ripple_form" method="post" action="insert_ripple.php"> 
				<input type="hidden" name="num" value="<?= $memo_num ?>"> 
				<div id="ripple_insert">
				    <div id="ripple_textarea">
						<textarea rows="3" cols="80" name="ripple_content"></textarea>
					</div>
					<div id="ripple_button"><input type="image" src="../img/memo_ripple_button.png"></div>
				</div>
				</form>

			</div> <!-- end of ripple2 -->
  		    <div class="clear"></div>
			<div class="linespace_10"></div>
<?php
		$number--;
	 }
	 mysqli_close($con);
	 
?>
<div id="page_num">
      
<?php 
     if($page != 1){
         $page_dlwjs = $page-1;
         echo"<a href='memo.php?page=$page_dlwjs'>◀ 이전 &nbsp;&nbsp;&nbsp;&nbsp</a>";
     }else{
        echo"◀ 이전 &nbsp;&nbsp;&nbsp;&nbsp";
     }
    
      for($i=1;$i<=$total_page;$i++){
          if($page == $i){
                echo"<b>$i</b>";
          }else{
              echo"<a href='memo.php?page=$i'>$i</a>";
          }
      }
    
      if($page !=$total_page){
           $page_daum = $page+1;
           echo"<a href='memo.php?page=$page_daum'>&nbsp;&nbsp;&nbsp;&nbsp;다음 ▶</a>";
      }else{
           echo"&nbsp;&nbsp;&nbsp;&nbsp;다음 ▶";
      }
?>

</div>
		 </div> <!-- end of ripple -->
	</div> <!-- end of col2 -->
  </div> <!-- end of content -->
</div> <!-- end of wrap -->

</body>
</html>
