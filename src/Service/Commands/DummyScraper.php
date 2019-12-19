<?php
declare(strict_types=1);

namespace App\Service\Commands;

use App\Model\Interfaces\ScraperInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;

class DummyScraper implements ScraperInterface
{
    private $entityManager;

    const KEY = 'dummy';

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function scrape(int $numberOfPages = 5, int $startPage = 1)
    {
        print_r(sprintf("Executing %s scraper, please wait for completion \n", $this->getKey()));
        return "I'm in";
        // TODO: Implement scrape() method.
    }

    public function isValidCar(iterable $scrapedObject)
    {
        // Registration year > 2012
        // Has emission
    }

    public function formatData(iterable $scrapedObject)
    {
        // TODO: Implement formatData() method.
    }

    public function getElementsToScrape(int $numberOfPages, int $page = 1): void
    {
    }

    public function processElement(string $url)
    {
    }

    public function getKey(): string
    {
        return self::KEY;
    }
}