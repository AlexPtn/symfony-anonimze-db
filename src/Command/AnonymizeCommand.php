<?php

namespace App\Command;

use App\Manager\AnonymizerManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnonymizeCommand extends Command
{
    public function __construct(private readonly AnonymizerManager $anonymiser)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:anonymize')
            ->setDescription('Anonymize sensitive data in the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->anonymiser->anonymize();

        $output->writeln('Data has been anonymized successfully.');

        return 0;
    }

}
