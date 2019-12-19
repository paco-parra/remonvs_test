<?php

namespace App\Model\Interfaces;

interface ScraperInterface
{
    const ALL_SCRAPERS = 'ALL';

    //Main function to scrape web page
    public function scrape();

    // This function check if car scraped has the valid attributes
    public function isValidCar(iterable $scrapedObject);

    // Format data to persist in database
    public function formatData(iterable $scrapedObject);

    public function getKey();
}