<?php

	$qType = $_POST['qType'];
	$numQ = $_POST['numQ'];

	//$qType=5;
	//$numQ=100;

		//echo'got to try';

		//open new in-memory database
		$db = new PDO('sqlite::memory:');
		//set errormode to exceptions
		$db -> setAttribute(PDO::ATTR_ERRMODE,
		 					PDO::ERRMODE_EXCEPTION);

		//open iondb database
		$dbion = new PDO('sqlite:iondb.sqlite');
		//set errormode to exceptions
		$dbion -> setAttribute(PDO::ATTR_ERRMODE,
							PDO::ERRMODE_EXCEPTION);

		try{
		//create new table 'iondbsa' for in-memory database-columns match iondbs
		$stmt = $db->prepare("CREATE TABLE IF NOT EXISTS iondbsa(

		ionId 		INTEGER PRIMARY KEY, 
		ions 		TEXT, 
		ionCharge 	TEXT,
		ionName     TEXT,
		ionNameA    TEXT,
		ionNameB    TEXT,
		ionNameC    TEXT,
		ionNameD    TEXT,
		latin 		TEXT,
		explanation	INTEGER,
		ox          NUMERIC
		
		);");

		$stmt-> execute();
		} catch (PDOException $e) {

			echo $e->getMessage();
			exit;

		}

		//create prepared insert statement 

		try{

	 	$insert = "INSERT INTO  iondbsa (ionId, ions, ionCharge,ionName, ionNameA, ionNameB, ionNameC, ionNameD, latin, explanation, ox)
	 	  			VALUES (:ionId, :ions, :ionCharge, :ionName, :ionNameA, :ionNameB, :ionNameC, :ionNameD, :latin, :explanation, :ox)";
	 	  		
		
	 	$stmt = $db->prepare($insert);



	 	//select all data from dbion 'iondbs' table

	  	if($qType==1){
	  		$x=0;
	 		$result = $dbion->query('SELECT * FROM iondbs WHERE typeall = 1 ORDER BY random()');
	 		}
	 	if($qType==2){
	  		$x=0;
	 		$result = $dbion->query('SELECT * FROM iondbs WHERE typemustknowa = 1 ORDER BY random()');
	 		}	
		if($qType==3){
	 		$result = $dbion->query('SELECT * FROM iondbs WHERE typepa = 1 ORDER BY random()');
	 		}	
	 	if($qType==5){
	 		$result = $dbion->query('SELECT * FROM iondbs WHERE typemono = 1 ORDER BY random()');
	 		}
	 	if($qType==6){
	 		$result = $dbion->query('SELECT * FROM iondbs WHERE typemustknowb = 1 ORDER BY random()');
	 		}	
	 		
	 // 	//loop through all data from 'iondbs' table
	  	foreach($result as $row) {

	  		//echo $x;
	  		//echo $row['isTransMetal'];

	  		if($x==0 && $row['isTransMetal']==1) {
	  			$x=1;
	  			//echo $x;
	  			continue;
	  		}

			//bind parameters to statement variables
			$stmt->bindParam(':ionId', $row['ionId'], SQLITE3_INTEGER);
			$stmt->bindParam(':ions', $row['ions'], SQLITE3_TEXT);
			$stmt->bindParam(':ionCharge', $row['ionCharge'], SQLITE3_TEXT);
			$stmt->bindParam(':ionName', $row['ionName'], SQLITE3_TEXT);
			$stmt->bindParam(':ionNameA', $row['ionNameA'], SQLITE3_TEXT);
			$stmt->bindParam(':ionNameB', $row['ionNameB'], SQLITE3_TEXT);
			$stmt->bindParam(':ionNameC', $row['ionNameC'], SQLITE3_TEXT);
			$stmt->bindParam(':ionNameD', $row['ionNameD'], SQLITE3_TEXT);
			$stmt->bindParam(':latin', $row['latin'], SQLITE3_TEXT);
			$stmt->bindParam(':explanation', $row['explanation'], SQLITE3_INTEGER);
			$stmt->bindParam(':ox', $row['ox'], SQLITE3_NUMERIC);
			
			
	  		//execute statment
	  		$stmt->execute();

	  		//echo$x;
		
			if($x==1 && $row['isTransMetal']==1) {
				$x=0;
			}
		
		//$x=$x+1;
		//echo $x;
		//echo $row['ionName'].'</br>';
		

	 	}

	 } catch (PDOException $e) {

			echo $e->getMessage();
			exit;

		}

	//Now echo stuff from in-memory db
	 	//echo "now echo stuff";
		if($numQ==10){
	 		$stmt = $db->query('SELECT * FROM iondbsa ORDER BY random() LIMIT 10');
	 		$resulta=$stmt->fetchAll(PDO::FETCH_ASSOC);
	 	} else if ($numQ==25) {
	 		$stmt = $db->query('SELECT * FROM iondbsa ORDER BY random() LIMIT 25');
	 		$resulta=$stmt->fetchAll(PDO::FETCH_ASSOC);
	 	} else if ($numQ==50){
	 		$stmt = $db->query('SELECT * FROM iondbsa ORDER BY random() LIMIT 50');
	 		$resulta=$stmt->fetchAll(PDO::FETCH_ASSOC);
	 	} else if ($numQ==100){
	 		$stmt = $db->query('SELECT * FROM iondbsa ORDER BY random() LIMIT 100');
	 		$resulta=$stmt->fetchAll(PDO::FETCH_ASSOC);
	 	}


	  	$resultaArray = array($resulta);
	 	header('Content-type: application/json');
	 	echo json_encode($resultaArray);
	 //echo 'Got here';

		$db = null;
	 	$dbion = null;


?>