<?php
declare(strict_types=1);

namespace App\Service\Commands;

use App\Manager\CarManager;
use App\Manager\EquipmentManager;
use App\Manager\ImagesManager;
use App\Manager\ScraperManager;
use App\Model\Interfaces\ScraperInterface;
use Symfony\Component\DomCrawler\Crawler;

class AutoScout24Scraper extends AbstractScraper implements ScraperInterface
{
    private $crawler;
    private $scraper;
    private $dateLimit;

    const BASE_URL = "https://www.autoscout24.de";
    const URL_TO_SCRAPE = 'https://www.autoscout24.de/lst/audi/a6?sort=standard&amp;desc=0&amp;ustate=N%2CU&amp;cy=D&amp;fregfrom=2012&amp;atype=C&amp;ac=0&size=20&page=';
    const KEY = 'auto_scout';
    const LAST_PAGE = 20;

    public function __construct(CarManager $carManager, ImagesManager $imagesManager, EquipmentManager $equipmentManager, ScraperManager $scraperManager)
    {
        parent::__construct($carManager, $imagesManager, $equipmentManager, $scraperManager);
        $this->scraper = $this->scraperManager->createOrRetrieveBy(['key' => 'keyName', 'value' => self::KEY]);
        $this->dateLimit = date_create_from_format('m/Y', '01/2012');
    }

    public function scrape(int $numberOfPages = 5, int $startPage = 1)
    {
        print_r(sprintf("Executing %s scraper, please wait for completion \n", $this->getKey()));

        $this->scraper->resetLastScrapedElements();
        $this->scraper->setLastScrapDate(new \DateTime());
        $this->scraperManager->save($this->scraper);

        // Store all required elements to scrape in a array
        $this->getElementsToScrape($numberOfPages, $startPage);
    }

    public function isValidCar()
    {
        $valid = true;
        $date = date_create_from_format('m/Y', $this->processedElement['registration_date']);
        $emission = $this->processedElement['emission'];

        if (($date < $this->dateLimit) || !$emission) {
            $valid = false;
        }

        return $valid;
    }

    public function getElementsToScrape(int $numberOfPages, int $page = 1): void
    {
        // Calculate last page scraped, starting in $page value
        $lastPage = $this->resolveLastPage($page, $numberOfPages);

        while($page < $lastPage) {
            $url = sprintf("%s%s", self::URL_TO_SCRAPE, $page);
            print_r(sprintf("Scraping page %s with URL %s \n", $page, $url));
            $this->crawler = new Crawler(file_get_contents($url));
            $numberOfElements = $this->crawler->filterXPath('//div[@class="cl-list-element cl-list-element-gap"]')->count();

            // Recursive call to avoid web page request blocking
            if($numberOfElements == 0) continue;

            $this->crawler->filterXPath('//div[@class="cl-list-element cl-list-element-gap"]')->each(function (Crawler $node) {
                $link = $node->filterXPath('//a[@data-item-name="detail-page-link"]')->attr('href');
                $itemUrl = sprintf('%s%s', self::BASE_URL, $link);
                $this->resetProcessedElementArray();
                $validElement = $this->processElement($itemUrl);
                if(!$validElement) return;
                $this->persistCar($itemUrl, $this->processedElement, $this->scraper);
            });
            $page++;
        }
    }

    public function processElement(string $url)
    {
        print_r(sprintf("Processing car with URL: %s \n", $url));

        $this->crawler = new Crawler(file_get_contents($url));
        $this->processedElement['name'] = trim($this->crawler->filterXPath('//h1[@class="cldt-detail-title sc-ellipsis"]')->text(null, true));
        $this->processedElement['price'] = trim($this->crawler->filterXPath('//div[@class="cldt-price"]')->text(null, true));
        $this->processedElement['millage'] = trim($this->crawler->filterXPath('//div[@class="cldt-stage-basic-data"]')->children()->eq(0)->children()->eq(1)->text(null, true));
        $this->processedElement['registration_date'] = trim($this->crawler->filterXPath('//div[@class="cldt-stage-basic-data"]')->children()->eq(1)->children()->eq(1)->text(null, true));
        $this->processedElement['power'] = sprintf('%s %s',
            trim($this->crawler->filterXPath('//div[@class="cldt-stage-basic-data"]')->children()->eq(2)->children()->eq(1)->text(null, true)),
            trim($this->crawler->filterXPath('//div[@class="cldt-stage-basic-data"]')->children()->eq(2)->children()->eq(2)->text(null, true))
        );
        $this->crawler->filterXPath('//dt[@class="sc-ellipsis"]')->each(function (Crawler $node) use ($url) {
            switch ($node->filterXPath('//text()[not(as24-footnote-item)]')->text()) {
                case 'Kraftstoff':
                    $this->processedElement['fuel'] = $node->nextAll()->text(null, true);
                    break;
                case 'CO2-Emissionen':
                    $this->processedElement['emission'] = $node->nextAll()->text(null, true);
                    break;
                case 'Getriebeart':
                    $this->processedElement['transmission'] = $node->nextAll()->text(null, true);
                    break;
                case 'Angebotsnummer':
                    $this->processedElement['external_id'] = $node->nextAll()->text(null, true);
                    break;
                case 'Außenfarbe':
                    $this->processedElement['color_exterior'] = $node->nextAll()->text(null, true);
                    break;
                case 'Karosserieform':
                    $this->processedElement['body_type'] = $node->nextAll()->text(null, true);
                    break;
            }
        });

        if(!$this->isValidCar()) {
            print_r(sprintf("The car with URL %s has not valid attributes \n", $url));
            return false;
        }

        $this->crawler->filterXPath('//div[@class="gallery-picture sc-lazy-image"][not(data-item="three-sixty-picture")]')->each(function (Crawler $node) use ($url) {
            array_push($this->processedElement['images'], $node->children()->eq(0)->attr('data-fullscreen-src'));
        });

        $this->crawler->filterXPath('//div[@class="cldt-equipment-block sc-grid-col-3 sc-grid-col-m-4 sc-grid-col-s-12 sc-pull-left"]')->each(function (Crawler $node) use ($url) {
            $equipmentType = $node->filterXPath('//h3[@class="sc-font-bold"]')->text();
            $this->processedElement['equipment'] += [ $equipmentType => []];
            $node->filterXPath('//span')->each(function (Crawler $equipmentNode) use ($url, $equipmentType) {
                array_push($this->processedElement['equipment'][$equipmentType], $equipmentNode->text());
            });
        });

        return true;
    }

    public function getKey(): string
    {
        return self::KEY;
    }

    public function resolveLastPage(int $initialPage, int $numberOfPages)
    {
        $lastPage = $initialPage + $numberOfPages;
        if($lastPage < self::LAST_PAGE) return $lastPage;
        return self::LAST_PAGE;
    }
}