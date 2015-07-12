<?php
// DB connect : mysql_connect(host,id,pw)
$db = "db_201024441";
$sql = mysql_connect('localhost', 'root', 'apmsetup');
if (!$sql)
{
	die('Could not connect: ' . mysql_error());
}
// DB select : mysql_select_db(db name, $con)
if(!mysql_select_db("db_201024441", $sql))
{
	//createDB();
}

// javascript를 통해 넘겨준 query를 가져온다
// GET방식으로 넘겨줬기 때문에 GET으로 가져온다
$query= $_GET["q"];
if($query != null) {
	$queryPiece = explode("@", $query);
	if($queryPiece[0] == "p2")
		problem2($queryPiece[1]);
	else if($queryPiece[0] == "p3")
		problem3($queryPiece[1]);
	else if($queryPiece[0] == "p4")
		problem4($queryPiece[1]);
	else if($queryPiece[0] == "p5")
		problem5($queryPiece[1]);
	else
		echo $query;
}
// query execution : mysql_query(query)
//$result = mysql_query($query);
//echo "<table>";
//	echo "<tr>" . $arr["abc"] . " " . $arr["bcd"] . " " . $arr["efg"] . "</tr>";
// while($row = mysql_fetch_array($result))
// {
	// echo "<tr><td><b>MemberID</b></td><td>".$row['MemberID']."</td></tr>";
	// echo "<tr><td><b>MemberName</b></td><td>".$row['MemberName']."</td></tr>";
	// echo "<tr><td><b>MemberCourse</b></td><td>".$row['MemberCourse']."</td></tr>";
	// echo "<tr><td><b>Laboratory</b></td><td>".$row['Laboratory']."</td></tr>";
// }
//echo "</table>";


$message = $_GET["m"];
echo $message;
// 데이터베이스 초기화 명령들
$messagePiece = explode("=", $message);
if($messagePiece[0] == "deleteDB") {
	deleteDB();
}
else if($messagePiece[0] == "createDB") {
	createDB();
}
else if($messagePiece[0] == "Student") {
	initStudent($messagePiece[1]);
}
else if($messagePiece[0] == "Professor") {
	initProfessor($messagePiece[1]);
}
else if($messagePiece[0] == "Lecture") {
	initLecture($messagePiece[1]);
}
else if($messagePiece[0] == "Student_Lecture") {
	initStudent_Lecture($messagePiece[1]);
}
else if($messagePiece[0] == "Professor_Lecture") {
	initProfessor_Lecture($messagePiece[1]);
}
else if($messagePiece[0] == "Department") {
	initDepartment($messagePiece[1]);
}

// DB connection close : mysql_close($con)
mysql_close($sql);

function problem2($q) {
	echo "problem2<br/>";
	$result;
	if(!($result = mysql_query($q)))
		echo 'Could not execute query: ' . mysql_error();
	echo "<table>";
	echo "<thead> <tr> <th>name</th> <th>cnt</th> </thead>";
	while($row = mysql_fetch_array($result)) {
		echo "<tr> <td>". $row['name'] . "</td> <td>" . $row['cnt'] . "</td> </tr>";
	}
	echo "</table>";
}

function problem3($q) {
	echo "problem2<br/>";
	$result;
	if(!($result = mysql_query($q)))
		echo 'Could not execute query: ' . mysql_error();
	echo "<table>";
	echo "<thead> <tr> <th>department</th> </thead>";
	while($row = mysql_fetch_array($result)) {
		echo "<tr> <td>". $row['department'] . "</td> </tr>";
	}
	echo "</table>";
}

function problem4($q) {
	echo "problem4<br/>";
	$result;
	if(!($result = mysql_query($q)))
		echo 'Could not execute query: ' . mysql_error();
	echo "<table>";
	echo "<thead> <tr> <th>lecture</th> <th>student</th> <th>department</th> <th>year</th> <th>score</th> </thead>";
	while($row = mysql_fetch_array($result)) {
		echo "<tr> <td>". $row['l_name'] . "</td> <td>" . $row['s_name'] . "</td>";
		echo "<td>". $row['department'] . "</td> <td>" . $row['year'] . "</td> <td>" . $row['score'] . "</td> </tr>";
	}
	echo "</table>";
}

function problem5($q) {
	echo "problem5<br/>";
	$result;
	if(!($result = mysql_query($q)))
		echo 'Could not execute query: ' . mysql_error();
	echo "<table>";
	echo "<thead> <tr> <th>department</th> <th>name</th> </thead>";
	while($row = mysql_fetch_array($result)) {
		echo "<tr> <td>". $row['department'] . "</td> <td>" . $row['name'] . "</td> </tr>";
	}
	echo "</table>";
}
function deleteDB() {
	global $db;
	if(!mysql_query("drop database " . $db))
		echo 'Could not drop the database : ' . mysql_error();
}

function createDB() {
	global $db;
	if(!mysql_query("create database " . $db))
		echo 'Could not create a database : ' . mysql_error();
	mysql_select_db($db);
}
function initStudent($fileName) {
	if(!mysql_query("create table Student (
					name char(20),
					ID int primary key,
					year int,
					department char(20),
					score real )" ) )
		echo 'Could not create a table: ' . mysql_error();
					
	$studentData = file($fileName);
	$numOfData = (int)($studentData[0]);
	for($i=1; $i<=$numOfData; $i++) {
		$dataPiece = explode("\t", $studentData[$i]);
		$q = "insert into Student values (";
		$q = $q . "'" . $dataPiece[0] . "', "; //name
		$q = $q . $dataPiece[1] . ", "; //id
		$q = $q . $dataPiece[2] . ", "; //year
		$q = $q . "'" . $dataPiece[3] . "', "; //department
		$q = $q . $dataPiece[4] . ")"; //score
		if(!mysql_query($q))
			echo 'Could not insert a tuple: ' . mysql_error() . "<br/>";
	}
}

function initProfessor($fileName) {
	if(!mysql_query("create table Professor ( 
						name char(20),
						ID int primary key,
						major char(20),
						department char(20) )" ) )
		echo 'Could not create a table: ' . mysql_error();
		
	$professorData = file($fileName);
	$numOfData = (int)($professorData[0]);
	for($i=1; $i<=$numOfData; $i++) {
		$dataPiece = explode("\t", $professorData[$i]);
		$q = "insert into Professor values (";
		$q = $q . "'" . $dataPiece[0] . "', "; //name
		$q = $q . $dataPiece[1] . ", "; //id
		$q = $q . "'" . $dataPiece[2] . "', "; //major
		$q = $q . "'" . $dataPiece[3] . "')"; //department
		if(!mysql_query($q))
			echo 'Could not insert a tuple: ' . mysql_error() . "<br/>";
	}
}

function initLecture($fileName) {
	if(!mysql_query( "create table Lecture (
						ID int primary key,
						name char(20),
						department char(20),
						capacity int)" ) )
		echo 'Could not create a table: ' . mysql_error();
		
	$lectureData = file($fileName);
	$numOfData = (int)($lectureData[0]);
	for($i=1; $i<=$numOfData; $i++) {
		$dataPiece = explode("\t", $lectureData[$i]);
		$q = "insert into Lecture values (";
		$q = $q . $dataPiece[0] . ", "; //id
		$q = $q . "'" . $dataPiece[1] . "', "; //name
		$q = $q . "'" . $dataPiece[2] . "', "; //department
		$q = $q . $dataPiece[3] . ")"; //capacity
		if(!mysql_query($q))
			echo 'Could not insert a tuple: ' . mysql_error() . "<br/>";
	}
}

function initStudent_Lecture($fileName) {
	if(!mysql_query( "create table Student_Lecture (
						studentID int,
						lectureID int,
						primary key (studentID, lectureID) )" ) )
		echo 'Could not create a table: ' . mysql_error();
		
	$slData = file($fileName);
	$numOfData = (int)($slData[0]);
	for($i=1; $i<=$numOfData; $i++) {
		$dataPiece = explode("\t", $slData[$i]);
		$q = "insert into Student_Lecture values (";
		$q = $q . $dataPiece[0] . ", "; //studentID
		$q = $q . $dataPiece[1] . ")"; //lectureID
		if(!mysql_query($q))
			echo 'Could not insert a tuple: ' . mysql_error() . "<br/>";
	}
}

function initProfessor_Lecture($fileName) {
	if(!mysql_query( "create table Professor_Lecture (
						professorID int,
						lectureID int,
						primary key (professorID, lectureID) )" ) )
		echo 'Could not create a table: ' . mysql_error();
		
	$plData = file($fileName);
	$numOfData = (int)($plData[0]);
	for($i=1; $i<=$numOfData; $i++) {
		$dataPiece = explode("\t", $plData[$i]);
		$q = "insert into Professor_Lecture values (";
		$q = $q . $dataPiece[0] . ", "; //professorID
		$q = $q . $dataPiece[1] . ")"; //lectureID
		if(!mysql_query($q))
			echo 'Could not insert a tuple: ' . mysql_error() . "<br/>";
	}
}

function initDepartment($fileName) {
	if(!mysql_query( "create table Department (
						name char(20) primary key,
						office char(10),
						phone char(10) )" ) )
		echo 'Could not create a table: ' . mysql_error();
		
	$departmentData = file($fileName);
	$numOfData = (int)($departmentData[0]);
	for($i=1; $i<=$numOfData; $i++) {
		$dataPiece = explode("\t", $departmentData[$i]);
		$q = "insert into Department values (";
		$q = $q . "'" . $dataPiece[0] . "', "; //name
		$q = $q . "'" . $dataPiece[1] . "', "; //office
		$q = $q . "'" . $dataPiece[2] . "')"; //phone
		if(!mysql_query($q))
			echo 'Could not insert a tuple: ' . mysql_error() . "<br/>";
	}
}
?>