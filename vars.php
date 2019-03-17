 <?php
 
	if (!function_exists('setConstantVariable')) {
		
			function setConstantVariable($setConstantName, $setConstantValue) {
				if ( !defined( $setConstantName ) ) {
					define($setConstantName, $setConstantValue);
				}
			}
			
	}
 
	setConstantVariable('DB_SERVER', 'localhost');
	setConstantVariable('DB_USER', 'admin');
	setConstantVariable('DB_PASS', 'admin');
	setConstantVariable('DB_DATABASE', 'tst');
	
	setConstantVariable('CST_PAGERECSCOUNT', 100);
	
	setConstantVariable('CST_REVIEW_APPROVED', 101);
	setConstantVariable('CST_REVIEW_DECLINED', 102);
	
 ?>