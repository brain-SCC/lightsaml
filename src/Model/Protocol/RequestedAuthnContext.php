<?php

namespace LightSaml\Model\Protocol;

use DOMNode;
use LightSaml\Model\AbstractSamlModel;
use LightSaml\Model\Assertion\AuthnContext;
use LightSaml\Model\Context\DeserializationContext;
use LightSaml\Model\Context\SerializationContext;
use LightSaml\SamlConstants;

class RequestedAuthnContext extends AbstractSamlModel
{
    /**
     * @var string|null
     */
    protected $authnContextClassRef;

    /**
     * @var string|null
     */
    protected $authnContextDeclRef;

    /**
     * @var string|null
     */
    protected $comparison;

    /**
     * @param string|null $authnContextClassRef
     *
     * @return RequestedAuthnContext
     */
    public function setAuthnContextClassRef($authnContextClassRef)
    {
        $this->authnContextClassRef = (string) $authnContextClassRef;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthnContextClassRef()
    {
        return $this->authnContextClassRef;
    }

    /**
     * @param string|null $authnContextDeclRef
     *
     * @return RequestedAuthnContext
     */
    public function setAuthnContextDeclRef($authnContextDeclRef)
    {
        $this->authnContextDeclRef = (string) $authnContextDeclRef;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthnContextDeclRef()
    {
        return $this->authnContextDeclRef;
    }

    /**
     * @param string|null $comparison
     *
     * @return RequestedAuthnContext
     */
    public function setComparison($comparison)
    {
        $this->comparison = $comparison;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getComparison()
    {
        return $this->comparison;
    }

    /**
     * @return void
     */
    public function serialize(DOMNode $parent, SerializationContext $context)
    {
        $result = $this->createElement('RequestedAuthnContext', SamlConstants::NS_PROTOCOL, $parent, $context);

        $this->attributesToXml(['Comparison'], $result);

        $this->singleElementsToXml(
            ['AuthnContextClassRef', 'AuthnContextDeclRef'],
            $result,
            $context,
            SamlConstants::NS_ASSERTION
        );
    }

    public function deserialize(DOMNode $node, DeserializationContext $context)
    {
        $this->checkXmlNodeName($node, 'RequestedAuthnContext', SamlConstants::NS_PROTOCOL);

        $this->attributesFromXml($node, ['Comparison']);

        $this->singleElementsFromXml($node, $context, [
            'AuthnContextClassRef' => ['saml', null],
            'AuthnContextDeclRef' => ['saml', null],
        ]);
    }
}
