<?php

namespace NumNum\UBL;

use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class TaxScheme implements XmlSerializable
{
    private $id;
    private $name;
    private $taxTypeCode;
    private $currencyCode;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return TaxScheme
     */
    public function setId(string $id): TaxScheme
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TaxScheme
     */
    public function setName(?string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaxTypeCode(): ?string
    {
        return $this->taxTypeCode;
    }

    /**
     * @param string $taxTypeCode
     * @return TaxScheme
     */
    public function setTaxTypeCode(?string $taxTypeCode)
    {
        $this->taxTypeCode = $taxTypeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     * @return TaxScheme
     */
    public function setCurrencyCode(?string $currencyCode)
    {
        $this->currencyCode = $currencyCode;
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
        $writer->write([
            Schema::CBC . 'ID' => $this->id
        ]);
        if ($this->name !== null) {
            $writer->write([
                Schema::CBC . 'Name' => $this->name
            ]);
        }
        if ($this->taxTypeCode !== null) {
            $writer->write([
                Schema::CBC . 'TaxTypeCode' => $this->taxTypeCode
            ]);
        }
        if ($this->currencyCode !== null) {
            $writer->write([
                Schema::CBC . 'CurrencyCode' => $this->currencyCode
            ]);
        }
    }
}
