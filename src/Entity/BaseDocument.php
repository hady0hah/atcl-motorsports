<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class BaseDocument
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"list_documents"})
     */
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $modifiedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime('NOW');
    }

    /**
     * @ORM\PreUpdate
     */
    public function setModifiedAt()
    {
        $this->modifiedAt = new \DateTime('NOW');
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set published
     *
     * @param boolean $published
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }


}