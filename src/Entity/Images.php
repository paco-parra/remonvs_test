<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImagesRepository")
 */
class Images
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $url;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mainImage;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function isMainImage(): ?bool
    {
        return $this->mainImage;
    }

    public function setMainImage(?bool $mainImage): void
    {
        $this->mainImage = $mainImage;
    }
}
