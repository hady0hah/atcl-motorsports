<?php

namespace App\Entity;

use App\Repository\GradeTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GradeTypeRepository::class)
 */
class GradeType extends OType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=LicenseGrade::class, mappedBy="gradeType")
     */
    private $licenseGrades;

    /**
     * @ORM\Column(type="json")
     */
    private $config = [];

    public function __construct()
    {
        parent::__construct();
        $this->module = strtolower(get_class($this));
        $this->licenseGrades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|LicenseGrade[]
     */
    public function getLicenseGrades(): Collection
    {
        return $this->licenseGrades;
    }

    public function addLicenseGrade(LicenseGrade $licenseGrade): self
    {
        if (!$this->licenseGrades->contains($licenseGrade)) {
            $this->licenseGrades[] = $licenseGrade;
            $licenseGrade->setGradeType($this);
        }

        return $this;
    }

    public function removeLicenseGrade(LicenseGrade $licenseGrade): self
    {
        if ($this->licenseGrades->removeElement($licenseGrade)) {
            // set the owning side to null (unless already changed)
            if ($licenseGrade->getGradeType() === $this) {
                $licenseGrade->setGradeType(null);
            }
        }

        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }
}
