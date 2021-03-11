<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=DriverRepository::class)
 * @Vich\Uploadable
 */
class Driver extends BaseDocument
{
    const BLOOD_TYPES = [
        "A-" => "A-",
        "A+" => "A+",
        "B-" => "B-",
        "B+" => "B+",
        "O-" => "O-",
        "O+" => "O+",
        "AB+" => "AB+",
        "AB-" => "AB-",
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_driver"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"show_driver"})
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="driver_image", fileNameProperty="image")
     * @Assert\Image(
     *     minRatio=0.8,
     *     maxRatio=0.8,
     *     minRatioMessage="The image should have a ratio of 4/5",
     *     maxRatioMessage="The image should have a ratio of 4/5"
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_event_results", "show_driver"})
     *
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_event_results", "show_driver"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $licenseNumber = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $idNumber;

//    private $status;
    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="driver")
     */
    private $participants;


    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"show_driver"})
     */
    private $dateOfBirth;

    /**
     * @ORM\OneToMany(targetEntity=License::class, mappedBy="driver")
     */
    private $licenses;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"show_driver"})
     */
    private $bloodType;

    /**
     * @Groups({"show_driver"})
     */
    public $currentLicenses;

    /**
     *
     */
    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->licenses = new ArrayCollection();
        $this->currentLicenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage() : ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($imageFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->modifiedAt = new \DateTime('now');
        }
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLicenseNumber(): ?array
    {
        $licenseNumber = $this->licenseNumber;

        return array_unique($licenseNumber);
    }

    public function setLicenseNumber(?array $licenseNumber): self
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    public function getIdType(): ?string
    {
        return $this->idType;
    }

    public function setIdType(string $idType): self
    {
        $this->idType = $idType;

        return $this;
    }

    public function getIdNumber(): ?string
    {
        return $this->idNumber;
    }

    public function setIdNumber(string $idNumber): self
    {
        $this->idNumber = $idNumber;

        return $this;
    }


    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setDriver($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getDriver() === $this) {
                $participant->setDriver(null);
            }
        }

        return $this;
    }

    public function getLastLicenseNumber()
    {
        return $this->licenseNumber[array_key_last($this->licenseNumber)];
    }

    public function getLicenseNumberNumber()
    {

        return $this->getLastLicenseNumber()['number'];
    }

    public function getLicenseNumberDate()
    {
        return $this->getLastLicenseNumber()['year'];
    }

    public function __toString()
    {
        return "{$this->firstName} {$this->lastName}";
    }


    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

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
            $license->setDriver($this);
        }

        return $this;
    }

    public function removeLicense(License $license): self
    {
        if ($this->licenses->removeElement($license)) {
            // set the owning side to null (unless already changed)
            if ($license->getDriver() === $this) {
                $license->setDriver(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"show_driver"})
     */
    public function getImageFilePath(): ?string
    {
        if ($this->imageFile) {
//            $pos = strpos($this->imageFile->getPath(), '/public/uploads');

            return "/uploads/images/drivers";
        } else {
            return null;
        }
//        return $this->imageFile ? $this->imageFile->getPath() : null;
    }

    public function getBloodType(): ?string
    {
        return $this->bloodType;
    }

    public function setBloodType(?string $bloodType): self
    {
        $this->bloodType = $bloodType;
        return $this;
    }
//    public function getStatus()
//    {
//        $arr = $this->licenses->filter(function ($el) { return $el->status === 'Active'; });
//        return count($arr) > 0 ? "Active" : "Expired";
//    }
}
