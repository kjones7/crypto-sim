<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/sql/hidden_data.php'; // contains sql username and password

$dsn = 'mysql:dbname=' . $dbName . ';host=mysql';
$conn = new PDO($dsn, $dbUserName, $dbUserPass);
$stmt = $conn->prepare('
SELECT id, abbreviation
FROM cryptocurrencies
');
$stmt->execute();

$cryptoInfo = [];
$rows = $stmt->fetchAll(PDO::FETCH_NUM);
foreach($rows as $row) {
    $cryptoInfo[$row[1]] = $row[0];
}

print_r($cryptoInfo['ETH']);