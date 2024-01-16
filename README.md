# lightSAML

This is a fork of [litesaml/lightsaml](https://github.com/litesaml/lightsaml) and adds support for:

- RSASSA-PSS based XML signature algorithms (by using our fork of [xmlseclibs](https://github.com/brain-SCC/xmlseclibs))
- SamlExtenions in AuthnRequest

We maintain a master branch, with the latest changes from [litesaml/lightsaml](https://github.com/litesaml/lightsaml) and our additions. We also maintain a legacy branch with our additions, which provides PHP 7.3 support.

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
        "brainscc/lightsaml": "~4.2.0"
    }
}
```
