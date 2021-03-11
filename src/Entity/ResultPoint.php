<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */
class ResultPoint extends Result
{
    public function setValueNumberAutomatic()
    {
        $this->valueNumber = (float) $this->value;
    }


}