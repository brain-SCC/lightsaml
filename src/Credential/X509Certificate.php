<?php

namespace LightSaml\Credential;

use InvalidArgumentException;
use LightSaml\Error\LightSamlException;
use LightSaml\Error\LightSamlSecurityException;
use LightSaml\SamlConstants;
use RobRichards\XMLSecLibs\XMLSecurityKey;

class X509Certificate
{
    private static $typeMap = [
        'RSA-SHA1' => XMLSecurityKey::RSA_SHA1,
        'RSA-SHA256' => XMLSecurityKey::RSA_SHA256,
        'RSA-SHA384' => XMLSecurityKey::RSA_SHA384,
        'RSA-SHA512' => XMLSecurityKey::RSA_SHA512,
    ];

    /** @var string */
    protected $data;

    /** @var array|null */
    protected $info;

    /** @var string */
    private $signatureAlgorithm;

    /**
     * @param string $filename
     *
     * @return X509Certificate
     */
    public static function fromFile($filename)
    {
        $result = new self();
        $result->loadFromFile($filename);

        return $result;
    }

    /**
     * @param string $data
     *
     * @return X509Certificate
     */
    public function setData($data)
    {
        $this->data = preg_replace('/\s+/', '', $data);
        $this->parse();

        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     *
     * @return X509Certificate
     *
     * @throws InvalidArgumentException
     */
    public function loadPem($data)
    {
        $pattern = '/^-----BEGIN CERTIFICATE-----([^-]*)^-----END CERTIFICATE-----/m';
        if (false == preg_match($pattern, $data, $matches)) {
            throw new InvalidArgumentException('Invalid PEM encoded certificate');
        }
        $this->data = preg_replace('/\s+/', '', $matches[1]);
        $this->parse();

        return $this;
    }

    /**
     * @param string $filename
     *
     * @return X509Certificate
     *
     * @throws InvalidArgumentException
     */
    public function loadFromFile($filename)
    {
        if (!is_file($filename)) {
            throw new InvalidArgumentException(sprintf("File not found '%s'", $filename));
        }
        $content = file_get_contents($filename);
        $this->loadPem($content);

        return $this;
    }

    /**
     * @return string
     */
    public function toPem()
    {
        return "-----BEGIN CERTIFICATE-----\n" . chunk_split($this->getData(), 64, "\n") . "-----END CERTIFICATE-----\n";
    }

    public function parse()
    {
        if (false == $this->data) {
            throw new LightSamlException('Certificate data not set');
        }

        $res = openssl_x509_read($this->toPem());
        $this->info = openssl_x509_parse($res);
        $this->signatureAlgorithm = null;
        $signatureType = $this->info['signatureTypeSN'] ?? '';
        if ($signatureType && isset(self::$typeMap[$signatureType])) {
            $this->signatureAlgorithm = self::$typeMap[$signatureType];
        } else {
            openssl_x509_export($res, $out, false);
            if (preg_match('/^\s+Signature Algorithm:\s*(.*)\s*$/m', $out, $match)) {
                switch (trim($match[1])) {
                    case 'sha1WithRSAEncryption':
                    case 'sha1WithRSA':
                        $this->signatureAlgorithm = XMLSecurityKey::RSA_SHA1;
                        break;
                    case 'sha256WithRSAEncryption':
                    case 'sha256WithRSA':
                        $this->signatureAlgorithm = XMLSecurityKey::RSA_SHA256;
                        break;
                    case 'sha384WithRSAEncryption':
                    case 'sha384WithRSA':
                        $this->signatureAlgorithm = XMLSecurityKey::RSA_SHA384;
                        break;
                    case 'sha512WithRSAEncryption':
                    case 'sha512WithRSA':
                        $this->signatureAlgorithm = XMLSecurityKey::RSA_SHA512;
                        break;
                    case 'md5WithRSAEncryption':
                    case 'md5WithRSA':
                        $this->signatureAlgorithm = SamlConstants::XMLDSIG_DIGEST_MD5;
                        break;
                    case 'rsassaPss':
                        if (preg_match('/^\s+Mask Algorithm:\s*(.*)\s*$/m', $out, $match)) {
                            switch (trim($match[1])) {
                                case 'mgf1 with sha1':
                                    $this->signatureAlgorithm = XMLSecurityKey::SHA1_RSA_MGF1;
                                    break 2;
                                case 'mgf1 with sha224':
                                    $this->signatureAlgorithm = XMLSecurityKey::SHA224_RSA_MGF1;
                                    break 2;
                                case 'mgf1 with sha256':
                                    $this->signatureAlgorithm = XMLSecurityKey::SHA256_RSA_MGF1;
                                    break 2;
                                case 'mgf1 with sha384':
                                    $this->signatureAlgorithm = XMLSecurityKey::SHA384_RSA_MGF1;
                                    break 2;
                                case 'mgf1 with sha512':
                                    $this->signatureAlgorithm = XMLSecurityKey::SHA512_RSA_MGF1;
                                    break 2;
                                default:
                                    throw new LightSamlSecurityException('Unrecognized hash algorithm: ' . $match[1]);
                            }
                        }
                        // no break
                    default:
                        throw new LightSamlSecurityException('Unrecognized signature algorithm: ' . $match[1]);
                }
            }
        }

        if (!$this->signatureAlgorithm) {
            throw new LightSamlSecurityException('Unrecognized signature algorithm');
        }
    }

    /**
     * @return string
     *
     * @throws LightSamlException
     */
    public function getName()
    {
        if (false == $this->info) {
            throw new LightSamlException('Certificate data not set');
        }

        return $this->info['name'];
    }

    /**
     * @return string
     *
     * @throws LightSamlException
     */
    public function getSubject()
    {
        if (false == $this->info) {
            throw new LightSamlException('Certificate data not set');
        }

        return $this->info['subject'];
    }

    /**
     * @return array
     *
     * @throws LightSamlException
     */
    public function getIssuer()
    {
        if (false == $this->info) {
            throw new LightSamlException('Certificate data not set');
        }

        return $this->info['issuer'];
    }

    /**
     * @return int
     *
     * @throws LightSamlException
     */
    public function getValidFromTimestamp()
    {
        if (false == $this->info) {
            throw new LightSamlException('Certificate data not set');
        }

        return $this->info['validFrom_time_t'];
    }

    /**
     * @return int
     *
     * @throws LightSamlException
     */
    public function getValidToTimestamp()
    {
        if (false == $this->info) {
            throw new LightSamlException('Certificate data not set');
        }

        return $this->info['validTo_time_t'];
    }

    /**
     * @return array
     *
     * @throws LightSamlException
     */
    public function getInfo()
    {
        if (false == $this->info) {
            throw new LightSamlException('Certificate data not set');
        }

        return $this->info;
    }

    /**
     * @throws LightSamlException
     *
     * @return string
     */
    public function getFingerprint()
    {
        if (false == $this->data) {
            throw new LightSamlException('Certificate data not set');
        }

        return XMLSecurityKey::getRawThumbprint($this->toPem());
    }

    public function getSignatureAlgorithm()
    {
        if (false == $this->data) {
            throw new LightSamlException('Certificate data not set');
        }

        return $this->signatureAlgorithm;
    }
}
