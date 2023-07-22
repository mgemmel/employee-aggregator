<?php
declare(strict_types=1);

namespace App\Services\Xml;

use App\Exceptions\XmlStorageException;
use App\Models\users\AttributeModel;
use Exception;
use SimpleXMLElement;

abstract class XmlStorageService
{
    private string $filePath;
    private SimpleXMLElement $simpleXMLElement;

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }


    /**
     * @throws XmlStorageException
     */
    private function loadContent(): string
    {
        try {
            $content = file_get_contents($this->filePath);
            if (is_string($content)) {
                return $content;
            }
        } catch (Exception $exception) {
            throw new XmlStorageException('Unable to load content', previous: $exception);
        }
        throw new XmlStorageException('Unable to read content');
    }


    /**
     * @throws XmlStorageException
     * @throws Exception
     */
    private function loadData(): void
    {
        try {
            $this->simpleXMLElement = new SimpleXMLElement($this->loadContent());
        } catch (Exception $exception) {
            throw new XmlStorageException('Unable to parse content', previous: $exception);
        }

    }

    /**
     * @throws XmlStorageException
     */
    protected function getData(): SimpleXMLElement
    {
        if (!isset($this->simpleXMLElement)) {
            $this->loadData();
        }

        return $this->simpleXMLElement;
    }

    /**
     * @return void
     * @throws XmlStorageException
     */
    public function saveData(): void
    {
        if (isset($this->simpleXMLElement) && $this->simpleXMLElement->saveXML($this->filePath)) {
            return;
        }
        throw new XmlStorageException('Unable to save data');
    }

    /**
     * @param string $name
     * @param AttributeModel[] $attributes
     * @return SimpleXMLElement
     * @throws XmlStorageException
     */
    public function createXmlElement(string $name, array $attributes): SimpleXMLElement
    {
        $child =  $this->getData()->addChild($name);
        foreach ($attributes as $attribute){
            $child->addAttribute($attribute->getName(), $attribute->getValue());
        }

        return $child;
    }
}
