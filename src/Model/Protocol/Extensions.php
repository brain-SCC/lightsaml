<?php

namespace LightSaml\Model\Protocol;

use LightSaml\Model\AbstractSamlModel;
use LightSaml\Model\Context\DeserializationContext;
use LightSaml\Model\Context\SerializationContext;
use LightSaml\SamlConstants;

class Extensions extends AbstractSamlModel
{
    /**
     * @var AbstractExtension[]|array
     */
    protected $items = [];

    /**
     * @return Extensions
     */
    public function addItem(AbstractExtension $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return AbstractExtension[]|array
     */
    public function getAllItems()
    {
        return $this->items;
    }

    /**
     * @return void
     */
    public function serialize(\DOMNode $parent, SerializationContext $context)
    {
        $result = $this->createElement('Extensions', SamlConstants::NS_PROTOCOL, $parent, $context);

        foreach ($this->items as $item) {
            $item->serialize($result, $context);
        }
    }

    public function deserialize(\DOMNode $node, DeserializationContext $context)
    {
    }
}
