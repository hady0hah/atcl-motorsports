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
class ResultCumulative extends Result
{
    public function calculateResult()
    {
        $cumulativePenalty=0;
        $cumulativeValueNumber=0;
        foreach($this->getChildrenResults() as $result) {
            $cumulativePenalty += $result->getPenalty();
            if($result->getSection()->getSectionType() !== 'tc') {
                $cumulativeValueNumber += $result->getValueNumber();
            }
        }
        $this->setPenalty($cumulativePenalty);
        $this->setValueNumber($cumulativeValueNumber);
        if($this->getPublished() && $this->getParentResult()) {
            $this->getParentResult()->calculateResult();
            $this->getParentResult()->setValueNumber($this->getParentResult()->getValueNumber() + $this->getParentResult()->getPenalty());
        }
    }
}
