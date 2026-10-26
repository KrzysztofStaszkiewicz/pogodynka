<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'measurements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $Date = null;

    #[ORM\Column]
    private ?int $Temperature = null;

    #[ORM\Column]
    private ?int $Wind = null;

    #[ORM\Column(length: 255)]
    private ?string $Cloudiness = null;

    #[ORM\Column]
    private ?int $Precipitation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->Date;
    }

    public function setDate(\DateTime $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getTemperature(): ?int
    {
        return $this->Temperature;
    }

    public function setTemperature(int $Temperature): static
    {
        $this->Temperature = $Temperature;

        return $this;
    }

    public function getWind(): ?int
    {
        return $this->Wind;
    }

    public function setWind(int $Wind): static
    {
        $this->Wind = $Wind;

        return $this;
    }

    public function getCloudiness(): ?string
    {
        return $this->Cloudiness;
    }

    public function setCloudiness(string $Cloudiness): static
    {
        $this->Cloudiness = $Cloudiness;

        return $this;
    }

    public function getPrecipitation(): ?int
    {
        return $this->Precipitation;
    }

    public function setPrecipitation(int $Precipitation): static
    {
        $this->Precipitation = $Precipitation;

        return $this;
    }
}
