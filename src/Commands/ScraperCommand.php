<?php
declare(strict_types=1);

namespace App\Commands;

use App\Manager\ScraperManager;
use App\Model\Interfaces\ScraperInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScraperCommand extends Command
{

    private $scraperManager;

    public function __construct(ScraperManager $scraperManager)
    {
        $this->scraperManager = $scraperManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:scraper')
            ->setDescription('Execute scraper, all scrapers are executed by default')
            ->addOption('scraper', null, InputArgument::OPTIONAL, "Execute specific scraper, don't use if you want execute all", ScraperInterface::ALL_SCRAPERS)
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->scraperManager->scrape($input->getOption('scraper'));
        } catch (\Exception $exception) {
            $output->write($exception);
        }

    }

}