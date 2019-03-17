 <?php
 
	if (!function_exists('getCookieIsAdmin')) {
		
			function getCookieIsAdmin()
			{
				if(!isset($_COOKIE['review_task_isadmin'])) {
			    setcookie('review_task_isadmin', 0);
			    $_COOKIE['review_task_isadmin'] = 0;
				}
				$cookieIsAdmin = $_COOKIE["review_task_isadmin"] ;
				return ( isset($cookieIsAdmin) && ($cookieIsAdmin != 0) ) ? true : false ;
			}
	
	}
 
	if (!function_exists('setCookieIsAdmin')) {
		
			function setCookieIsAdmin($is_admin)
			{
				if( ($is_admin == true) || ($is_admin == 1) ) {
			    setcookie('review_task_isadmin', 1);
			    $_COOKIE['review_task_isadmin'] = 1;
				}
				else {
			    setcookie('review_task_isadmin', 0);
			    $_COOKIE['review_task_isadmin'] = 0;
				}
				return true;
			}
	
	}
 
	if (!function_exists('buildRootPath')) {
		
			function buildRootPath()
			{
				$lnkHTTPS = ( ( (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") || $_SERVER["SERVER_PORT"] == 443 ) ? "https" : "http" );
				return $lnkHTTPS . "://" . $_SERVER["SERVER_NAME"] . dirname( $_SERVER["PHP_SELF"] ) . "/";
			}
	
	}
 
	if (!function_exists('buildReviewURL')) {
		
			function buildReviewURL($id)
			{
				$txtResult = buildRootPath() .  "review.php";
				
				if ( isset($id) && ($id > 0) ) {
					$txtResult = $txtResult .  "?id=" . $id;
				}
				
				return $txtResult;
			}
	
	}
 
 
	if (!function_exists('setBlockVisibility')) {
		
			function setBlockVisibility($block_name, $show_bool)
			{
				echo
					'<script type="text/javascript">' .
					'	try { document.getElementById(	"' . $block_name . '"	).style.setAttribute("display", "'	. ($show_bool ? "block" : "none" ) . '"	); } catch(e) {}; ' .	// ie 
					'	try { document.getElementById(	"' . $block_name . '"	).style.display = ( "'				. ($show_bool ? "block" : "none" ) . '" ); } catch(e) {}; ' .	// chrome
					'</script>'
				;
			}
	
	}
	
 ?>