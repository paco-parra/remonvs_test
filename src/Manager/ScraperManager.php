<?php
declare(strict_types=1);

namespace App\Manager;

use App\Entity\Scrapers;
use App\Model\Interfaces\ScraperInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ScraperManager extends AbstractBaseManager
{
    private $allScrapers = [];

    public function createOrRetrieveBy(array $findKey)
    {
        $scraper = $this->entityManager->getRepository($this->class)->findOneBy([$findKey['key'] => $findKey['value']]);

        if(!$scraper instanceof Scrapers) {
            $scraper = $this->create();
            $scraper->setKeyName($findKey['value']);
            $this->save($scraper, true);
        }
        return $scraper;
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