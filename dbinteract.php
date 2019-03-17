 <?php
	
	if (!function_exists('getRow')) {
		
			function getRow($rowId)
			{
				$rows = null;
				try {
				
					include "vars.php";
					include "dbconnect.php";
					include "dbquery.php";
					
					$conn = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
					
					$link = $conn->connect();
					
					$display_page = 1;
					$queryText = getQuerySelectByPage($display_page, $rowId);
					
					$result = $link->query( $queryText );	// mysqli::query
					
					////////////////////////////////////////////////////
					////////////////////////////////////////////////////
					
					if ( is_null($result) || ($result == false) )		// если ошибка соединения, и не удалось подключиться
					{
						return null;
					}
					
					$rows = $result->fetch_all(MYSQLI_BOTH);
					
					////////////////////////////////////////////////////
					////////////////////////////////////////////////////
		
					$result->free();		// очищаем результаты выборки
					$link->close();			// закрываем подключение
					
				} catch (Exception $e) {
					unset($rows);
					return null;
				}
				return $rows;
			}
	
	}
	
	
	if (!function_exists('getRows')) {
		
			function getRows()
			{
				return getRow(null);
			}
	
	}
	
	
	if (!function_exists('setApproving')) {
		
			function setApproving($id_review, $approved)
			{
				try {
				
					include "vars.php";
					include "dbconnect.php";
					include "dbquery.php";
					
					$conn = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
					
					$link = $conn->connect();
					
					$display_page = 1;
					$queryText = getQueryAccepting($id_review, $approved);
					
					$result = $link->query( $queryText );	// mysqli::query
					
					////////////////////////////////////////////////////
					////////////////////////////////////////////////////
		
					$link->close();			// закрываем подключение
					
					return ( ( !is_null($result) ) && ( $result ) );
					
				} catch (Exception $e) {
					
					unset($rows);
					return null;
					
				}
			}
	
	}
	
	
	if (!function_exists('updateReview')) {
		
			function updateReview($id_review, $title_review, $email_review, $text_review)
			{
				try {
				
					include "vars.php";
					include "dbconnect.php";
					include "dbquery.php";
					
					$conn = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
					
					$link = $conn->connect();
					
					$queryText = getQueryUpdate($id_review, $title_review, $email_review, $text_review);
					
					$result = $link->query( $queryText );	// mysqli::query
					
					$error_text = ( ( $result == 1 ) ? '' : mysqli_error($link) ) ;
		 
					$link->close();			// закрываем подключение
					
					return $error_text;
					
				} catch (Exception $e) {
					return $e->getMessage();
				}
			}
	
	}
 ?>