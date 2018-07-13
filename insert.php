<?php session_start(); ?>

<meta charset="utf-8">
<?php
    include "../lib/dbconn.php";
    $regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장

	if(!empty($_GET['table'])){
	    $table=$_GET['table'];
	}
	if(!empty($_GET['num'])){
	    $num=$_GET['num'];
	}
	if(!empty($_GET['page'])){
	    $page=$_GET['page'];
	}
	if(!empty($_GET['mode'])){
	    $mode=$_GET['mode'];
	}
	if(!empty($_POST['subject'])){
	    $subject=htmlspecialchars($_POST['subject']);
	}
	if(!empty($_POST['content'])){
	    $content=htmlspecialchars($_POST['content']);
	}
	
	if(empty($userid)) {
		echo("
		<script>
	     window.alert('로그인 후 이용해 주세요.')
	     history.go(-1)
	   </script>
		");
		exit;
	}
	
	/*   단일 파일 업로드 
	$upfile_name	 = $_FILES["upfile"]["name"];
	$upfile_tmp_name = $_FILES["upfile"]["tmp_name"];
	$upfile_type     = $_FILES["upfile"]["type"];
	$upfile_size     = $_FILES["upfile"]["size"];
	$upfile_error    = $_FILES["upfile"]["error"];
	*/

	// 다중 파일 업로드
	$files = $_FILES["upfile"];    //다중파일을 보낼때 배열로 묶어서 가능 name=upfile[]
	$count = count($files["name"]);
	var_dump($_FILES);
	
	$upload_dir = './data/';

	for ($i=0; $i<$count; $i++){
		$upfile_name[$i]     = $files["name"][$i];    //실제파일명 배열
		$upfile_tmp_name[$i] = $files["tmp_name"][$i];    //서버에 저장되는 임시 파일명 배열
		$upfile_type[$i]     = $files["type"][$i];    //업로드 파일 형식 배열
		$upfile_size[$i]     = $files["size"][$i];    //업로드 파일 크기 배열 1바이트 단위
		$upfile_error[$i]    = $files["error"][$i];   //에러발생 확인 배열
      
		$file = explode(".", $upfile_name[$i]);
		$file_name = $file[0];
		$file_ext  = $file[1];
		
		
		if (!$upfile_error[$i]){
			$new_file_name = date("Y_m_d_H_i_s");
			$new_file_name = $new_file_name."_".$i;
			$copied_file_name[$i] = $new_file_name.".".$file_ext;      
			$uploaded_file[$i] = $upload_dir.$copied_file_name[$i];

			if( $upfile_size[$i]  > 80000000 ){
				echo("
				<script>
				alert('업로드 파일 크기가 지정된 용량(8MB)을 초과합니다!\\n파일 크기를 체크해주세요! ');
				history.go(-1)
				</script>
				");
				exit;
			}

			if(!move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i])){
				echo("
					<script>
					alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
					history.go(-1)
					</script>
				");
				exit;
			}
		}
	}

	if ($mode=="modify"){
		$num_checked = count($_POST['del_file']);
		$position = $_POST['del_file'];

		for($i=0; $i<$num_checked; $i++)                      // 삭제 표시할 항목
		{
			$index = $position[$i];
			$del_ok[$index] = "y";
		}

		$sql = "select * from $table where num=$num";     //수정할 레코드 검색
		$result = mysqli_query($con,$sql)or die("실패원인:".mysqli_error($con));
		$row = mysqli_fetch_array($result);

		for ($i=0; $i<$count; $i++)					// db정보 갱신
		{

		    $field_org_name = "file_name_".$i;    
		    $field_real_name = "file_copied_".$i; 
		    $field_real_type = "file_type_".$i;

			$org_name_value = $upfile_name[$i];  //실제 파일명
			$org_real_value = $copied_file_name[$i]; //저장된 파일명
			$org_type_value = $upfile_type[$i];
			if ($del_ok[$i] == "y"){
				$delete_field = "file_copied_".$i;
				$delete_name = $row[$delete_field]; //삭제할 파일명
				
				$delete_path = "./data/".$delete_name;

				unlink($delete_path);   //경로에있는파일 삭제

				$sql = "update $table set $field_org_name = '$org_name_value', $field_real_name = '$org_real_value',$field_real_type = '$org_type_value'  where num=$num";
				mysqli_query($con,$sql)or die("실패원인:".mysqli_error($con));  // $sql 에 저장된 명령 실행
			}
			else{
				if (!$upfile_error[$i]){
					$sql = "update $table set $field_org_name = '$org_name_value', $field_real_name = '$org_real_value',$field_real_type = '$org_type_value'  where num=$num";
					mysqli_query($con,$sql)or die("실패원인:".mysqli_error($con));  // $sql 에 저장된 명령 실행					
				}
			}

		}
		$sql = "update $table set subject='$subject', content='$content' where num=$num";
		mysqli_query($con,$sql)or die("실패원인:".mysqli_error($con));  // $sql 에 저장된 명령 실행
	}
	else{
		$sql = "insert into $table (id, name, nick, subject, content, regist_day, hit, ";
		$sql .= " file_name_0, file_name_1, file_name_2, file_copied_0,  file_copied_1, file_copied_2,file_type_0,file_type_1,file_type_2) ";
		$sql .= "values('$userid', '$username', '$usernick', '$subject', '$content', '$regist_day', 0,";
		$sql .= "'$upfile_name[0]', '$upfile_name[1]',  '$upfile_name[2]', '$copied_file_name[0]', '$copied_file_name[1]','$copied_file_name[2]','$upfile_type[0]','$upfile_type[1]','$upfile_type[2]')";
		mysqli_query($con,$sql)or die("실패원인:".mysqli_error($con));  // $sql 에 저장된 명령 실행
	}

	mysqli_close($con);                // DB 연결 끊기

	echo "
	   <script>
	    location.href = 'list.php?table=$table&page=$page';
	   </script>
	";
?>

  
