<?php
/**
 * Created by PhpStorm.
 * User: olegivanov
 * Date: 13.09.16
 * Time: 15:24
 */

namespace BalanceBundle\Service;

use BalanceBundle\Entity\Purse;

class CbrConverter extends ConverterAbstract
{
    const CBR_LINK = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @var array
     */
    protected $currencies = [
        Purse::CURRENCY_RUB,
        Purse::CURRENCY_EUR,
        Purse::CURRENCY_KGS,
        Purse::CURRENCY_USD
    ];

    public function getCurrencyRate()
    {
        $needCurrencies = [];
        foreach ($this->currencies as $value) {
            $needCurrencies[] = strtoupper($value);
        }

        $xmlString = file_get_contents(self::CBR_LINK);
        $xmlRoot = new \SimpleXMLElement($xmlString);
        foreach ($xmlRoot as $childNode) {
            /** @var \SimpleXMLElement $childNode */
            $currencyCharCode = strval($childNode->CharCode);

            if (in_array($currencyCharCode, $needCurrencies)) {
                $currencyNominal = strval($childNode->Nominal);
                $currencyValue = strval($childNode->Value);
                $currencyValue = floatval(str_replace(',', '.', $currencyValue));

                $value = $currencyNominal > 0 ? $currencyValue / $currencyNominal : 1;
                $this->currenciesRate[$currencyCharCode] = $value;
            }
        }

        $this->currenciesRate[strtoupper(Purse::CURRENCY_RUB)] = 1;
    }

    /**
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function convert($amount, $fromCurrency, $toCurrency)
    {
        if (in_array($fromCurrency, $this->currencies) && in_array($toCurrency, $this->currencies)) {
            $fromKey = strtoupper($fromCurrency);
            $toKey = strtoupper($toCurrency);

            $fromValue = 0;
            if (isset($this->currenciesRate[$fromKey])) {
                $fromValue = $this->currenciesRate[$fromKey];
            }

            $toValue = 0;
            if (isset($this->currenciesRate[$toKey])) {
                $toValue = $this->currenciesRate[$toKey];
            }

            if ($toValue > 0) {
                return $amount * $fromValue / $toValue;
            }
        }

        return 0;
    }
}