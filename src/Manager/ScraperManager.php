<?php
declare(strict_types=1);

namespace App\Manager;

use App\Model\Interfaces\ScraperInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ScraperManager
{
    private $allScrapers = [];

    public function __construct()
    {
    }

    public function scrape(string $scraper = ScraperInterface::ALL_SCRAPERS, int $numberOfPages = 5, int $startPage = 1)
    {
        $scrapers = $this->getActiveScrapers($scraper);

        foreach ($scrapers as $scraper) {
            $scraper->scrape((int) $numberOfPages, (int) $startPage);
        }
    }

    private function getActiveScrapers($scraperStrategy)
    {
        if($scraperStrategy == ScraperInterface::ALL_SCRAPERS) return $this->getScrapers();

        foreach ($this->getScrapers() as $scraper) {
            if($scraperStrategy == $scraper->getKey()) {
                return [$scraper];
            }
        }

        throw new InvalidArgumentException(sprintf('Any scraper found with key %s', $scraperStrategy));
    }

    public function getScrapers()
    {
        return $this->allScrapers;
    }

    public function addScraper(ScraperInterface $scraper)
    {
        $this->allScrapers[] = $scraper;
    }
}