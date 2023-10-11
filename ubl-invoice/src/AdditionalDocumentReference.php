<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class AdditionalDocumentReference implements XmlSerializable
{
    private $id;
    private $documentType;
    private $documentTypeCode;
    private $documentDescription;
    private $attachment;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return AdditionalDocumentReference
     */
    public function setId(string $id): AdditionalDocumentReference
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    /**
     * @param string $documentType
     * @return AdditionalDocumentReference
     */
    public function setDocumentType(string $documentType): AdditionalDocumentReference
    {
        $this->documentType = $documentType;
        return $this;
    }

    /**
     * @return int
     */
    public function getDocumentTypeCode(): ?int
    {
        return $this->documentTypeCode;
    }

    /**
     * @param int $documentTypeCode
     * @return AdditionalDocumentReference
     */
    public function setDocumentTypeCode(int $documentTypeCode): AdditionalDocumentReference
    {
        $this->documentTypeCode = $documentTypeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentDescription(): ?string
    {
        return $this->documentDescription;
    }

    /**
     * @param string $documentDescription
     * @return AdditionalDocumentReference
     */
    public function setDocumentDescription(string $documentDescription): AdditionalDocumentReference
    {
        $this->documentDescription = $documentDescription;
        return $this;
    }

    /**
     * @return Attachment
     */
    public function getAttachment(): ?Attachment
    {
        return $this->attachment;
    }

    /**
     * @param Attachment $attachment
     * @return AdditionalDocumentReference
     */
    public function setAttachment(Attachment $attachment): AdditionalDocumentReference
    {
        $this->attachment = $attachment;
        return $this;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        $writer->write([ Schema::CBC . 'ID' => $this->id ]);

        if ($this->documentTypeCode !== null) {
            $writer->write([
                Schema::CBC . 'DocumentTypeCode' => $this->documentTypeCode
            ]);
        } else if ($this->documentType !== null) {
            $writer->write([
                Schema::CBC . 'DocumentType' => $this->documentType
            ]);
        }

        if ($this->documentDescription !== null) {
            $writer->write([
                Schema::CBC . 'DocumentDescription' => $this->documentDescription
            ]);
        }

        if ($this->attachment !== null) {
            $writer->write([
              Schema::CAC . 'Attachment' => $this->attachment
            ]);
        }
    }
}
