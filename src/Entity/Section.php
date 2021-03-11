<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Section extends BaseDocument
{
    public const LEG = 'leg';
    public const SECTION = 'section';
    public const TC = 'tc';
    public const SS = 'ss';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"show_upcoming_event", "running_event", "list_events"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"running_event", "list_events"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"show_upcoming_event", "running_event", "list_events"})
     */
    private $label;

    /**
     * @ORM\Column(type="datetime")
     * @Groups ({"running_event", "list_events"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups ({"running_event", "list_events"})
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"running_event", "list_events"})
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"running_event", "list_events"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="sections")
     */
    private $event;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isStarted = false;

    private $isRunning = false;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="childrenSections")
     */
    private $parentSection;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isParent=false;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="parentSection", cascade={"persist", "remove"})
     * @ORM\OrderBy({"ordering" = "ASC", "startDate" = "ASC"})
     * @Groups ({"show_upcoming_event", "running_event", "list_events"})
     */
    private $childrenSections;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $resultType;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="section", cascade={"persist", "remove"})
     */
    private $results;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     * @Groups ({"running_event"})
     */
    private $banner;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordering;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isIncludedInFinalResult = true;

    /**
     * @Vich\UploadableField(mapping="section_banner", fileNameProperty="banner")
     * @var File
     */
    private $bannerFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"show_upcoming_event", "running_event", "list_events"})
     */
    private $sectionType;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expectedStartDate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $targetTime;

    public function __construct()
    {
        $this->childrenSections = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
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

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getIsStarted(): ?bool
    {
        return $this->isStarted;
    }

    public function setIsStarted(?bool $isStarted): self
    {
        $this->isStarted = $isStarted;
        return $this;
    }

    public function getIsRunning(): ?bool
    {
        return $this->isRunning;
    }

    public function setIsRunning(?bool $isRunning): ?self
    {
        $this->isRunning = $isRunning;
        return $this;
    }

    public function getParentSection(): ?self
    {
        return $this->parentSection;
    }

    public function setParentSection(?self $parentSection): self
    {
        $this->parentSection = $parentSection;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildrenSections(): Collection
    {
        return $this->childrenSections;
    }

    public function addChildrenSection(self $childrenSection): self
    {
        if (!$this->childrenSections->contains($childrenSection)) {
            $this->childrenSections[] = $childrenSection;
            $childrenSection->setParentSection($this);
        }

        return $this;
    }

    public function removeChildrenSection(self $childrenSection): self
    {
        if ($this->childrenSections->removeElement($childrenSection)) {
            // set the owning side to null (unless already changed)
            if ($childrenSection->getParentSection() === $this) {
                $childrenSection->setParentSection(null);
            }
        }

        return $this;
    }

    public function getResultType(): ?string
    {
        return $this->resultType;
    }

    
    public function setResultType(): self
    {
        switch ($this->getSectionType()) {
            case Section::LEG:
            case Section::SECTION:
                $this->resultType = Result::CUMULATIVE;
                break;
            case Section::TC:
                $this->resultType = Result::CHECKPOINT;
                $this->setExpectedStartDate($this->getStartDate());
                break;
            case Section::SS:
                $this->resultType = Result::STARTEND;
                $this->setExpectedStartDate($this->getStartDate());
                break;
            default:
                $this->resultType = $this->getEvent()->getResultType();
        }

        return $this;
    }
    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        if ($this->getParentSection()) {
            $this->setEvent($this->getParentSection()->getEvent());
        }
        $this->setResultType();
        
    }
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->setResultType();
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
            $result->setSection($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getSection() === $this) {
                $result->setSection(null);
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
     * @Groups({"running_event"})
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

    public function getOrdering(): ?int
    {
        return $this->ordering;
    }

    public function setOrdering(?int $ordering): self
    {
        $this->ordering = $ordering;
        return $this;
    }

    public function getIsIncludedInFinalResult(): ?bool
    {
        return $this->isIncludedInFinalResult;
    }

    public function setIsIncludedInFinalResult(?bool $isIncludedInFinalResult): self
    {
        $this->isIncludedInFinalResult = $isIncludedInFinalResult;
        return $this;
    }

    /**
     * @param $section
     * @return Event
     * returns the parent event of the section
     */
    public static function getEventSection($section)
    {
        if ($section instanceof Event) {
            return $section;
        } else if (!$section->getParentSection()) {
            return $section->getEvent();
        } else {
            return Section::getEventSection($section->getParentSection());
        }
    }

    public static function getParentNode($section)
    {
        if (!$section->getParentSection())
            return $section->getEvent();
        else
            return $section->getParentSection();
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


    public function __toString()
    {
        return (string)$this->label;
    }

    public function getSectionType(): ?string
    {
        return $this->sectionType;
    }

    public function setSectionType(?string $sectionType): self
    {
        $this->sectionType = $sectionType;

        return $this;
    }

    public function getExpectedStartDate(): ?\DateTimeInterface
    {
        return $this->expectedStartDate;
    }

    public function setExpectedStartDate(?\DateTimeInterface $expectedStartDate): self
    {
        $this->expectedStartDate = $expectedStartDate;

        return $this;
    }

    /**
     * Get the value of isParent
     */ 
    public function isParent()
    {
        return $this->isParent;
    }

    /**
     * Get the value of isParent
     */ 
    public function getIsParent()
    {
        return $this->isParent;
    }

    /**
     * Set the value of isParent
     *
     * @return  self
     */ 
    public function setIsParent($isParent)
    {
        $this->isParent = $isParent;

        return $this;
    }

    /**
     * Get the value of targetTime
     */ 
    public function getTargetTime()
    {
        return $this->targetTime;
    }

    /**
     * Set the value of targetTime
     *
     * @return  self
     */ 
    public function setTargetTime($targetTime)
    {
        $this->targetTime = $targetTime;

        return $this;
    }
}
