<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant extends BaseDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list_event_results"})
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_event_results"})
     */
    private $car;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="participant")
     */
    private $results;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class, inversedBy="participants")
     * @Groups({"list_event_results"})
     */
    private $driver;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class, inversedBy="participants")
     * @Groups({"list_event_results"})
     */
    private $coDriver;

    /**
     * @ORM\Column (type="boolean")
     */
    private $dnf = false;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="participants")
     */
    private $event;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $gap;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordering;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCar(): ?string
    {
        return $this->car;
    }

    public function setCar(string $car): self
    {
        $this->car = $car;

        return $this;
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
            $result->setParticipant($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getParticipant() === $this) {
                $result->setParticipant(null);
            }
        }

        return $this;
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

    public function getCoDriver(): ?Driver
    {
        return $this->coDriver;
    }

    public function setCoDriver(?Driver $coDriver): self
    {
        $this->coDriver = $coDriver;

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

    public function __toString()
    {
        return $this->coDriver ? "{$this->driver} & {$this->coDriver}" : (string) $this->driver;
    }

    public function getGap(): ?float
    {
        return $this->gap;
    }

    public function setGap(?float $gap): self
    {
        $this->gap = $gap;

        return $this;
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
}
