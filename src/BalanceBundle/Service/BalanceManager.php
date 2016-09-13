<?php

namespace BalanceBundle\Service;

use BalanceBundle\Entity\Operation;
use BalanceBundle\Entity\Purse;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Created by PhpStorm.
 * User: olegivanov
 * Date: 13.09.16
 * Time: 14:17
 */
class BalanceManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ConverterInterface
     */
    private $converter;
    /**
     * @var Purse[]
     */
    private $purses;

    /**
     * BalanceManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param ConverterInterface $converter
     */
    public function __construct(EntityManagerInterface $entityManager, ConverterInterface $converter)
    {
        $this->entityManager = $entityManager;
        $this->converter = $converter;

        $this->init();
    }

    public function init()
    {
        $this->purses = $this->entityManager->getRepository('BalanceBundle:Purse')->findAll();
    }

    /**
     * @return Purse[]
     */
    public function getPurses()
    {
        return $this->purses;
    }

    /**
     * @param string $currency
     * @return Purse
     */
    public function getPurseByCurrency($currency)
    {
        foreach ($this->purses as $purse) {
            if ($purse->getCurrency() == $currency) {
                return $purse;
            }
        }

        return null;
    }

    /**
     * @param Purse $purse
     * @param float $amount
     * @return boolean
     * @throws \Exception
     */
    public function addMoney(Purse $purse, $amount)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $operation = new Operation();
            $operation->setAmount($amount);
            $operation->setPurse($purse);

            $currentPurseBalance = floatval($purse->getBalance()) + $amount;
            $purse->setBalance($currentPurseBalance);

            $this->entityManager->persist($operation);
            $this->entityManager->persist($purse);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param Purse $purse
     * @param float $amount
     * @return boolean
     * @throws \Exception
     */
    public function removeMoney(Purse $purse, $amount)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $purseRemainBalance = floatval($purse->getBalance()) - $amount;
            if ($purseRemainBalance < 0) {
                throw new \Exception('Нельзя списать деньги в минус');
            }

            $operation = new Operation();
            $operation->setAmount($amount * -1);
            $operation->setPurse($purse);

            $currentPurseBalance = floatval($purse->getBalance()) - $amount;
            $purse->setBalance($currentPurseBalance);

            $this->entityManager->persist($operation);
            $this->entityManager->persist($purse);
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param Purse $purse
     * @param string $toCurrency
     * @return float
     */
    public function convertTo(Purse $purse, $toCurrency)
    {
        $purseBalance = floatval($purse->getBalance());
        $purseCurrency = $purse->getCurrency();

        return $this->converter->convert($purseBalance, $purseCurrency, $toCurrency);
    }
}