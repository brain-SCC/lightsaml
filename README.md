# lightSAML

This is a fork of [litesaml/lightsaml](https://github.com/litesaml/lightsaml) and adds support for RSASSA-PSS based XML signature algorithms by using our fork of [xmlseclibs](https://github.com/brain-SCC/xmlseclibs).

Light Saml Implements basic SAML 2.0 data model classes, serialization/deserialization to/from xml with XML security and
certificates support, and message encapsulations to bindings. Covered with unit tests.

## Installation

Install with composer.

```json
{
    "repositories": [
        {
            "url": "https://github.com/brain-SCC/lightsaml.git",
            "type": "git"
        }
    ],
    "require": {
        "brainscc/lightsaml": "~3.1.0"
    }
}
```
