# lightSAML

This is a fork of [litesaml/lightsaml](https://github.com/litesaml/lightsaml) and adds support for:

- RSASSA-PSS based XML signature algorithms (by using our fork of [xmlseclibs](https://github.com/brain-SCC/xmlseclibs))
- `SamlExtenions` in `AuthnRequest`
- `RequestedAuthnContext` in `AuthnRequest`

These changes are necessary for using this library with the German services [bundID](https://id.bund.de) and [MeinUnternehmenskonto](https://mein-unternehmenskonto.de). We maintain this repo, with the latest changes from [litesaml/lightsaml](https://github.com/litesaml/lightsaml) and our additions.

## Installation

This package requires `php:^8.1`

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
        "brainscc/lightsaml": "~4.5.0"
    }
}
```
