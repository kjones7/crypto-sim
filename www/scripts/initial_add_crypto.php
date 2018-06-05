<?php

require '../vendor/jaggedsoft/php-binance-api/php-binance-api.php'; // TODO - Come up with better way to manange paths
require '../vendor/autoload.php';
require 'hidden_keys.php'; // contains binance api key and secret
require '../sql/hidden_data.php'; // contains sql username and password
require 'cryptoSymbolsAndNames.php'; // contains array to get crypto names from symbols
// @see home_directory_config.php
// use config from ~/.confg/jaggedsoft/php-binance-api.json

$api = new Binance\API($binance_key, $binance_secret);

/**
 * The current prices of all of the cryptocurrencies from the API
 * @var array $ticker = 
 * [
 *   [
 *     'symbol' => (string) The symbols/abbreviations of the cryptocurrency,
 *         where the first abbreivation is what the worth of the amount is for,
 *         and the second abbrevation is the unit of the amount.
 *         Ex: 'ETCBTC'
 *     'price' => (string) The worth of the first abbreviation in units of the
 *         second abbreviation.
 *         Ex: 'ETCBTC' will give how much ETH is worth in BTC
 *   ]
 * ]
 * 
 * Possible symbols can be seen on public api https://api.binance.com/api/v3/ticker/price
 */
$ticker = $api->prices();
$bitcoinResultsOnly = array(); // will contain only cryptocurrncies that are in BTC, meaning 'symbol' ends in 'BTC'
$oneBTCtoUSD = $ticker['BTCUSDT']; // TODO - Is USDT close enough to USD to use?
// $conn = mysqli_connect("mysql", $dbUserName, $dbUserPass, $dbName);
$dsn = 'mysql:dbname=' . $dbName . ';host=mysql';

try {
    $conn = new PDO($dsn, $dbUserName, $dbUserPass);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

foreach ($ticker as $symbol => $price) {
    if(endsWith($symbol, 'BTC')) {
        insertCryptocurrency($symbol, $price);
    }
    if($symbol === 'BTCUSDT') {
        // insert bitcoin
        $overrideOptions = [
            'symbol' => 'BTC',
            'name' => 'Bitcoin',
            'worthInUSD' => $price
        ];
        $overrideInsert = true;
        insertCryptocurrency($symbol, $price, $overrideInsert, $overrideOptions);
    }
}

function insertCryptocurrency($symbol, $price, $overrideInsert = false, $overrideOptions = []) {
    global $conn, $oneBTCtoUSD, $cryptoSymbolsAndNames;
    if($overrideInsert) {
        $cryptoSymbol = $overrideOptions['symbol'];
        $cryptoName = $overrideOptions['name'];
        $worthInUSD = $overrideOptions['worthInUSD'];
    } else if(endsWith($symbol, 'BTC')){
        $worthInUSD = $price * $oneBTCtoUSD; // TODO use currency library
        // TODO turn this process into a separate function, add it to a separate php file
        // so it can be used in loop_update_crypto.php too
        $strLength = strlen($symbol);
        $cryptoSymbol = substr($symbol, 0, $strLength - 3);
        $cryptoName = $cryptoSymbolsAndNames[$cryptoSymbol];
    }
    $insertCryptoStmt = $conn->prepare(
        "INSERT INTO cryptocurrencies(
            name,
            abbreviation,
            worth_in_USD
            ) VALUES (
                :name,
                :symbol,
                :worthInUSD
                )");
    $insertCryptoStmt->bindParam(':name', $cryptoName, PDO::PARAM_STR);
    $insertCryptoStmt->bindParam('symbol', $cryptoSymbol, PDO::PARAM_STR);
    $insertCryptoStmt->bindParam('worthInUSD', $worthInUSD);

    if (!$insertCryptoStmt->execute()) {
        echo "Error: " . $insertCryptoStmt->errorInfo()[2] . " $symbol";
    }
}

// TODO add this to a helper php file
function endsWith($haystack, $needle)
{
    $length = strlen($needle);

    return $length === 0 || 
    (substr($haystack, -$length) === $needle);
}
