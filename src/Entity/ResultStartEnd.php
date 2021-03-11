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
class ResultStartEnd extends Result
{
    private $startDate;
    private $endDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expectedStartDate;

    public function getStartDate()
    {
        if ($this->getValue()) {
            $values = explode(",", $this->getValue());
            return $values[0];
        } else
            return $this->getValue();
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        if ($this->getValue()) {
            $values = explode(",", $this->getValue());
            $values[0] = $startDate;
        } else {
            $values = array($startDate);
        }
        $this->setValue(implode(',', $values));
    }

    public function getEndDate()
    {
        if ($this->getValue()) {
            $values = explode(",", $this->getValue());
            return $values[1];
        } else
            return $this->getValue();
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        if ($this->getValue()) {
            $values = explode(",", $this->getValue());
            $values[1] = $endDate;
        } else {
            $values = array($endDate);
        }

        $this->setValue(implode(',', $values));
    }

    public function setValueNumberAutomatic()
    {
        $this->setValueNumber(strtotime($this->endDate) - strtotime($this->startDate));
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

    public function calculateResult()
    {
        if($this->startDate) {
            // $this->setValue($this->startDate->format('Y-m-d H:i:s'));
            $start = new DateTime($this->getStartDate());
            $end = new DateTime($this->getEndDate());
            $diff = $end->diff($start);
            $result = $diff->h * 3600 + $diff->i * 60 + $diff->s + $diff->f;
            $this->calculatePenalty();
            $this->setValueNumber($result);
            if($this->getPublished() && $this->getParentResult()) {
                 $this->getParentResult()->calculateResult();
                 $this->getParentResult()->setValueNumber($this->getParentResult()->getValueNumber() + $this->getParentResult()->getPenalty());
                }
        }
    }

    public function calculatePenalty() {
        if($this->getExpectedStartDate() && $this->getStartDate() && $this->getPenalty() === null) {
            $start = new \DateTime($this->getStartDate());
            $diff = $this->getExpectedStartDate()->diff($start);
            $diff = ($diff->invert ? -1 : 1)*($diff->h*60 + $diff->i);
            $penalty = $diff < 0 ? $diff * -60 : $diff * 10;
            $this->setPenalty($penalty);
        }
        
    }
}
