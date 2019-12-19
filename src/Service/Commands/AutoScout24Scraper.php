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
    private $processedElements = [];

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

        foreach ($this->elementsToScrape as $item) {
            $this->processElement($item);
        }

        print_r($this->processedElements);
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
        // Calculate last page scraped, starting in $page value
        $lastPage = $this->resolverLastPage($page, $numberOfPages);

        while($page < $lastPage) {
            $url = sprintf("%s%s", self::URL_TO_SCRAPE, $page);
            print_r(sprintf("Scraping page %s with URL %s \n", $page, $url));
            $this->crawler = new Crawler(file_get_contents($url));
            $numberOfElements = $this->crawler->filterXPath('//div[@class="cl-list-element cl-list-element-gap"]')->count();

            // Recursive call to avoid web page request blocking
            if($numberOfElements == 0) continue;

            $this->crawler->filterXPath('//div[@class="cl-list-element cl-list-element-gap"]')->each(function (Crawler $node) {
                $link = $node->filterXPath('//a[@data-item-name="detail-page-link"]')->attr('href');
//                print_r(sprintf("URL %s from \n", $link));
                $this->elementsToScrape[] = sprintf('%s%s', self::BASE_URL, $link);
            });
            $page++;
        }
    }

    public function processElement(string $url)
    {
        print_r(sprintf("Processing car with URL: %s \n", $url));
        $this->crawler = new Crawler(file_get_contents($url));
        $this->processedElements[$url] = [];
        $this->processedElements[$url] += ['title' => trim($this->crawler->filterXPath('//h1[@class="cldt-detail-title sc-ellipsis"]')->text(null, true))];
        $this->processedElements[$url] += ['price' => trim($this->crawler->filterXPath('//div[@class="cldt-price"]')->text(null, true))];
        $this->processedElements[$url] += ['millage' => trim($this->crawler->filterXPath('//div[@class="cldt-stage-basic-data"]')->children()->eq(0)->children()->eq(1)->text(null, true))];
        $this->processedElements[$url] += ['registration_date' => trim($this->crawler->filterXPath('//div[@class="cldt-stage-basic-data"]')->children()->eq(1)->children()->eq(1)->text(null, true))];
        $this->processedElements[$url] += ['power' => sprintf('%s %s',
            trim($this->crawler->filterXPath('//div[@class="cldt-stage-basic-data"]')->children()->eq(2)->children()->eq(1)->text(null, true)),
            trim($this->crawler->filterXPath('//div[@class="cldt-stage-basic-data"]')->children()->eq(2)->children()->eq(2)->text(null, true))
        )];
        $this->crawler->filterXPath('//dt[@class="sc-ellipsis"]')->each(function (Crawler $node) use ($url) {
            switch ($node->filterXPath('//text()[not(as24-footnote-item)]')->text()) {
                case 'Kraftstoff':
                    $this->processedElements[$url] += ['fuel' => $node->nextAll()->text(null, true)];
                    break;
                case 'CO2-Emissionen':
                    $this->processedElements[$url] += ['emission' => $node->nextAll()->text(null, true)];
                    break;
                case 'Getriebeart':
                    $this->processedElements[$url] += ['transmission' => $node->nextAll()->text(null, true)];
                    break;
                case 'Angebotsnummer':
                    $this->processedElements[$url] += ['external_id' => $node->nextAll()->text(null, true)];
                    break;
                case 'Außenfarbe':
                    $this->processedElements[$url] += ['color_exterior' => $node->nextAll()->text(null, true)];
                    break;
                case 'Karosserieform':
                    $this->processedElements[$url] += ['body_type' => $node->nextAll()->text(null, true)];
                    break;
            }
        });

        $this->crawler->filterXPath('//div[@class="gallery-picture sc-lazy-image"][not(data-item="three-sixty-picture")]')->each(function (Crawler $node) use ($url) {
            $this->processedElements[$url] += ['images' => []];
            array_push($this->processedElements[$url]['images'], $node->children()->eq(0)->attr('data-fullscreen-src'));
        });

        $this->processedElements[$url] += ['equipment' => []];
        $this->crawler->filterXPath('//div[@class="cldt-equipment-block sc-grid-col-3 sc-grid-col-m-4 sc-grid-col-s-12 sc-pull-left"]')->each(function (Crawler $node) use ($url) {
            $equipmentType = $node->filterXPath('//h3[@class="sc-font-bold"]')->text();
            $this->processedElements[$url]['equipment'] += [ $equipmentType => []];
            $node->filterXPath('//span')->each(function (Crawler $equipmentNode) use ($url, $equipmentType) {
                array_push($this->processedElements[$url]['equipment'][$equipmentType], $equipmentNode->text());
            });
        });
    }

    public function getKey(): string
    {
        return self::KEY;
    }

    // This function resolve last page scraped, AutoScout has a limit of 20 pages.
    private function resolverLastPage(int $initialPage, int $numberOfPages)
    {
        $lastPage = $initialPage + $numberOfPages;
        if($lastPage < self::LAST_PAGE) return $lastPage;
        return self::LAST_PAGE;
    }
}