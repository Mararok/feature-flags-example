<?php

namespace App\Command;

use App\AppFeatureFlags;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshFeatureFlagsCacheCommand extends Command
{

    public function __construct(private AppFeatureFlags $ff)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName("app:system:feature-flags:cache:refresh")
            ->setDescription("Updates cache with current feature flags");

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->ff->refreshCache();
        return Command::SUCCESS;
    }
}
