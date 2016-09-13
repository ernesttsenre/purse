<?php
/**
 * Created by PhpStorm.
 * User: olegivanov
 * Date: 13.09.16
 * Time: 15:25
 */

namespace BalanceBundle\Service;

abstract class ConverterAbstract implements ConverterInterface
{
    /**
     * Здесь лежат валюты
     * @var array
     */
    protected $currenciesRate;

    /**
     * ConverterAbstract constructor.
     */
    public function __construct()
    {
        $this->currenciesRate = [];
        $this->getCurrencyRate();
    }
}