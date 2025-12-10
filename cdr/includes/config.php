<?Php
////// Your Database Details here /////////
$db_host="cdr.tnasolutions.cl";
$port=3306;
$socket="";
$db_user="cdr";
$db_pwd="Pq63_10ad";
$db_name="tnasolutions";
try {
$dbo = new PDO('mysql:host='.$db_host.';dbname='.$db_name, $db_user, $db_pwd);
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}
?>