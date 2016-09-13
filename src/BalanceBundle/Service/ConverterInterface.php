<?php
/**
 * Created by PhpStorm.
 * User: olegivanov
 * Date: 13.09.16
 * Time: 14:46
 */

namespace BalanceBundle\Service;

interface ConverterInterface
{
    /**
     * @return void
     */
    public function getCurrencyRate();

    /**
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function convert($amount, $fromCurrency, $toCurrency);
}