
function	testSQLForUpperCaseTableName($queryString){

	return;

	$localTesting = FALSE;

	$patternTests = array(
		'\sfrom\s([^\s]+)\s?',			//select * from schema.somtTable
		'\sjoin\s([^\s]+)\s?',			// "inner join schema.someTable on a.y = b.y"
		'\sinto\s([^\s]+)\s',			// insert into schema.someTable (x, y, z) values (1, 2, 3)
		'update\s([^\s]+)\s',			// "update schema.sentMessagesGroup set x = 1",
		'create.*table\s(.+?)\(.*$',	// "CREATE TABLE schema.clientManagement( clientManagementID int(10) unsigned NOT NULL auto_increment, clientID int(10) unsigned NOT NULL , signupResellerPromotionCodeID int(10) unsigned , resellerUserID int(10) unsigned , PRIMARY KEY ( clientManagementID ) , KEY Index_clientID (clientID) ) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;" => TRUE,
		'drop.*table\s(.+?)$',			// drop table schema.someTable
	);

	foreach($patternTests as $patternTest){

		$pattern = "/.*".$patternTest."/i";

		$matches = array();
		$matchCount = preg_match_all($pattern, $queryString,  $matches);

		if($matchCount != 0){
			foreach($matches[1] as $match){

				if($localTesting == TRUE){
					echo "match = $match \r\n";
				}

				if(strcmp($match, strtolower($match)) !== 0){

					$upperCaseDetected = "UPPER CASE DETECTED:\r\n";
					$upperCaseDetected .= $match;
					$upperCaseDetected .= "\r\n\r\nOriginal query is $queryString\r\n";
					$calledFromString = getCalledFromString(1);// 1 is correct for prepared statements prepared through prepareAndExecute.

					$upperCaseDetected .= "\r\nCalled from : \r\n".$calledFromString;

					if($localTesting == TRUE){
						echo $upperCaseDetected;
					}
					else{
						logToFileFatal($upperCaseDetected);
					}
					return TRUE;
				}
			}
			break;// Stop processing any more rules, to prevent insert...on duplicate update from matching the update rule.
		}
	}

	return FALSE;
}

