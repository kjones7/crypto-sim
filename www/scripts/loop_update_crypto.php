<?php

require '../vendor/jaggedsoft/php-binance-api/php-binance-api.php'; // TODO - Come up with better way to manange paths
require "/var/www/html/vendor/autoload.php";
require 'hidden_keys.php'; // contains binance api key and secret
require '../sql/hidden_data.php'; // contains sql username and password
// TODO - get sql username and pass from config.ini file

//TODO Add database connection to separate php file, so it can be reused
$api = new Binance\API($binance_key, $binance_secret);
$ticker = $api->prices();
$bitcoinResultsOnly = array(); // will contain only cryptocurrncies that are in BTC, meaning 'symbol' ends in 'BTC'
$oneBTCtoUSD = $ticker['BTCUSDT']; // TODO - Is USDT close enough to USD to use?
$dsn = 'mysql:dbname=' . $dbName . ';host=mysql';

try {
    $conn = new PDO($dsn, $dbUserName, $dbUserPass);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

while (true) {
    $ticker = $api->prices();
    foreach ($ticker as $symbol => $price) {
        if (endsWith($symbol, 'BTC')) {
            $strLength = strlen($symbol);
            $symbol = substr($symbol, 0, $strLength - 3);
            $price = $price * $oneBTCtoUSD;
            if ($symbol != null && $price != null) {
                executeUpdateStmt($symbol, $price);
            }
        }
        if ($symbol === 'BTCUSDT') {
            executeUpdateStmt('BTC', $price);
        }
    }
    echo 'working! ';
    sleep(5);
}

/**
 * Updates the price of the cryptocurrency in the database
 *
 * @param string $symbol - The symbol of the cryptocurrency to update
 * @param string $worthInUSD - The new, updated price (in USD)
 * @return void
 */
function executeUpdateStmt($symbol, $worthInUSD)
{
    global $conn;
    $updateCryptoStmt = $conn->prepare(
        "UPDATE cryptocurrencies
        SET worth_in_USD = :worthInUSD
        WHERE abbreviation = :symbol"
    );
    $updateCryptoStmt->bindParam('worthInUSD', $worthInUSD);
    $updateCryptoStmt->bindParam('symbol', $symbol);
    if (!$updateCryptoStmt->execute()) {
        echo "Error: " . $updateCryptoStmt->errorInfo()[2];
    }
}

// TODO add this to a helper php file
function endsWith($haystack, $needle)
{
    $length = strlen($needle);

    return $length === 0 ||
    (substr($haystack, -$length) === $needle);
}
