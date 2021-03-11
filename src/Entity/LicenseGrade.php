<?php

namespace App\Entity;

use App\Repository\LicenseGradeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=LicenseGradeRepository::class)
 */
class LicenseGrade extends BaseDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_driver"})
     */
    private $gradeLetter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=License::class, mappedBy="licenseGrade")
     */
    private $licenses;

    /**
     * @ORM\ManyToOne(targetEntity=GradeType::class, inversedBy="licenseGrades")
     */
    private $gradeType;

    /**
     * @ORM\OneToMany(targetEntity=LicenseGradePrice::class, mappedBy="licenseGrade")
     */
    private $licenseGradePrices;

    public function __construct()
    {
        $this->licenses = new ArrayCollection();
        $this->licenseGradePrices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGradeLetter(): ?string
    {
        return $this->gradeLetter;
    }

    public function setGradeLetter(string $gradeLetter): self
    {
        $this->gradeLetter = $gradeLetter;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|License[]
     */
    public function getLicenses(): Collection
    {
        return $this->licenses;
    }

    public function addLicense(License $license): self
    {
        if (!$this->licenses->contains($license)) {
            $this->licenses[] = $license;
            $license->setLicenseGrade($this);
        }

        return $this;
    }

    public function removeLicense(License $license): self
    {
        if ($this->licenses->removeElement($license)) {
            // set the owning side to null (unless already changed)
            if ($license->getLicenseGrade() === $this) {
                $license->setLicenseGrade(null);
            }
        }

        return $this;
    }

    public function getGradeType(): ?GradeType
    {
        return $this->gradeType;
    }

    public function setGradeType(?GradeType $gradeType): self
    {
        $this->gradeType = $gradeType;

        return $this;
    }

    /**
     * @return Collection|LicenseGradePrice[]
     */
    public function getLicenseGradePrices(): Collection
    {
        return $this->licenseGradePrices;
    }

    public function addLicenseGradePrice(LicenseGradePrice $licenseGradePrice): self
    {
        if (!$this->licenseGradePrices->contains($licenseGradePrice)) {
            $this->licenseGradePrices[] = $licenseGradePrice;
            $licenseGradePrice->setLicenseGrade($this);
        }

        return $this;
    }

    public function removeLicenseGradePrice(LicenseGradePrice $licenseGradePrice): self
    {
        if ($this->licenseGradePrices->removeElement($licenseGradePrice)) {
            // set the owning side to null (unless already changed)
            if ($licenseGradePrice->getLicenseGrade() === $this) {
                $licenseGradePrice->setLicenseGrade(null);
            }
        }

        return $this;
    }

    public function __toString() : string
    {
        return $this->gradeLetter;
    }
}
