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
    private $currencies;

    public function __construct(string $amount)
    {
        $parser = new BitcoinMoneyParser(8);
        $this->currencies = new BitcoinCurrencies();
        $this->amount = $amount;
        $this->money = $parser->parse(self::REQUIRED_SIGN . $amount);
    }

    /**
     * Multiply the amounts of two currencies together
     * @param Currency $currency
     * @param int $fractionDigits - Should be one of the *_FRACTION_DIGITS constants in the Currency class.
     * It decides how many decimal places will be included in the formatted product
     * @return bool|string Returns either the product or false on failure of substr()
     */
    // TODO - Make sure you do error handling when product is false
    public function multiply(Currency $currency, int $fractionDigits)
    {
        $formatter = new BitcoinMoneyFormatter($fractionDigits, $this->currencies);
        $formattedMoney = $formatter->format($this->money->multiply($currency->getAmount()));

        // Removes signal in beginning (taking into account whether it's negative or not)
        if(substr($formattedMoney, 0, 1) === '-') {
            $product = substr($formattedMoney, 0, 1) . substr($formattedMoney, 3, strlen($formattedMoney));
        } else {
            $product = substr($formattedMoney, 2, strlen($formattedMoney));
        }

        return $product;
    }

    /**
     * Divide $this->amount by $currency->getAmount()
     * @param Currency $currency
     * @param int $fractionDigits - Should be one of the *_FRACTION_DIGITS constants in the Currency class.
     * It decides how many decimal places will be included in the formatted quotient
     * @return bool|string Returns either the product or false on failure of substr()
     */
    public function divide(Currency $currency, int $fractionDigits)
    {
        $formatter = new BitcoinMoneyFormatter($fractionDigits, $this->currencies);
        $formattedMoney = $formatter->format($this->money->divide($currency->getAmount()));

        // Removes signal in beginning (taking into account whether it's negative or not)
        if(substr($formattedMoney, 0, 1) === '-') {
            $quotient = substr($formattedMoney, 0, 1) . substr($formattedMoney, 3, strlen($formattedMoney));
        } else {
            $quotient = substr($formattedMoney, 2, strlen($formattedMoney));
        }

        return $quotient;
    }

    /**
     * Add the amounts of both currencies together
     * @param Currency $currency
     * @param int $fractionDigits Should be one of the *_FRACTION_DIGITS constants in the Currency class.
     * It decides how many decimal places will be included in the formatted sum
     * @return bool|string Returns either the product or false on failure of substr()
     */
    public function add(Currency $currency, int $fractionDigits)
    {
        $formatter = new BitcoinMoneyFormatter($fractionDigits, $this->currencies);
        $formattedMoney = $formatter->format($this->money->add($currency->getMoney()));

        // Removes signal in beginning (taking into account whether it's negative or not)
        if(substr($formattedMoney, 0, 1) === '-') {
            $sum = substr($formattedMoney, 0, 1) . substr($formattedMoney, 3, strlen($formattedMoney));
        } else {
            $sum = substr($formattedMoney, 2, strlen($formattedMoney));
        }

        return $sum;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Money
     */
    public function getMoney()
    {
        return $this->money;
    }
}