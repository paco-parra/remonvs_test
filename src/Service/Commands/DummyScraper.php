<?php
declare(strict_types=1);

namespace App\Service\Commands;

use App\Manager\CarManager;
use App\Manager\EquipmentManager;
use App\Manager\ImagesManager;
use App\Manager\ScraperManager;
use App\Model\Interfaces\ScraperInterface;

class DummyScraper extends AbstractScraper implements ScraperInterface
{
    private $scraper;
    private $dateLimit;

    const KEY = 'dummy';
    const LAST_PAGE = 10;
    const URL_TO_SCRAPE = "http://url-for-dummy-scraper/category-list?page=";
    const BASE_URL = 'http://url-for-dummy-scraper';

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

            $numberOfElements = rand(3, 6);

            for($i = 1; $i <= $numberOfElements; $i++) {
                $link = sprintf("/category-%s/dummy-product-to-scrape-%s", $page, $i);
                $itemUrl = sprintf('%s%s', self::BASE_URL, $link);
                $this->resetProcessedElementArray();
                $validElement = $this->processElement($itemUrl);
                if(!$validElement) continue;
                $this->persistCar($itemUrl, $this->processedElement, $this->scraper);
            }

            $page++;
        }
    }

    public function processElement(string $url)
    {
        print_r(sprintf("Processing car with URL: %s \n", $url));

        $this->processedElement['name'] = sprintf('dummy-name-%s', rand(1,50));
        $this->processedElement['price'] = sprintf('dummy-price-%s', rand(1,50));
        $this->processedElement['millage'] = sprintf('dummy-millage-%s', rand(1,50));
        $this->processedElement['registration_date'] = sprintf('%s/%s', rand(1,12), rand(2010, 2019));
        $this->processedElement['power'] = sprintf('dummy-power-%s', rand(1,50));
        $this->processedElement['fuel'] = sprintf('dummy-fuel-%s', rand(1,50));
        $this->processedElement['emission'] = sprintf('dummy-emission-%s', rand(1,50));
        $this->processedElement['transmission'] = sprintf('dummy-transmission-%s', rand(1,50));
        $this->processedElement['external_id'] = sprintf('dummy-external-id-%s', rand(1,50));
        $this->processedElement['color_exterior'] = sprintf('dummy-color-exterior-%s', rand(1,50));
        $this->processedElement['body_type'] = sprintf('dummy-body-type-%s', rand(1,50));

        if(!$this->isValidCar()) {
            print_r(sprintf("The car with URL %s has not valid attributes \n", $url));
            return false;
        }

        for($i=1; $i <= rand(8, 13); $i++) {
            array_push($this->processedElement['images'], sprintf('https://image-repository.url-for-dummy-scraper/img-%s', rand(1, 1000000)));
        }

        $equipmentType = ['Type-A', 'Type-B', 'Type-C', 'Type-D'];
        foreach ($equipmentType as $item) {
            $this->processedElement['equipment'] += [ $item => []];
            for($i=1; $i <= rand(8, 13); $i++) {
                array_push($this->processedElement['equipment'][$item], sprintf('equipment-%s-%s', $item, $i));
            }
        }

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