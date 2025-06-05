<?php

namespace LightSaml\Model\Protocol;

use DOMNode;
use LightSaml\Model\Context\CDataContext;
use LightSaml\Model\Context\SerializationContext;

abstract class AbstractExtension extends SamlMessage
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $namespace;

    private $attributes = [];

    /**
     * @var AbstractExtension[]|string[]
     */
    private $elements = [];

    public function __construct(string $name, ?string $namespace = null)
    {
        $this->name = $name;
        $this->namespace = $namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param bool|string $value
     *
     * @return $this
     */
    public function addAttribute(string $name, $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function getAttribute($name): ?string
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * @param self|string|CDataContext $element
     *
     * @return $this
     */
    public function addElement($element): self
    {
        $this->elements[] = $element;

        return $this;
    }

    public function serialize(DOMNode $parent, SerializationContext $context)
    {
        $result = $this->createElement($this->name, $this->namespace, $parent, $context);

        foreach ($this->attributes as $name => $value) {
            if (is_bool($value)) {
                $result->setAttribute($name, $value ? 'true' : 'false');
            } else {
                $result->setAttribute($name, $value);
            }
        }

        foreach ($this->elements as $item) {
            if ($item instanceof self) {
                $item->serialize($result, $context);
            } elseif ($item instanceof CDataContext) {
                $cdata = $result->ownerDocument->createCDATASection((string) $item);
                $result->appendChild($cdata);
            } elseif (is_string($item)) {
                $result->nodeValue = $item;
            }
        }
    }
}
