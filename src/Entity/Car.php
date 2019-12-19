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
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $fuel;

    /**
     * @ORM\Column(type="float")
     */
    private $millage;

    /**
     * @ORM\Column(type="date")
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="string", length=35)
     */
    private $power;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $bodyType;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $colorExterior;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $emission;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $transmission;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $externalId;

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
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice(float $price): void
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
    public function getMillage(): float
    {
        return $this->millage;
    }

    /**
     * @param mixed $millage
     */
    public function setMillage(float $millage): void
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
        if (!$this->images->contains($image)) {
            $this->images->removeElement($image);
        }
    }
}
