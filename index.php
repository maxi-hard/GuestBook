<html>
	
 <head>
	<meta charset="utf-8">
	<title>Список отзывов</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="shortcut icon" href="/img/favicon.ico" type="image/png">
	
	<script src="/js/functions.js"></script>
	<script src="https://www.google.com/recaptcha/api.js"></script>
 </head>
 
 
 <body>
 	
	<!-- примитивная имитация админки -->
	<form id="form_btn_admin" class="css_auth" id="form_admin" method="post">
		<input class="css_block" type="submit" value="Админка">
		<input type="text" name="make_admin" value=1 style="display: none;">
	</form>
	<form id="form_btn_user" class="css_auth" id="form_user" method="post">
		<input class="css_block" type="submit" value="Юзер-интерфейс">
		<input type="text" name="make_admin" value=0 style="display: none;">
	</form>
	
 	 
	<div class="css_header">
		<div class="css_title">
			Список отзывов
		</div>
		<br/>
		<div id = "div_err" class="css_error" style="display: none;">
			Не удалось загрузить данные из БД!
		</div>
	</div>
	 
	<div class = "css_content">
		<table id = "tbl_reviews">
			<tr id = "tbl_header">
				<th>id</th>
				<th>Название</th>
				<th>email</th>
				<th>Текст</th>
				<th>Одобрен</th>
				<th>Ссылка</th>
			</tr>
		</table>
		
		<button id="btnAddReview">Добавить</button>
		 
	</div>
 
 </body>
</html>
 
 
<?php
	
	
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////
	

	include "vars.php";
	include "functions.php";
	include "dbinteract.php";
	
	
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////
	 	
	 	
 	// примитивная имитация админки
 	
	if (isset($_POST['make_admin'])) {
		setCookieIsAdmin( ($_POST['make_admin'] == 1) ? true : false );
	}
	$isAdmin = getCookieIsAdmin();
	//echo "Admin: " . ( $isAdmin ? 1 : 0 );
	setBlockVisibility( "form_btn_user", $isAdmin );
	setBlockVisibility( "form_btn_admin", !($isAdmin) );

	setBlockVisibility("btnAddReview", !($isAdmin));	// открываем кнопку "Добавить", доступную для юзеров
	echo
		'<script type="text/javascript">' .
		'		addListener(document.getElementById("btnAddReview"), "click", function() { window.location.href="' . buildReviewURL(0) . '" } ); ' .
		'</script>'
		;
	
	
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////
	
	
	// если вернулись со страницы отзыва - обрабатываем POST, чтоб показать ответ
	
	if ( isset($_POST['review_id']) ) {
		
		if ( isset($_POST['review_approve']) )				// код одобрения
		{
			setApproving($_POST['review_id'], true);
	    echo "<p>Одобрен отзыв с ID: " . $_POST['review_id'] . "</p>";
	  }
	  
		if ( isset($_POST['review_decline']) )				// код отклонения
		{
			setApproving($_POST['review_id'], false);
	    echo "<p>Отклонён отзыв с ID: " . $_POST['review_id'] . "</p>";
	  }
	  
		if ( isset($_POST['review_edit']) )						// код редактирования
		{
			
			// проверка данных капчи
			$captcha_result = 0;
		 	if(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response']) {
				$secret = '6LfsuZYUAAAAAJqfIaNrR3zo9HpVelDOY57OGiRe';
				$ip = $_SERVER['REMOTE_ADDR'];
				$response = $_POST['g-recaptcha-response'];
				$rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$ip");
				$arr = json_decode($rsp, TRUE);
				$captcha_result = ( $arr['success'] ? 1 : 0 );
			}
			else {
				$captcha_result = -1; // в POST не переданы данные капчи
			}
			
			// проверка введённых данных
			if ($captcha_result <= 0) {
				echo "<p>Защита от автоматических переходов не пройдена! Данные отзывов не изменены.</p>";
			}
			else if ( ( !isset($_POST['review_title']) ) || ( strlen($_POST['review_title']) < 3 ) ) {
				echo "<p>Введено слишком короткое наименование отзыва (менее 3 символов).</p>";
			}
			else if ( ( !isset($_POST['review_email']) ) || ( filter_var($_POST['review_email'], FILTER_VALIDATE_EMAIL) == false ) ) {
				echo "<p>Введенный e-mail не прошёл проверку на корректность.</p>";
			}
			else if ( ( !isset($_POST['review_text']) ) || ( strlen($_POST['review_text']) < 10 ) ) {
				echo "<p>Введён слишком короткий текст отзыва (менее 10 символов).</p>";
			}
			else {
				updateReview( $_POST['review_id'], $_POST['review_title'], $_POST['review_email'], $_POST['review_text'] );
				echo "<p>Отзыв отправлен на модерацию!</p>";
			}
	  }
	  
  }
	
	
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////
	
	
	// получив данные всех отзывов из БД, динамически наполняем через JS
	// существующую таблицу tbl_reviews полученными отзывами
  
	$rows = getRows();
	if ( is_null($rows) )
	{
		setBlockVisibility("div_err", true);
		setBlockVisibility("tbl_reviews", false);
	}
	else
	{
		foreach ($rows as $row) {
			if ( $isAdmin || ($row["accepted"] == 1) ) {		// запретим юзерам видеть неодобренные отзывы
				echo
					'<script type="text/javascript">
						var tableRef = document.getElementById("tbl_reviews");
						var newRow = tableRef.insertRow();
						
						var lnkName = "' . buildReviewURL( $row["id"] ) . '";
						
						var aLink = document.createElement("a");
						aLink.href = lnkName;
						aLink.title = lnkName;
						aLink.appendChild( document.createTextNode(lnkName) );
						
						newRow.insertCell(0).appendChild( document.createTextNode("'. $row["id"] .'") );
						newRow.insertCell(1).appendChild( document.createTextNode("'. $row["title"] .'") );
						newRow.insertCell(2).appendChild( document.createTextNode("'. $row["email"] .'") );
						newRow.insertCell(3).appendChild( document.createTextNode("'. $row["text"] .'") );
						newRow.insertCell(4).appendChild( document.createTextNode("' . ( ($row["accepted"] == 1) ? "да" : "нет" ) . '" ) );
						newRow.insertCell(5).appendChild( aLink );
					</script>'
				;
			}
		}
	}
	
?>