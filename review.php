<html>
	
 <head>
	<meta charset="utf-8">
	<title>Страница отзыва</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="shortcut icon" href="/img/favicon.ico" type="image/png">
	
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
			Отзыв
		</div>
		<br/>
		<div id = "div_err" class="css_error" style="display: none;">
			Не удалось загрузить данные из БД!
		</div>
	</div>
      
	<form class="css_content" id="form_review" action="index.php" method="post">
		<div class = "css_content">
			<table id = "tbl_review">
			</table>
		</div>
		<div id="div_recaptcha" class="g-recaptcha" data-sitekey="6LfsuZYUAAAAAF4XvByq5c4WHIjyva86U5AKKV_l"></div>
	</form>
	
	<p><a href="index.php">Назад к списку отзывов</a></p>
 	
 </body>
</html>

 
<?php
	
	
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////
	

	include "vars.php";
	include "functions.php";
	include "dbinteract.php";


	$idReview = 0;
	if (isset($_GET['id'])) {
		$idReview = $_GET['id'];
	}
	
	
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////


	// примитивная имитация админки
	
	if (isset($_POST['make_admin'])) {
		setCookieIsAdmin( ($_POST['make_admin'] == 1) ? true : false );
	}
	$isAdmin = ( getCookieIsAdmin() ? 1 : 0 );
	//echo "Admin: " . $isAdmin;
	setBlockVisibility( "form_btn_user", $isAdmin );
	setBlockVisibility( "form_btn_admin", !($isAdmin) );
	
	setBlockVisibility( "div_recaptcha", !($isAdmin) );
	
	
	////////////////////////////////////////////////////
	////////////////////////////////////////////////////
	
	
	// получив данные отзыва с ID = $idReview из БД, динамически наполняем через JS
	// существующую таблицу tbl_review параметрами отзыва
	
	$rows = getRow($idReview);
	/*
	if ( ($rows == false) || ($idReview <= 0) )
	{
		setBlockVisibility("div_err", true);
		//setBlockVisibility("tbl_review", false);
	}
	else
	*/
	if ( ($rows == false) )
	{
		$rows =
			array(
				array( "id" => 0
					, "title" => ""
					, "text" => ""
					, "email" => ""
					, "accepted" => 0 )
			); 
	}
	{
		$isReadonly = ($isAdmin == 1) ? "true" : "false";
		
		foreach ($rows as $row) {
			echo
				'<script type="text/javascript">
				
					// возвращает div, в котором лежит input для формы отзыва
					function appendInput(aInpID, aInpType, aInpName, aInpValue, aInpCaption, aInpReadonly)
					{
						var elemInput = document.createElement("input");
				    elemInput.id = aInpID;
				    elemInput.type = aInpType;
				    elemInput.name = aInpName;
				    elemInput.value = aInpValue;
				    elemInput.readOnly = aInpReadonly;
				    
				    if (aInpType != "submit")
							elemInput.className = "css_noborder";
				    
						var elemSpan = document.createElement("span");
				    elemSpan.innerHTML = aInpCaption;
				    
						var elemDiv = document.createElement("div");
						elemDiv.className = "css_block";
						elemDiv.appendChild( elemSpan );
						elemDiv.appendChild( elemInput );
						
				    return elemDiv;
					}
				
					// входные: 1) таблица; 2) описание строки таблицы; 3) div с input-ом
					// выходные: ссылка на строку таблицы
					function appendRow(aTable, aCaption, aElement)
					{
						var newRow = tableRef.insertRow();
						newRow.insertCell(0).appendChild(
								document.createTextNode(aCaption)
						);
						newRow.insertCell(1).appendChild( aElement );
						
				    return newRow;
					}
					
					
					var tableRef = document.getElementById("tbl_review");
					
					appendRow(
						tableRef
						, "ID"
						, appendInput("review_id", "text", "review_id", "'. $row["id"] .'", "", true)
					);
					appendRow(
						tableRef
						, "Название (мин. 3 симв.)"
						, appendInput("review_title", "text", "review_title", "'. $row["title"] .'", "", '. $isReadonly .')
					);
					appendRow(
						tableRef
						, "E-mail"
						, appendInput("review_email", "text", "review_email", "'. $row["email"] .'", "", '. $isReadonly .')
					);
					appendRow(
						tableRef
						, "Текст отзыва (мин. 10 симв.)"
						, appendInput("review_text", "text", "review_text", "'. $row["text"] .'", "", '. $isReadonly .')
					);
					appendRow(
						tableRef
						, "Принят"
						, appendInput("review_accepted", "text", "review_accepted", "'. ( ($row["accepted"] == 1) ? "да" : "нет" ) .'", "", true)
					);
			    
			    
					var formReview = document.getElementById("form_review");
				    
					  '. ( ($isAdmin == 1) ? '
							formReview.appendChild( appendInput("review_approve", "submit", "review_approve", "Одобрить", "", false) );
							formReview.appendChild( appendInput("review_decline", "submit", "review_decline", "Отклонить", "", false) );
						' : '
							formReview.appendChild( appendInput("review_edit", "submit", "review_edit", "Отправить", "", false) );
						' ) .'
						
				</script>'
			;
		}
	}
	
?>