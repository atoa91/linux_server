
<?php
    include("session.php");
	
	session_start();
	#keyword regular expression
	$pattern = '/(#([\xEA-\xED][\x80-\xBF]{2}|[0-9]|[a-zA-Z]|[_])+)/';
 ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta http-equiv= "Content-Type" content="text/html; charset=utf8">

		<title>php test</title>

	  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/materialize.min.css">

	  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>
		<script type="text/javascript">
      $(document).ready(function(){

    		$('select').material_select();

				$('.button-collapse').sideNav({
					 menuWidth: 300, // Default is 240
			 	});

			});

		</script>
	</head>
	<body>
		<img class="logo_img" src="img/logo.png" alt="" />
		<nav class="z-depth-0 top_nav row valign-wrapper">
			<div class="col s12 m8 search_wrapper valign-wrapper">
		    <input type="text" class="search_nav" placeholder="Search Products">
		    <span class="search_icon_nav"><a href="#"><img src="img/search.png" alt=""></a></span>
		  </div>
			<div class="offset-m3 col s12 m1 logout_wrapper center-align">
				<a class="red-text" href="logout.php">로그아웃</a>
			</div>
			<ul id="slide-out" class="side-nav fixed">
				<li class="active"><a href="list.php">작품관리</a></li>
				<li><a href="#!">뉴스 / 공지사항</a></li>
				<li><a href="#!">작가노트 / 평론</a></li>
			</ul>
		</nav>

		<div class="body_wrapper row">
			<div class="col s12 m12">
				<div class="col s12 m12 search_box">
					<h5>검색창</h5>
					<form name='form1' method="post" action="list.php">
						<div class="row">
		          <div class="input-field col s2 m2">
								<input id="input_text" type="text" length="10" name="search_title">
		            <label for="input_text">제목 검색하기</label>
		          </div>

		          <div class="input-field col s2 m2">
							  <select name ="search_year">
									<option value="1980" >   1980's    </option>
									<option value="1990" >   1990's   </option>
									<option value="2000" >   2000's    </option>
									<option value="2010" >   2010's    </option>
									<option value="2015" >   2015    </option>
									<option value="2016" >   2016    </option>
							  </select>
							  <label>작품년도</label>
							</div>

		          <div class="input-field col s2 m2">
								<select name ="search_theme">
									<option value="혼합기법" >   혼합기법    </option>
									<option value="조각" >   조각   </option>
									<option value="미디어아트" >   미디어아트    </option>
									<option value="태피스트리" >   태피스트리    </option>
									<option value="한지" >   한지    </option>
									<option value="판화" >   판화    </option>
								</select>
							  <label>기법구분</label>
							</div>

		          <div class="input-field col s2 m2">
								<select name ="search_exhibit">
									<option value="" >   기법    </option>
									<option value="" >   조각   </option>
									<option value="" >   미디어아트    </option>
									<option value="" >   태피스트리    </option>
									<option value="" >   한지    </option>
									<option value="" >   판화    </option>
								</select>
							  <label>전시구분</label>
							</div>
							<div class="input-field col s1 m1 valign-wrapper">
								<input type="submit" class="btn search_btn" value="검색">
							</div>
						</div>
					</form>

					<FORM name='form2' method="post" action="list.php">
						<div class="row">
		          <div class="input-field col s3 m3">
								<input type="text" name="search_tag">
		            <label for="input_text">#태그 검색하기 :</label>
		          </div>
							<div class="input-field col s1 m1">
								<input class="btn search_btn" type="submit" value="검색">
							</div>

							<?php
								require_once("myDB.php");
								$pdo = db_connect();
								// tag search
 								if(isset($_GET['search_tag'])) {
								$search_tag= $_GET['search_tag'];
								$subString .= '&amp;search_tag='.$search_tag;
								}

								if(isset($_GET['search_year'])) {
								$search_year= $_GET['search_year'];
								$subString .= '&amp;search_year='.$search_year;
								}

								if(isset($_GET['search_title'])) {
								$search_title= $_GET['search_title'];
								$subString .= '&amp;search_title='.$search_title;
								}
								if(isset($_GET['search_theme'])) {
								$search_theme= $_GET['search_theme'];
								$subString .= '&amp;search_theme='.$search_theme;
								}

								//paging
 								if(isset($_GET['page'])) {
									$page = $_GET['page'];
								} else {
									$page = 1;
								}
								//paging start
 								// control pages
 								$onePage = 1; //한페이지에 보여줄 게시글 수
								$oneSection = 10; //한번에 보여줄 페이지 개수
								$paging = '<ul>'; // 페이징을 저장할 변수
								$currentLimit = ($onePage * $page) - $onePage; //몇 번째의 글부터 가져오는지
								$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; //limit sql 구문

							

								//deletion.
								if(isset($_GET['action'])&& $_GET['action'] == 'delete'){

							    $sql['pictures'] ="DELETE FROM pictures where pid =:pid";
							    $sql['tags'] = "DELETE FROM tags where pid =:pid1";
							    $pdo-> beginTransaction();

							   try{
							     foreach($sql as $stmh_name => &$sql_command)
							     {
							       $stmh[$stmh_name]=$pdo->prepare($sql_command);
							     }

							     $pid = $_GET['pid'];
							     $stmh['pictures'] -> bindValue(':pid', $pid, PDO::PARAM_INT);
							     $stmh['pictures']->execute();
							     $stmh['tags'] -> bindValue(':pid1',$pid,PDO::PARAM_INT);
							     $stmh['tags']->execute();

							     $pdo-> commit();

							   }
							   catch(PDOException $Exception){
							     $pdo -> rollBack();
							     print "삭제 오류났다:".$Exception->getMessage();
							   }
							 }


								//insertion
								if(isset($_SESSION["reup"])&&isset($_POST['action']) && $_POST['action'] == 'insert'){

								foreach($_FILES["upload"]["name"] as $key => $values){

									$file_dir = '/var/www/html/images/';

									//set the directory '0' if thereis no folder at all
									if(max(scandir($file_dir))=='..'){
										@mkdir('images/0',0777);
										$file_dir ='/var/www/html/images/0/';
										$img_dir = "./images/0/";
									}
									// set directory auto_increment if there is integer folder
									else
									{
										$num=max(scandir($file_dir))+1;
										@mkdir('images/'.$num,0777);
										$file_dir = $file_dir.$num.'/';
										$img_dir = "./images/".$num.'/';
									}

									//file number is exactly same with directory number.
									$file_path = $file_dir.$num;

									if($_FILES['upload']['error'][$key] === UPLOAD_ERR_OK ){

									    $tmp_name = $_FILES["upload"]["tmp_name"][$key];
									    move_uploaded_file($tmp_name, $file_path);
									    $img_path= $img_dir.$num;
										$size = getimagesize($file_path);
										// transaction start.
										// register in pictures table.
										try{

											$pdo->beginTransaction();
											$sql = "insert into pictures (title,year,loc,size,theme,comment) values(:title,:year,:loc,:size,:theme,:comment)";
											$stmh = $pdo-> prepare($sql);
											//파일 이름은 한국어로 저장.
											$stmh-> bindValue(":title", $_POST['title'],PDO::PARAM_STR);
											$stmh-> bindValue(":year", $_POST['year'],PDO::PARAM_INT);
											$stmh-> bindValue(":loc", $img_path,PDO::PARAM_STR);
											$stmh-> bindValue(":size", $size[3] ,PDO::PARAM_STR);
											$stmh-> bindValue(":theme", $_POST['theme'],PDO::PARAM_STR);
											$stmh-> bindValue(":comment", $_POST['comment'], PDO::PARAM_STR);
											$stmh-> execute();
											$pdo-> commit();
											session_unset();

										}catch(PDOException $Exception){
											$pdo->rollBack();
											print "등록 오류났다:".$Exception->getMessage()."<br/>";
										}


									// register in tags table.
									if( $_POST['keywords'] != ""){

										try{
											// getting tags.
											preg_match_all($pattern, $_POST['keywords'], $match);
											$keywords = implode('', $match[0]);
											$strTok = explode('#', $keywords);
										 	array_shift($strTok);

										 	$pdo->beginTransaction();
											$sql = "select pid from pictures where title = :title";
											$stmh = $pdo->prepare($sql);
											$stmh-> bindValue(":title", $_POST['title'], PDO::PARAM_STR);
											$stmh->execute();
											$pdo-> commit();

											$row = $stmh->fetch(PDO::FETCH_ASSOC);
										 	$pid = $row['pid'];

										 	// sql starts.
										 	if(isset($strTok)){
										 		$cnt = count($strTok);

										 		for($i = 0 ; $i < $cnt ; $i++){
													$pdo->beginTransaction();
													$sql = "insert into tags (pid, keyword) values(:pid,:keyword)";
													$stmh = $pdo-> prepare($sql);
													$stmh-> bindValue(":pid", $pid ,PDO::PARAM_INT);
													$stmh-> bindValue(":keyword", $strTok[$i], PDO::PARAM_STR);
													$stmh-> execute();
													$pdo-> commit();
												}
										 	}
										}
										catch(PDOException $Exception){
											$pdo->rollBack();
											print "등록 오류났다:".$Exception->getMessage()."<br/>";
										}
									}

									}
									}
							}


								//update.
								if(isset($_SESSION["reupdate"])&&isset($_POST['action'])&& $_POST['action'] == 'update'){

									$title2 = ($_POST['title2'] == "")? $_SESSION['title'] : $_POST['title2'] ;
									$year2 = ($_POST['year2'] == "")? $_SESSION['year'] : $_POST['year2'] ;
									$theme2 = ($_POST['theme2'] == "")? $_SESSION['theme'] :$_POST['theme2'] ;
									$comment2 = ($_POST['comment2'] == "")? $_SESSION['comment'] : $_POST['comment2'] ;
									$pid = $_POST['pid'];

									try{

							 		$pdo-> beginTransaction();
							 		$sql ="update pictures set
							 				title = :title2 , year = :year2 , theme = :theme2, comment = :comment2
							 				where pid = :pid limit 1";
							 		$stmh = $pdo->prepare($sql);
							 		$stmh ->bindValue(':title2', $title2, PDO::PARAM_STR);
							 		$stmh -> bindValue(':pid', $pid, PDO::PARAM_INT);
							 		$stmh -> bindValue(':year2', $year2, PDO::PARAM_INT);
							 		$stmh -> bindValue(':theme2', $theme2, PDO::PARAM_STR);
							 		$stmh -> bindValue(':comment2', $comment2, PDO::PARAM_STR);

							 		$stmh->execute();
							 		$pdo-> commit();
							 	}
							 	catch(PDOException $Exception){
							 		$pdo -> rollBack();
							 		print "수정 오류났다:".$Exception->getMessage();
							 	}

							 	if( $_POST['keywords2'] != ""){

									try{
										// getting tags.
										preg_match_all($pattern, $_POST['keywords2'], $match);
										$keywords = implode('', $match[0]);
										$strTok = explode('#', $keywords);
										array_shift($strTok);

									 	// sql starts.
									 	if(isset($strTok)){
									 		$cnt = count($strTok);

									 		$sql = "delete from tags where pid = :pid";
											$stmh = $pdo-> prepare($sql);
											$stmh-> bindValue(":pid", $pid ,PDO::PARAM_INT);
											$stmh-> execute();

										 	for($i = 0 ; $i < $cnt ; $i++){
										 		$pdo->beginTransaction();
												$sql = "insert into tags (pid, keyword) values(:pid,:keyword)";
												$stmh = $pdo-> prepare($sql);
												$stmh-> bindValue(":pid", $pid ,PDO::PARAM_INT);
												$stmh-> bindValue(":keyword", $strTok[$i], PDO::PARAM_STR);
												$stmh-> execute();
												$pdo-> commit();
											}
											unset($_SESSION['reupdate']);
									 	}
									}
									catch(PDOException $Exception){
										$pdo->rollBack();
										print "수정 오류났다:".$Exception->getMessage()."<br/>";
									}
								}
							}
							else
								echo $_POST['comment2'];


								//Search & Show the status.
								//receive data
							$search_title = '%'.$_GET['search_title'].'%';
							$search_year = '%'.$_GET['search_year'].'%';
							$search_theme = '%'.$_GET['search_theme'].'%';
							$search_tag = '%'.$_GET['search_tag'].'%';
							//set the case
							//연도로만 검색
							if($search_title == "%%" && $search_year !="%%" && $search_theme=="%%")
								$check = 1;
							//테마로만 검색
							else if($search_title == "%%" && $search_year =="%%" and $search_theme !="%%")
								$check = 2;
							//연도와 테마로 검색
							else if($search_title == "%%" && $search_year !="%%" and $search_theme !="%%")
								$check = 3;
							//이름으로 검색
							else if($search_title != "%%" && $search_year =="%%" and $search_theme =="%%")
									$check = 4;
								//이름이랑 연도로 검색
								else if($search_title != "%%" && $search_year !="%%" and $search_theme =="%%")
									$check = 5;
								//이름, 연도, 테마로 검색
								else if($search_title != "%%" && $search_year !="%%" and $search_theme !="%%")
									$check = 6;
								//이름, 테마로 검색
								else if($search_title != "%%" && $search_year =="%%" and $search_theme !="%%")
									$check = 7;
								//tag로 검색
								else if($search_tag != "%%")
									$check = 8;
								else
									$check = 9;



							//setting sql query.
							switch ($check) {
								case 1:
									# code...
									try{
										$sql = "select * from pictures where year like :year";
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':year',$search_year, PDO::PARAM_INT);
										$stmh->execute();
										$count = $stmh->rowCount();
										$sql = "select * from pictures where year like :year".$sqlLimit;
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':year',$search_year, PDO::PARAM_INT);
										$stmh->execute();
										print "<div class='picture_count col s5 m5'>".$count." pictures are found.</div>";
									}catch(PDOException $Exception){
										print "Error:".$Exception->getMessage();
									}
									break;

								case 2:

									try{
										$sql = "select * from pictures where theme like :theme";
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':theme',$search_theme, PDO::PARAM_STR);
										$stmh->execute();
										$count = $stmh->rowCount();
										print "<div class='picture_count col s5 m5'>".$count." pictures are found.</div>";
										$sql = "select * from pictures where theme like :theme".$sqlLimit;
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':theme',$search_theme, PDO::PARAM_STR);
										$stmh->execute();
									}catch(PDOException $Exception){
										print "Error:".$Exception->getMessage();
									}

									break;

								case 3:

									try{
										$sql = "select * from pictures where year like :year and theme like :theme";
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':year',$search_year, PDO::PARAM_INT);
										$stmh->bindValue(':theme',$search_theme, PDO::PARAM_STR);
										$stmh->execute();
										$count = $stmh->rowCount();
										print "<div class='picture_count col s5 m5'>".$count." pictures are found.</div>";
										$sql = "select * from pictures where year like :year and theme like :theme".$sqlLimit;
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':year',$search_year, PDO::PARAM_INT);
										$stmh->bindValue(':theme',$search_theme, PDO::PARAM_STR);
										$stmh->execute();
									}catch(PDOException $Exception){
										print "Error:".$Exception->getMessage();
									}

									break;
								//이름으로 검색
								case 4:

									try{
										$sql = "select * from pictures where title like :title";
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':title',$search_title, PDO::PARAM_STR);
										$stmh->execute();
										$count = $stmh->rowCount();
										print "<div class='picture_count col s5 m5'>".$count." pictures are found.</div>";
										$sql = "select * from pictures where title like :title".$sqlLimit;
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':title',$search_title, PDO::PARAM_STR);
										$stmh->execute();
									}catch(PDOException $Exception){
										print "Error:".$Exception->getMessage();
									}

									break;
								//이름이랑 연도로 검색
								case 5:

									try{
										$sql = "select * from pictures where year like :year and title like :title";
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':year',$search_year, PDO::PARAM_INT);
										$stmh->bindValue(':title',$search_title, PDO::PARAM_STR);
										$stmh->execute();
										$count = $stmh->rowCount();
										print "<div class='picture_count col s5 m5'>".$count." pictures are found.</div>";
										$sql = "select * from pictures where year like :year and title like :title".$sqlLimit;
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':year',$search_year, PDO::PARAM_INT);
										$stmh->bindValue(':title',$search_title, PDO::PARAM_STR);
										$stmh->execute();
									}catch(PDOException $Exception){
										print "Error:".$Exception->getMessage();
									}

									break;
								//이름, 연도, 테마로 검색
								case 6:

									try{
										$sql = "select * from pictures where year like :year and theme like :theme and title like :title";
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':year',$search_year, PDO::PARAM_INT);
										$stmh->bindValue(':theme',$search_theme, PDO::PARAM_STR);
										$stmh->bindValue(':theme',$search_title, PDO::PARAM_STR);
										$stmh->execute();
										$count = $stmh->rowCount();
										print "<div class='picture_count col s5 m5'>".$count." pictures are found.</div>";
										$sql = "select * from pictures where year like :year and theme like :theme and title like :title".$sqlLimit;
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':year',$search_year, PDO::PARAM_INT);
										$stmh->bindValue(':theme',$search_theme, PDO::PARAM_STR);
										$stmh->bindValue(':theme',$search_title, PDO::PARAM_STR);
										$stmh->execute();
									}catch(PDOException $Exception){
										print "Error:".$Exception->getMessage();
									}

									break;
								//이름, 테마로 검색
								case 7:

									try{
										$sql = "select * from pictures where title like :title and theme like :theme";
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':title',$search_title, PDO::PARAM_INT);
										$stmh->bindValue(':theme',$search_theme, PDO::PARAM_STR);
										$stmh->execute();
										$count = $stmh->rowCount();
										print "<div class='picture_count col s5 m5'>".$count." pictures are found.</div>";
										$sql = "select * from pictures where title like :title and theme like :theme".$sqlLimit;
										$stmh = $pdo->prepare($sql);
										$stmh->bindValue(':title',$search_title, PDO::PARAM_INT);
										$stmh->bindValue(':theme',$search_theme, PDO::PARAM_STR);
										$stmh->execute();
									}catch(PDOException $Exception){
										print "Error:".$Exception->getMessage();
									}

									break;
								// tag로 검색
								case 8:

									  try{
								        $sql = "select distinct * from tags inner join pictures on tags.pid=pictures.pid where tags.keyword like :keyword";
								        $stmh = $pdo->prepare($sql);
								        $stmh->bindValue(':keyword',$search_tag, PDO::PARAM_STR);
								        $stmh->execute();
								        $count = $stmh->rowCount();
								        print "<div class='picture_count col s5 m5'>".$count." pictures are found.</div>";
								        $sql = "select distinct * from tags inner join pictures on tags.pid=pictures.pid where tags.keyword like :keyword".$sqlLimit;
								        $stmh = $pdo->prepare($sql);
								        $stmh->bindValue(':keyword',$search_tag, PDO::PARAM_STR);
								        $stmh->execute();



								      }catch(PDOException $Exception){
								        print "Error:".$Exception->getMessage();
								      }

								      break;

								default:
									# code...
									try{
										$sql = "select * from pictures";
										$stmh = $pdo->prepare($sql);
										$stmh->execute();
										$count = $stmh->rowCount();
										print "<div class='picture_count col s5 m5'>".$count." pictures are found.</div>";
										$sql = "select * from pictures".$sqlLimit;
										$stmh = $pdo->prepare($sql);
										$stmh->execute();
									}catch(PDOException $Exception){
										print "Error:".$Exception->getMessage();
									}
							}

							//Show the result of query.
								if($count < 1){
										//print $search_title;
										//print $search_year;
										//print $search_theme;
										//print $check."<br/>";
										print "There is no picture.<BR/>";
										break;


							}else{

								//paging

								//paging start
										
										$allPost = $count;
									
										$allPage = ceil($allPost / $onePage); //전체 페이지 수 
							
										if($page <1 && $page > $allPage){
											?>
											<script>
											alert("존재하지 않는 페이지 입니다.");
											history.back();
											</script>
										<?php
											exit;
										}

	
										$currentSection = ceil($page / $oneSection);
										$allSection = ceil($allPage / $oneSection); //전체 섹션의 수 

										$firstPage = ($currentSection * $oneSection) - ($oneSection - 1); //현재 섹션의 처음 페이지

										if($currentSection == $allSection){
											$lastPage = $allPage; //현재 섹션이 마지막 섹션이라면 $allPage가 마지막이된다.
										}else{
											$lastPage = $currentSection * $oneSection; //현재 섹션의 마지막 페이지
										}

										$prevPage = (($currentSection -1)* $oneSection);
										$nextPage = (($currentSection +1)* $oneSection) - ($oneSection-1);

										$paging = '<ul>'; // 페이징을 저장할 변수


										//첫 페이지 가아니라면 처음 버튼을 생성
										if($page != 1){
											$paging.='<a href="./list.php?page=1'.$subString.'"> 처음 </a>';
										}
										//첫 섹션이 아니라면 이전 버튼을 생성
										if($currentSection != 1){
											$paging.='<a href="./list.php?page='.$prevPage.$subString.'"> 이전 </a>';
										}

										for($i = $firstPage; $i <= $lastPage; $i++) {
											if($i == $page) {
												$paging .= $i ;
											} else {
												$paging .= '<a href="./list.php?page='.$i.$subString.'"> ' .$i. ' </a>';
											}
										}

											//마지막 섹션이 아니라면 다음 버튼을 생성
										if($currentSection != $allSection) { 
											$paging .= '<a href="./list.php?page=' .$nextPage .$subString.'"> 다음 </a>';
										}
										//마지막 페이지가 아니라면 끝 버튼을 생성
										if($page != $allPage) { 
											$paging .= '<a href="./list.php?page=' .$allPage .$subString.'"> 끝 </a>';
										}
										$paging .= '</ul>';


									//paging end

							?>

						</div>
					</FORM>
				</div>

	 			<a href="upload1.php" class="btn"><i class="material-icons left">library_add</i>신규작품업로드</a>

				<table class="highlight centered">
					<thead>
					 	<tr>
					 		<th >상세보기</th>
					 		<th >작품명</th>
					 		<th >부가설명</th>
							<th >작품년도 </th>
					 		<th >기법구분</th>
					 		<th >전시구분</th>
					 		<th >키워드 태그</th>
							<th >수정</th>
					 		<th >삭제</th>
					 	</tr>
					</thead>
				 	<tbody>
					 	<?php
					 		while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
					 			$name4 = rawurlencode($row['title']);
					 			$pid = $row['pid'];
					 			$keywords = "";

					 			$sql2 = "select keyword from tags where pid = :pid";
								$stmh2 = $pdo->prepare($sql2);
								$stmh2 -> bindValue(":pid", $pid, PDO::PARAM_INT);
								$stmh2->execute();
								$count2 = $stmh2->rowCount();

								$i = 0;
								while( $i < $count2 ){
									//whenever do fetch(PDO::FETCH_ASSOC), next value is updated.
									$tag = $stmh2->fetch(PDO::FETCH_ASSOC);
									$keywords .= "#".$tag['keyword']."<br/>";
									$i++;
								}
					 	?>
					 	<tr>
					 		<td align="center"><img width="300px" src="<?=htmlspecialchars($row["loc"])?>"></td>
					 		<td align="center" ><?=htmlspecialchars($row['title'])?></td>
					 		<td align="center" ><?=nl2br(htmlspecialchars($row['comment']))?></td>
					 		<td align="center" ><?=htmlspecialchars($row['year'])?></td>
					 		<td align="center" ><?=htmlspecialchars($row['theme'])?></td>
					 		<td align="center" ><?=htmlspecialchars($row['exhibit'])?></td>
					 		<td align="center" ><?=$keywords?></td>
							<td align="center" ><a class="btn small_btn" href = update1.php?action=update&pid=<?=$pid?>>Modify</a></td>
							<td align="center" ><a class="btn small_btn" href = list.php?action=delete&pid=<?=$pid?>>delete</a></td>

						</tr>
						<?php
					 	}
					 	?>
				 	</tbody>
			 	</table>
				<?php
			 	}
			 	?>

			</div>
		</div>
		<!--show page -->
 	<div class="paging"><?php echo $paging ?></div>
	</body>
</html>
