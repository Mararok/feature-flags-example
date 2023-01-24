<?php

namespace App\Command;

use App\AppFeatureFlags;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetFeatureFlagsCacheCommand extends Command
{
    public function __construct(private AppFeatureFlags $ff)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName("app:system:feature-flags:cache:get")
            ->setDescription("Gets cache with current feature flags");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(json_encode($this->ff->getRawCached(), JSON_PRETTY_PRINT));
        return Command::SUCCESS;
    }
}
