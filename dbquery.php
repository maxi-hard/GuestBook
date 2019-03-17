 <?php
 
	if (!function_exists('getQuerySelectByPage')) {
		
			function getQuerySelectByPage($display_page, $id_review)
			{
				$where_clause = (
					isset($id_review) ?
						" WHERE (r.id = " . $id_review . ") " :
						" WHERE (0 = 0) "
				);
				$limit_clause = (
					isset($display_page) ?
						" LIMIT " . ( ($display_page - 1) * CST_PAGERECSCOUNT ).", ".(CST_PAGERECSCOUNT) :
						" "
				);
			
				return 
					"SELECT													"
					."	r.id 												"
					."	, r.title 											"
					."	, r.email 											"
					."	, r.text 											"
					."	, r.accepted 										"
					."FROM reviews r										"
					. $where_clause
					."ORDER by r.title 										"
					. $limit_clause
					;
			}
			
	}
	
	if (!function_exists('getQueryUpdate')) {
		
			function getQueryUpdate($id_review, $title_review, $email_review, $text_review)
			{
				$where_clause = (
					isset($id_review) ?
						" WHERE (r.id = " . $id_review . ") " :
						" WHERE (1 = 0) "
				);
				
				if ( isset($id_review) && ( $id_review > 0 ) ) {
					return 
						"UPDATE	reviews r SET		"
						." r.title = '" . (string)$title_review . "'"
						." , r.email = '" . (string)$email_review . "'"
						." , r.text = '" . (string)$text_review . "'"
						. $where_clause
						;
				}
				else {
					return 
						"INSERT INTO reviews ( "
						." title "
						." , email "
						." , text "
						." , accepted "
						." ) VALUES ( "
						."   '" . (string)$title_review . "'"
						." , '" . (string)$email_review . "'"
						." , '" . (string)$text_review . "'"
						." , 0 "
						." ) "
						;
				}
			}
			
	}
	
	if (!function_exists('getQueryAccepting')) {
		
			function getQueryAccepting($id_review, $approved)
			{
				$where_clause = (
					isset($id_review) ?
						" WHERE (r.id = " . $id_review . ") " :
						" WHERE (1 = 0) "
				);
			
				return 
					"UPDATE	reviews r 	"
					."SET r.accepted = " . ($approved ? "1" : "0" ) . " "
					. $where_clause
					;
			}
			
	}
	
 ?>