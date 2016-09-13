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
use Symfony\Component\Console\Question\ConfirmationQuestion;

class AddCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('balance:add')
            ->setDescription('Add money to balance')
            ->setHelp('This command allows you to add money')

            ->addArgument('amount', InputArgument::REQUIRED, 'How many money you add to balance.')
            ->addArgument('currency', InputArgument::OPTIONAL, 'What currency you need.', Purse::CURRENCY_RUB)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $amount = $input->getArgument('amount');
            $currency = $input->getArgument('currency');

            if (!is_numeric($amount) || $amount <= 0) {
                throw new \Exception('Не правильно указана сумма');
            }

            $balanceManager = $this->getContainer()->get('balance.manager');
            $purse = $balanceManager->getPurseByCurrency($currency);
            if (!$purse) {
                throw new \Exception('Кошелек не найден');
            }

            $helper = $this->getHelper('question');
            $questionString = sprintf('<question>Вы хотите пополнить кошелек %s, на %s %s? (y|N)</question> ',
                $purse->getTitle(),
                $amount,
                $currency
            );
            $question = new ConfirmationQuestion($questionString, false);
            if (!$helper->ask($input, $output, $question)) {
                throw new \Exception('Операция отменена');
            }

            $balanceManager->addMoney($purse, $amount);

            $successMessage = sprintf('<info>Пополнение кошелька %s, на %s %s, прошло успешно</info>',
                $purse->getTitle(),
                $amount,
                $currency
            );
            $output->writeln($successMessage);
        } catch (\Exception $e) {
            $errorMessage = sprintf('<error>%s</error>', $e->getMessage());
            $output->writeln($errorMessage);
        }
    }
}