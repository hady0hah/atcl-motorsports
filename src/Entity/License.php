<?php

namespace App\Entity;

use App\Repository\LicenseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LicenseRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class License extends BaseDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_driver"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class, inversedBy="licenses")
     */
    private $driver;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"show_driver"})
     */
    private $issuedDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_driver"})
     */
    private $licenseNumber;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"show_driver"})
     */
    private $expiryDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"show_driver"})
     */
    private $fiaMedStdDate;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"show_driver"})
     */
    private $correctedEyesight;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"show_driver"})
     */
    private $medSupervision;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"show_driver"})
     */
    private $wadb;

    /**
     * @ORM\ManyToOne(targetEntity=LicenseGrade::class, inversedBy="licenses")
     * @Groups({"show_driver"})
     */
    private $licenseGrade;

    /**
     * @ORM\ManyToOne(targetEntity=LicenseType::class, inversedBy="licenses")
     */
    private $licenseType;

    /**
     * @ORM\Column(type="integer")
     */
    private $sequenceNumber;

    /**
     * @Groups({"show_driver"})
     */
    public $status;

    public function getId()
    {
        return $this->id;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getIssuedDate(): ?\DateTimeInterface
    {
        return $this->issuedDate;
    }

    public function setIssuedDate(\DateTimeInterface $issuedDate): self
    {
        $this->issuedDate = $issuedDate;

        return $this;
    }

    public function getLicenseNumber(): ?string
    {
        return $this->licenseNumber;
    }

    public function setLicenseNumber(string $licenseNumber): self
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    public function getExpiryDate(): ?\DateTimeInterface
    {
        return $this->expiryDate;
    }

    public function setExpiryDate(\DateTimeInterface $expiryDate): self
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    public function getFiaMedStdDate(): ?\DateTimeInterface
    {
        return $this->fiaMedStdDate;
    }

    public function setFiaMedStdDate(\DateTimeInterface $fiaMedStdDate): self
    {
        $this->fiaMedStdDate = $fiaMedStdDate;

        return $this;
    }

    public function getCorrectedEyesight(): ?bool
    {
        return $this->correctedEyesight;
    }

    public function setCorrectedEyesight(bool $correctedEyesight): self
    {
        $this->correctedEyesight = $correctedEyesight;

        return $this;
    }

    public function getMedSupervision(): ?bool
    {
        return $this->medSupervision;
    }

    public function setMedSupervision(bool $medSupervision): self
    {
        $this->medSupervision = $medSupervision;

        return $this;
    }

    public function getWadb(): ?bool
    {
        return $this->wadb;
    }

    public function setWadb(bool $wadb): self
    {
        $this->wadb = $wadb;

        return $this;
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

    public function getLicenseType(): ?LicenseType
    {
        return $this->licenseType;
    }

    public function setLicenseType(?LicenseType $licenseType): self
    {
        $this->licenseType = $licenseType;

        return $this;
    }

    public function getSequenceNumber(): ?int
    {
        return $this->sequenceNumber;
    }

    public function setSequenceNumber(?int $sequenceNumber): self
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

    /**
     * @ORM\PostLoad()
     */
    public function setStatus()
    {
        $this->status = $this->getExpiryDate() >= new \DateTime('now') ? 'Active' : 'Expired';
    }
}
