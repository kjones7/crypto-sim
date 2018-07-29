<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require 'hidden_keys.php'; // contains binance api key and secret
// TODO - Replace the hidden_data usage with the env file parsing
require dirname(__DIR__) . '/sql/hidden_data.php'; // contains sql username and password
require dirname(__DIR__) . '/scripts/cryptoSymbolsAndNames.php'; // contains array to get crypto names from symbols
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
$oneBTCtoUSD = $ticker['BTCUSDT']; // TODO - Is USDT close enough to USD to use?
$dsn = 'mysql:dbname=' . $dbName . ';host=mysql';

try {
    $conn = new PDO($dsn, $dbUserName, $dbUserPass);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

foreach ($ticker as $symbol => $price) {
    if (endsWith($symbol, 'BTC')) {
        insertCryptocurrency($symbol, $price);
    }
    if ($symbol === 'BTCUSDT') {
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

/**
 * Inserts a cryptocurrency into the database
 *
 * @param string $symbol - The symbol of the cryptocurrency
 *     Ex: 'ETHBTC', 'LTCBTC'
 *     Note: The first half of $symbol is the cryptocurrency it represents,
 *     and the second half is what the units of $price is in. BTC is used
 *     in this function, and is converted to USDT, which is a cryptocurrency
 *     close in value to US Dollars
 * @param string $price - The price/worth of the cryptocurrency, the units
 *     of which are in the second half of $symbol.
 *     Ex: 'ETHBTC' means that $price will be in BTC
 * @param boolean $overrideInsert - If true, override the insert to insert a custom cryptocurrency
 *     Used to insert Bitcoin, since it can't be automatically inserted from the API data, since
 *     this function only looks at $symbol that end in 'BTC', and Bitcoin is 'BTCUSDT' in the API
 * @param array $overrideOptions - The options of the override insert
 *   @param string $overrideOptions.symbol - the symbol of the cryptocurrency to be inserted
 *   @param string $overrideOptions.name - the name of the cryptocurrency to be inserted
 *   @param string $overrideOptions.worthInUSD - the worth of the cryptocurrency to be inserted (in USD)
 * @return void
 */
function insertCryptocurrency($symbol, $price, $overrideInsert = false, $overrideOptions = [])
{
    global $conn, $oneBTCtoUSD, $cryptoSymbolsAndNames;
    if ($overrideInsert) {
        $cryptoSymbol = $overrideOptions['symbol'];
        $cryptoName = $overrideOptions['name'];
        $worthInUSD = $overrideOptions['worthInUSD'];
    } elseif (endsWith($symbol, 'BTC')) {
        $worthInUSD = $price * $oneBTCtoUSD; // TODO use currency library
        // TODO turn this process into a separate function, add it to a separate php file
        // so it can be used in loop_update_crypto.php too
        $strLength = strlen($symbol);
        $cryptoSymbol = substr($symbol, 0, $strLength - 3);
        $cryptoName = $cryptoSymbolsAndNames[$cryptoSymbol];
    }
    if ($worthInUSD !== null && $cryptoSymbol !== null && $cryptoName !== null) {
        $insertCryptoStmt = $conn->prepare(
            "INSERT INTO cryptocurrencies(
                name,
                abbreviation,
                worth_in_USD
                ) VALUES (
                    :name,
                    :symbol,
                    :worthInUSD
                    )"
        );
        $insertCryptoStmt->bindParam(':name', $cryptoName, PDO::PARAM_STR);
        $insertCryptoStmt->bindParam(':symbol', $cryptoSymbol, PDO::PARAM_STR);
        $insertCryptoStmt->bindParam(':worthInUSD', $worthInUSD);

        if (!$insertCryptoStmt->execute()) {
            echo "Error: " . $insertCryptoStmt->errorInfo()[2] . " $symbol";
        }
    }
}

// TODO add this to a helper php file
/**
 * Returns whether $haystack ends with $need
 * Ex: endsWith('Hello', 'llo') returns true
 *
 * @param string $haystack - The main word that will have its end checked
 * @param string $needle - The string that will be checked to see if $haystack ends with it
 * @return boolean - True if $haystack ends with $needle, false otherwise
 */
function endsWith($haystack, $needle)
{
    $length = strlen($needle);

    return $length === 0 ||
    (substr($haystack, -$length) === $needle);
}
