<?php

namespace Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DatabaseCommand.
 */
class SampleCommand extends Console
{
    /**
     *  Configure commands to run.
     */
    protected function configure()
    {
        $this->setName('app:sample');
        $this->setDescription('Sample command console');
    }

    /**
     * Determines how this command will be executed.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = "<info>Hello, I'm console</info>";
        $output->writeln($message);

        return Command::SUCCESS;
    }
}
