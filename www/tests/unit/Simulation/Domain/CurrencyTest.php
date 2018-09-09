<?php

use CryptoSim\Simulation\Domain\Currency;

class CurrencyTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testMultiply()
    {
        $currency1 = new Currency('217.83751605');
        $currency2 = new Currency('56.09709798');

        $currency1Product = $currency1->multiply($currency2, Currency::CRYPTOCURRENCY_FRACTION_DIGITS);
        $currency2Product = $currency2->multiply($currency1, Currency::CRYPTOCURRENCY_FRACTION_DIGITS);

        $this->tester->assertEquals($currency1Product, $currency2Product);
        $this->tester->assertEquals($currency1Product, '12220.05248158');
    }
}