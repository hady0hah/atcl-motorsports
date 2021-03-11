<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      Result::POINT = ResultPoint::class, 
 *      Result::TIMER = ResultTime::class, 
 *      Result::STARTEND = ResultStartEnd::class, 
 *      Result::CHECKPOINT = ResultCheckpoint::class,
 *      Result::CUMULATIVE = ResultCumulative::class
 * })
 */
abstract class Result extends BaseDocument
{
    public const RESULT = 'point';
    public const POINT = 'point';
    public const TIMER = 'timer';
    public const STARTEND = 'startend';
    public const CHECKPOINT = 'checkpoint';
    public const CUMULATIVE = 'cumulative';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="results")
     * @ORM\OrderBy({"ordering" = "ASC"})
     * @Groups({"list_event_results"})
     */
    private $participant;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"list_event_results"})
     */
    private $dnf = false;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="results")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="results")
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity=Result::class, inversedBy="childrenResults", cascade={"persist"})
     */
    private $parentResult;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="parentResult", cascade={"persist", "remove"})
     * @Groups ({"show_upcoming_event", "running_event", "list_events"})
     */
    private $childrenResults;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"list_event_results"})
     */
    private $value;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $penalty;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"list_event_results"})
     */
    private $valueNumber;

    /**
     * @Groups({"list_event_results"})
     */
    private $formattedResultValue;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isIncludedInFinalResult = true;

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

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    public function getDnf(): ?bool
    {
        return $this->dnf;
    }

    public function setDnf(?bool $dnf): self
    {
        $this->dnf = $dnf;
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

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getParentResult(): ?self
    {
        return $this->parentResult;
    }

    public function setParentResult(?self $parentResult): self
    {
        $this->parentResult = $parentResult;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildrenResults(): Collection
    {
        return $this->childrenResults;
    }

    public function addChildrenResult(self $childrenResult): self
    {
        if (!$this->childrenResults->contains($childrenResult)) {
            $this->childrenResults[] = $childrenResult;
            $childrenResult->setParentResult($this);
        }

        return $this;
    }

    public function removeChildrenResult(self $childrenResult): self
    {
        if ($this->childrenResults->removeElement($childrenResult)) {
            // set the owning side to null (unless already changed)
            if ($childrenResult->getParentResult() === $this) {
                $childrenResult->setParentResult(null);
            }
        }

        return $this;
    }
    
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValueNumber(): ?float
    {
        return $this->valueNumber;
    }

    public function setValueNumber($valueNumber): self
    {
        $this->valueNumber = $valueNumber;
        return $this;
    }

    public function getFormattedResultValue()
    {
        return $this->convertFloatToTimeFormat($this->valueNumber);
    }

    public function setFormattedResultValue($formattedResultValue)
    {
        $this->formattedResultValue = $formattedResultValue;
        return $this;
    }

    public function setValueNumberDnf()
    {
        if (!$this instanceof ResultPoint)
            $this->valueNumber = 99999999999999999999;

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

    public function getPenalty(): ?float
    {
        return $this->penalty;
    }

    public function setPenalty(?float $penalty): self
    {
        $this->penalty = $penalty;

        return $this;
    }

    public function getType()
    {
        if ($this instanceof ResultPoint)
            return Result::POINT;
        elseif ($this instanceof ResultTime)
            return Result::TIMER;
        elseif ($this instanceof ResultStartEnd)
            return Result::STARTEND;
        elseif ($this instanceof ResultCheckpoint)
            return Result::CHECKPOINT;
    }

    public function calculateResult() {}

    public function __toString(): ?string
    {
        return $this->participant->getDriver();
    }

    public static function epochToTimer($seconds)
    {
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $seconds = $seconds % 60;
        $minutes = $minutes % 60;
        $format = '%u:%02u:%02u';
        return sprintf($format, $hours, $minutes, $seconds);
    }

    public static function timerToEpoch($epoch)
    {
        $valueArr = explode(":", $epoch);
        return ((int)$valueArr[0] * 3600) + ((int)$valueArr[1] * 60) + (int)$valueArr[2];
    }


    public static function timerToMs($timer)
    {
        $valueArr = explode(":", $timer);
        return ((int)$valueArr[0] * 3600000) + ((int)$valueArr[1] * 60000) + ((int)$valueArr[2] * 1000) + (int)$valueArr[3];
    }

    public static function msToTimer($milliseconds)
    {
        $seconds = floor($milliseconds / 1000);
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $milliseconds = $milliseconds % 1000;
        $seconds = $seconds % 60;
        $minutes = $minutes % 60;

        $format = '%u:%02u:%02u:%03u';
        return sprintf($format, $hours, $minutes, $seconds, $milliseconds);
    }

    private function convertFloatToTimeFormat($resultValue)
    {
        $hours = floor($resultValue / 3600);
        $remainingSeconds = $resultValue % 3600;
        $minutes = floor($remainingSeconds / 60);
        $seconds = floor($remainingSeconds % 60);
        $microseconds = ($resultValue - floor($resultValue)) * 10;

        $formattedTime = sprintf("%02d:%02d:%02d.%d", $hours, $minutes, $seconds, number_format($microseconds, 1));

        return $formattedTime;
    }
}
