<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head> 
<style type="text/css">
div.latest_box{
width: 500px;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="css/common.css">
</head>

<body>
<div id="wrap">
	<div id="header">
    <?php include "./lib/top_login1.php"; ?>
	</div>  <!-- end of header -->

	<div id="menu">
	<?php include "./lib/top_menu1.php"; ?>
	</div>  <!-- end of menu --> 

  <div id="content">
		<div id="main_img"><img src="./img/main_img.jpg"></div>
  
  <?php include "./lib/func.php"; ?>
		<div id="latest">
			<div id="latest1">
				<div id="title_latest1"><img src="./img/title_latest1.gif"></div>
	  			<div class="latest_box">
				<?php latest_article("greet", 9, 25); ?>
				</div>
			</div>
			<div id="latest2">
				<div id="title_latest2"><img src="./img/title_latest2.gif"></div>
	  			<div class="latest_box">
				<?php latest_article("concert", 9, 25); ?>
				</div>
			</div>
		</div>
  
  </div> <!-- end of content -->
</div> <!-- end of wrap -->

</body>
</html>
