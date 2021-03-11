<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Repository\DocumentRepository;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 * Class Document
 * @package App\Entity
 * @Vich\Uploadable
 */
class Document extends BaseDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups ({"list_document_categories"})
     */
    private $label;

    /**
     * @ORM\Column(type="string")
     * @Groups ({"show_upcoming_event", "show_event", "list_events", "list_documents", "list_document_categories"})
     */
    private $document;

    /**
     * @Vich\UploadableField(mapping="document_files", fileNameProperty="document")
     */
    private $documentFile;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="documents")
     * @Groups ({"list_documents"})
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=DocumentCategory::class, inversedBy="documents")
     * @Groups ({"list_documents"})
     */
    private $documentCategory;


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

    public function getDocument() : ?string
    {
        return $this->document;
    }


    public function setDocument(?string $document): self
    {
        $this->document = $document;
        return $this;
    }

    public function getDocumentFile() : ?File
    {
        return $this->documentFile;
    }

    public function setDocumentFile(?File $documentFile) : void
    {
        $this->documentFile = $documentFile;
        if($documentFile)
        {
            $this->modifiedAt = new \DateTime('now');
        }
    }

    /**
     * @return string|null
     * @Groups ({"show_upcoming_event", "show_event", "list_events", "list_documents", "list_document_categories"})
     */
    public function getDocumentPath() : ?string
    {
        return "/uploads/documents/events";
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

    
    public function __toString(): string
    {
        return (string) $this->document;
    }

    public function getDocumentCategory(): ?DocumentCategory
    {
        return $this->documentCategory;
    }

    public function setDocumentCategory(?DocumentCategory $documentCategory): self
    {
        $this->documentCategory = $documentCategory;

        return $this;
    }
}