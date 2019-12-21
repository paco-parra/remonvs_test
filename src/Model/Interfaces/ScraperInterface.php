<?php
declare(strict_types=1);

namespace App\Model\Interfaces;

interface ScraperInterface
{
    const ALL_SCRAPERS = 'ALL';

    //Main function to scrape web page, by default we scrape 5 pages (with 20 items per page, we have the 100 items required) and start in page number 1
    public function scrape(int $numberOfPages = 5, int $startPage = 1);

    // This function check if car scraped has the valid attributes
    public function isValidCar();

    public function processElement(string $url);

    public function getKey(): string ;

    // Get elements to scrape from the list page
    public function getElementsToScrape(int $numberOfPages, int $page = 1): void;

    // This function resolve last page scraped, AutoScout has a limit of 20 pages.
    public function resolveLastPage(int $initialPage, int $numberOfPages);
}