<?php


namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ResultCheckpoint extends Result
{
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expectedStartDate;

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate)
    {
        $this->startDate = $startDate;
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
     * @ORM\PostLoad()
     */
    public function populateStartDate() {
        if($this->getValue()) $this->startDate = new DateTime($this->getValue());
    }
    
    public function calculateResult()
    {
        if($this->startDate) {
            $this->setValue($this->startDate->format('Y-m-d H:i:s'));
            $this->calculatePenalty();
            $this->setValueNumber($this->getPenalty());
            if($this->getPublished() && $this->getParentResult()) {
                    $this->getParentResult()->calculateResult();
                }
        }
    }

    public function calculatePenalty() {
        if($this->getExpectedStartDate() && $this->startDate && $this->getPenalty() === null) {
            $diff = $this->getExpectedStartDate()->diff($this->startDate);
            $diff = ($diff->invert ? -1 : 1)*($diff->h*60 + $diff->i);
            $penalty = $diff < 0 ? $diff * -60 : $diff * 10;
            $this->setPenalty($penalty);
        }
        
    }
}
