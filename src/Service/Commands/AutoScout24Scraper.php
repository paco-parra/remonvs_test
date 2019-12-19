<?php
declare(strict_types=1);

namespace App\Service\Commands;

use App\Model\Interfaces\ScraperInterface;
use Doctrine\ORM\EntityManager;

class AutoScout24Scraper implements ScraperInterface
{
    private $entityManager;

    const KEY = 'auto_scout';

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function scrape()
    {
        print_r('Im in');
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

    public function getKey()
    {
        return self::KEY;
    }
}