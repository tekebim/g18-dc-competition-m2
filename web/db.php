<?php
# Fill our vars and run on cli
# $ php -f db-connect-test.php


$dbname = 'jnve_demo';
$dbuser = 'root';
$dbpass = 'password';
$dbhost = 'db';


/*
$dbname = 'jnve_demo';
$dbuser = 'jnve';
$dbpass = 'Dsqd8sqkOLMDcnb';
$dbhost = 'localhost';
*/

/*
$link = mysql_connect($dbhost, $dbuser, $dbpass)
or die("Impossible de se connecter : " . mysql_error());
echo 'Connexion réussie';
mysql_close($link);
*/

/*
$connect = mysql_connect($dbhost, $dbuser, $dbpass) or die("Unable to Connect to '$dbhost'");
mysql_select_db($dbname) or die("Could not open the db '$dbname'");

$test_query = "SHOW TABLES FROM $dbname";
$result = mysql_query($test_query);

$tblCnt = 0;
while($tbl = mysql_fetch_array($result)) {
    $tblCnt++;
    #echo $tbl[0]."<br />\n";
}

if (!$tblCnt) {
    echo "There are no tables<br />\n";
} else {
    echo "There are $tblCnt tables<br />\n";
}

*/

try {
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    echo "Connected to $dbname at $dbhost successfully.";
} catch (PDOException $pe) {
    die("Could not connect to the database $dbname :" . $pe->getMessage());
}

