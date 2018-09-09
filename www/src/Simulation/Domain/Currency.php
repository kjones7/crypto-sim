<?php declare(strict_types=1);
// entity
namespace CryptoSim\Simulation\Domain;

use Money\Currencies\BitcoinCurrencies;
use Money\Formatter\BitcoinMoneyFormatter;
use Money\Parser\BitcoinMoneyParser;
use Money\Money;

final class Currency
{
    /** @var string This is required for the parsing and formatting to work correctly.
     * It's actually the symbol for Bitcoin, since the bitcoin parser/formatter is being used below,
     * since the 8 subunits are needed for decimal accuracy (we don't want only two decimal places for USD,
     * which is required when you use the USD in the MoneyPHP library
     */
    const REQUIRED_SIGN = 'Ƀ';
    const CRYPTOCURRENCY_FRACTION_DIGITS = 8;
    const USD_FRACTION_DIGITS = 2;

    private $amount;
    /** @var Money */
    private $money;

    public function __construct(string $amount)
    {
        $parser = new BitcoinMoneyParser(8);
        $this->amount = $amount;
        $this->money = $parser->parse(self::REQUIRED_SIGN . $amount);
    }

    public function multiply(Currency $currency, int $fractionDigits)
    {
        $currencies = new BitcoinCurrencies();
        $formatter = new BitcoinMoneyFormatter($fractionDigits, $currencies);
        $formattedMoney = $formatter->format($this->money->multiply($currency->getAmount()));

        // Removes signal in beginning
        return substr($formattedMoney, 2, strlen($formattedMoney));
    }

    public function getAmount()
    {
        return $this->amount;
    }
}