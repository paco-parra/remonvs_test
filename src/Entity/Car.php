<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $fuel;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $millage;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    private $power;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $bodyType;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $colorExterior;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $emission;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $transmission;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $externalId;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $externalUrl;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Equipment")
     * @ORM\JoinTable(name="cars_equipment",
     *      joinColumns={@ORM\JoinColumn(name="car_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="equipment_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $equipment;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Images")
     * @ORM\JoinTable(name="cars_images",
     *      joinColumns={@ORM\JoinColumn(name="car_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scrapers", inversedBy="cars", cascade={"persist"})
     * @ORM\JoinColumn(name="scraper_id", referencedColumnName="id")
     */
    private $scraper;

    public function __construct()
    {
        $this->equipment = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getFuel(): string
    {
        return $this->fuel;
    }

    /**
     * @param mixed $fuel
     */
    public function setFuel(string $fuel): void
    {
        $this->fuel = $fuel;
    }

    /**
     * @return mixed
     */
    public function getMillage(): string
    {
        return $this->millage;
    }

    /**
     * @param mixed $millage
     */
    public function setMillage(string $millage): void
    {
        $this->millage = $millage;
    }

    /**
     * @return mixed
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

    /**
     * @param mixed $registeredAt
     */
    public function setRegisteredAt($registeredAt): void
    {
        $this->registeredAt = $registeredAt;
    }

    /**
     * @return mixed
     */
    public function getPower(): string
    {
        return $this->power;
    }

    /**
     * @param mixed $power
     */
    public function setPower(string $power): void
    {
        $this->power = $power;
    }

    /**
     * @return mixed
     */
    public function getBodyType(): string
    {
        return $this->bodyType;
    }

    /**
     * @param mixed $bodyType
     */
    public function setBodyType(string $bodyType): void
    {
        $this->bodyType = $bodyType;
    }

    /**
     * @return mixed
     */
    public function getColorExterior(): string
    {
        return $this->colorExterior;
    }

    /**
     * @param mixed $colorExterior
     */
    public function setColorExterior(string $colorExterior): void
    {
        $this->colorExterior = $colorExterior;
    }

    /**
     * @return mixed
     */
    public function getEmission(): string
    {
        return $this->emission;
    }

    /**
     * @param mixed $emission
     */
    public function setEmission(string $emission): void
    {
        $this->emission = $emission;
    }

    /**
     * @return mixed
     */
    public function getTransmission(): string
    {
        return $this->transmission;
    }

    /**
     * @param mixed $transmission
     */
    public function setTransmission(string $transmission): void
    {
        $this->transmission = $transmission;
    }

    /**
     * @return mixed
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @param mixed $externalId
     */
    public function setExternalId(string $externalId): void
    {
        $this->externalId = $externalId;
    }

    /**
     * @return mixed
     */
    public function getExternalUrl(): string
    {
        return $this->externalUrl;
    }

    /**
     * @param mixed $externalUrl
     */
    public function setExternalUrl(string $externalUrl): void
    {
        $this->externalUrl = $externalUrl;
    }

    /**
     * @param ArrayCollection $equipment
     */
    public function setEquipment($equipment)
    {
        $this->equipment = $equipment;
    }

    public function addEquipment(Equipment $equipment)
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment->add($equipment);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment = null)
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment->removeElement($equipment);
        }
    }

    /**
     * @param ArrayCollection $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    public function addImage(Images $image)
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }

        return $this;
    }

    public function removeImage(Images $image = null)
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }
    }

    /**
     * @return mixed
     */
    public function getScraper():? Scrapers
    {
        return $this->scraper;
    }

    /**
     * @param mixed $scraper
     */
    public function setScraper(Scrapers $scraper): void
    {
        $this->scraper = $scraper;
    }

    public function getMainImage():? Images
    {
        if($this->images->first()->isMainImage()) return $this->images->first();

        foreach ($this->images as $image) {
            if($image->isMainImage()) return $image;
        }
        return null;
    }
}
