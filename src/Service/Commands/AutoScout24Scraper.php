<?php
declare(strict_types=1);

namespace App\Service\Commands;

use App\Model\Interfaces\ScraperInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;

class AutoScout24Scraper implements ScraperInterface
{
    private $entityManager;
    private $crawler;
    private $elementsToScrape = [];

    const BASE_URL = "https://www.autoscout24.de";
    const URL_TO_SCRAPE = 'https://www.autoscout24.de/lst/audi/a6?sort=standard&amp;desc=0&amp;ustate=N%2CU&amp;cy=D&amp;fregfrom=2012&amp;atype=C&amp;ac=0&size=20&page=';
    const KEY = 'auto_scout';
    const LAST_PAGE = 20;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function scrape(int $numberOfPages = 5, int $startPage = 1)
    {
        // Store all required elements to scrape in a array
        $this->getElementsToScrape($numberOfPages, $startPage);

        print_r($this->elementsToScrape);
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
        // Calculate last page starting in $page value
        $lastPage = ($page + $numberOfPages) < self::LAST_PAGE ? ($page + $numberOfPages) : self::LAST_PAGE;

        while($page <= $lastPage) {
            $url = sprintf("%s%s", self::URL_TO_SCRAPE, $page);
            print_r(sprintf("Scraping page %s with URL %s \n", $page, $url));
            $this->crawler = new Crawler(file_get_contents($url));
            $numberOfElements = $this->crawler->filterXPath('//div[@class="cl-list-element cl-list-element-gap"]')->count();

            // Recursive call to avoid web page request blocking
            if($numberOfElements == 0) continue;

            $this->crawler->filterXPath('//div[@class="cl-list-element cl-list-element-gap"]')->each(function (Crawler $node) {
                $link = $node->filterXPath('//a[@data-item-name="detail-page-link"]')->attr('href');
                print_r(sprintf("URL %s from \n", $link));
                $this->elementsToScrape[] = sprintf('%s%s', self::BASE_URL, $link);
            });
            $page++;
        }
    }

    public function getKey(): string
    {
        return self::KEY;
    }
}