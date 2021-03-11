<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @Vich\Uploadable
 */
class Event extends BaseDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_top_banner_events", "show_upcoming_event", "show_event", "running_event", "list_events"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_top_banner_events" , "show_upcoming_event", "show_event", "running_event", "list_events"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_top_banner_events" , "show_upcoming_event", "show_event", "running_event", "list_events", "list_documents"})
     */
    private $label;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"list_top_banner_events" , "show_upcoming_event", "show_event", "running_event", "list_events"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"list_top_banner_events", "show_upcoming_event", "show_event", "running_event", "list_events"})
     */
    private $endDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isStarted = false;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_top_banner_events", "show_upcoming_event", "show_event", "running_event", "list_events"})
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_top_banner_events", "show_upcoming_event", "show_event", "running_event", "list_events"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Championship::class, inversedBy="events")
     * @Groups({"show_upcoming_event", "list_top_banner_events", "list_events"})
     */
    private $championship;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="event", cascade={"persist", "remove"})
     * @ORM\OrderBy({"parentSection" = "ASC", "ordering" = "ASC", "startDate" = "ASC"})
     * @Groups({"show_upcoming_event", "running_event", "list_events"})
     */
    private $sections;

    /**
     * @ORM\Column(type="string")
     * @Groups ({"running_event"})
     */
    private $resultType;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="event", cascade={"persist", "remove"})
     * @Groups({"show_event"})
     */
    private $results;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     * @Groups({"list_top_banner_events" , "show_upcoming_event", "show_event", "running_event", "list_events", "list_documents"})
     */
    private $banner;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTopBanner = false;
    /**
     * @Vich\UploadableField(mapping="event_banner", fileNameProperty="banner")
     * @var File
     */
    private $bannerFile;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @var string
     * @Groups({"list_top_banner_events" , "show_upcoming_event", "show_event", "running_event", "list_events", "list_documents"})
     */
    private $image;
    /**
     * @Vich\UploadableField(mapping="event_banner", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="event", cascade={"persist", "remove"})
     * @Groups ({"show_upcoming_event", "show_event", "list_events"})
     */
    private $documents;

    /**
     * @ORM\OneToMany (targetEntity=Participant::class, mappedBy="event", cascade={"persist", "remove"})
     */
    private $participants;


    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->participants = new ArrayCollection();
//        $this->eventParticipants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getIsStarted() : ?bool
    {
        return $this->isStarted;
    }

    public function setIsStarted(?bool $isStarted) : self
    {
        $this->isStarted = $isStarted;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

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

    public function getChampionship(): ?Championship
    {
        return $this->championship;
    }

    public function setChampionship(?Championship $championship): self
    {
        $this->championship = $championship;

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): ?Collection
    {
        return $this->sections;
    }

    public function addSection(?Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setEvent($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getEvent() === $this) {
                $section->setEvent(null);
            }
        }

        return $this;
    }


    /**
     * @Groups ({"show_upcoming_event"})
     * @return string|null
     */
    public function getResultType(): ?string
    {
        return $this->resultType;
    }

    /**
     * @param string $resultType
     */
    public function setResultType(string $resultType)
    {
        $this->resultType = $resultType;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setEvent($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getEvent() === $this) {
                $result->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @param File|null $banner
     */
    public function setBannerFile(File $banner = null)
    {
        $this->bannerFile = $banner;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($banner) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->modifiedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getBannerFile()
    {
        return $this->bannerFile;
    }

    /**
     * @param $banner
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;
    }

    /**
     * @return string
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * @return string|null
     * @Groups({"list_top_banner_events", "show_upcoming_event", "show_event", "running_event", "list_events", "list_documents"})
     */
    public function getBannerFilePath(): ?string
    {
        if ($this->bannerFile) {
//            $pos = strpos($this->imageFile->getPath(), '/public/uploads');

            return "/uploads/banners/events";
        } else {
            return null;
        }
//        return $this->imageFile ? $this->imageFile->getPath() : null;
    }

    /**
     * @param File|null $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->modifiedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }


    public function getIsTopBanner(): ?bool
    {
        return $this->isTopBanner;
    }

    public function setIsTopBanner(?bool $isTopBanner): self
    {
        $this->isTopBanner = $isTopBanner;
        return $this;
    }

    public function __toString()
    {
        return (string)$this->label;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): ?Collection
    {
        return $this->documents;
    }

    public function addDocument(?Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setEvent($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getEvent() === $this) {
                $document->setEvent(null);
            }
        }

        return $this;
    }

    public function isOver(): bool
    {
        return $this->endDate <= new \DateTime('now');
    }

    public function isRunning(): bool
    {
        $now = new \DateTime('now');
        return $this->startDate <= $now && $this->endDate >= $now;
    }

    public function getParticipants() : ?Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)){
            $this->participants[] = $participant;
            $participant->setEvent($this);
        }
        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)){
            if ($participant->getEvent() == $this){
                $participant->setEvent(null);
            }
        }
        return $this;
    }

    /**
     * @Groups ({"show_upcoming_event", "list_events"})
     * @return bool
     */
    public function getHasResults()
    {
        return count($this->results) > 0;
    }
}
