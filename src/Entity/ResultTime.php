<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ResultTime extends Result
{
    public function setValueNumberAutomatic()
    {
        $this->valueNumber = self::timerToMs($this->value);
    }
}