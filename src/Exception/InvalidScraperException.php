<?php
declare(strict_types=1);

namespace App\Exception;

class InvalidScraperException extends \Exception implements CustomExceptionInterface
{
    public function __toString()
    {
        return sprintf("%s in %s line %s", $this->getMessage(), $this->getFile(), $this->getLine());
    }
}