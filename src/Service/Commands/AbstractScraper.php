<?php
declare(strict_types=1);

namespace App\Service\Commands;

use App\Entity\Equipment;
use App\Entity\Scrapers;
use App\Exception\ElementPersistingException;
use App\Manager\CarManager;
use App\Manager\EquipmentManager;
use App\Manager\ImagesManager;
use App\Manager\ScraperManager;

abstract class AbstractScraper
{
    protected $carManager;
    protected $imagesManager;
    protected $equipmentManager;
    protected $scraperManager;
    protected $processedElement = [];

    public function __construct(CarManager $carManager, ImagesManager $imagesManager, EquipmentManager $equipmentManager, ScraperManager $scraperManager)
    {
        $this->carManager = $carManager;
        $this->imagesManager = $imagesManager;
        $this->equipmentManager = $equipmentManager;
        $this->scraperManager = $scraperManager;
    }

    protected function persistCar(string $url, array $dataScraped, Scrapers $scraper)
    {
        $car = $this->carManager->createOrRetrieveByUrl(['key' => 'externalUrl', 'value' => $url]);
        $car->setScraper($scraper);

        $car->setName($dataScraped['name']);
        $car->setPrice($dataScraped['price']);
        $car->setMillage($dataScraped['millage']);
        $car->setFuel($dataScraped['fuel']);
        $car->setRegisteredAt($dataScraped['registration_date']);
        $car->setPower($dataScraped['power']);
        $car->setBodyType($dataScraped['body_type']);
        $car->setEmission($dataScraped['emission']);
        $car->setTransmission($dataScraped['transmission']);
        $car->setExternalId($dataScraped['external_id']);
        $car->setColorExterior($dataScraped['color_exterior']);

        foreach ($dataScraped['images'] as $item) {
            $image = $this->imagesManager->createOrRetrieveBy(['key' => 'url', 'value' => $item]);
            $car->addImage($image);
        }

        foreach ($dataScraped['equipment'] as $type => $equipments) {
            $equipment = new Equipment();

            foreach ($equipments as $item) {
                $equipment->setType($type);
                $equipment->setName($item);
                $this->equipmentManager->save($equipment, true);
            }

            $car->addEquipment($equipment);
        }

        $this->carManager->save($car, true);

        $scraper->increaseLastScrapeElements();
        $this->scraperManager->save($scraper, true);

        print_r(sprintf("Car with URL %s saved \n", $url));
    }

    protected function resetProcessedElementArray()
    {
        $this->processedElement = [
            'name' => '',
            'price' => '',
            'millage' => '',
            'fuel' => '',
            'registration_date' => '',
            'power' => '',
            'body_type' => '',
            'emission' => '',
            'transmission' => '',
            'external_id' => '',
            'color_exterior' => '',
            'images' => [],
            'equipment' => [],
        ];
    }
}