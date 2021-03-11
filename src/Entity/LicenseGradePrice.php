<?php

namespace App\Entity;

use App\Repository\LicenseGradePriceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LicenseGradePriceRepository::class)
 */
class LicenseGradePrice extends BaseDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=LicenseGrade::class, inversedBy="licenseGradePrices")
     */
    private $licenseGrade;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="date")
     */
    private $year;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLicenseGrade(): ?LicenseGrade
    {
        return $this->licenseGrade;
    }

    public function setLicenseGrade(?LicenseGrade $licenseGrade): self
    {
        $this->licenseGrade = $licenseGrade;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(\DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function __toString()
    {
        return $this->licenseGrade;
    }
}
