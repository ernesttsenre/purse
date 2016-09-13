<?php

/**
 * Created by PhpStorm.
 * User: olegivanov
 * Date: 13.09.16
 * Time: 13:13
 */

namespace BalanceBundle\Command;

use BalanceBundle\Entity\Purse;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class BalanceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('balance:balance')
            ->setDescription('Show balance from all purses')
            ->setHelp('This command show you balance from all purses')

            ->addArgument('inCurrency', InputArgument::OPTIONAL, 'What currency you need.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inCurrency = $input->getArgument('inCurrency');

        $balanceManager = $this->getContainer()->get('balance.manager');
        $purses = $balanceManager->getPurses();

        if (is_null($inCurrency)) {
            foreach ($purses as $purse) {
                $purseFormatString = sprintf('<info>%s</info>: %.2f', $purse->getTitle(), $purse->getBalance());
                $output->writeln($purseFormatString);
            }
        } else {
            $output->writeln(sprintf('<info>Баланс выводится в валюте: %s</info>', strtoupper($inCurrency)));
            $output->writeln('');

            $totalAmount = 0;
            foreach ($purses as $purse) {
                $amount = $balanceManager->convertTo($purse, $inCurrency);
                $totalAmount += $amount;

                $purseFormatString = sprintf('<info>%s</info>: %.4f %s', $purse->getTitle(), $amount, $inCurrency);
                $output->writeln($purseFormatString);
            }

            $output->writeln('');
            $purseFormatString = sprintf('<info>Всего</info>: %.4f %s', $totalAmount, $inCurrency);
            $output->writeln($purseFormatString);
        }
    }
}