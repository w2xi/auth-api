<?php

namespace app\api\service;

use app\api\exception\JwtTokenExpiredException;
use app\api\exception\JwtTokenMissingException;
use app\api\exception\NeedPermissionException;
use DateTimeImmutable;
use DateTimeZone;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\Clock\SystemClock;

class JwtAuth
{
    protected static $instance;
    // a base64Encoded string
    private static $secret = 'mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=';
    protected static $config;
    protected $token;
    protected $decodeToken;
    protected $uid;
    protected $iss             = 'api.w2xi.com';
    protected $aud             = 'test.com';
    protected static $timezone = 'Asia/Shanghai';

    private function __construct()
    {}

    private function __clone()
    {}

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
            self::$config   = self::config();
        }
        return self::$instance;
    }

    public static function config()
    {
        $configuration = Configuration::forSymmetricSigner(
            // You may use any HMAC variations (256, 384, and 512)
            new Sha256(),
            // replace the value below with a key of your own!
            InMemory::base64Encoded(self::$secret)
            // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
        );
        return $configuration;
    }

    public function encode()
    {
        $now         = new DateTimeImmutable('now', new DateTimeZone(self::$timezone));
        $this->token = self::$config->builder()
            ->issuedBy($this->iss)
            ->permittedFor($this->aud)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('uid', $this->uid)
            ->getToken(self::$config->signer(), self::$config->signingKey());

        return $this;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getToken()
    {
        return $this->token->toString();
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function decode()
    {
        if (!$this->decodeToken) {
            $this->decodeToken = self::$config->parser()->parse((string) $this->token);
            $this->uid         = $this->decodeToken->claims()->get('uid');
        }

        return $this;
    }

    public function validExpiration()
    {
        $now = new DateTimeImmutable('now', new DateTimeZone(self::$timezone));
        if ($this->decodeToken->isExpired($now)) {
            throw new JwtTokenExpiredException();
        }
        return $this;
    }

    public function setValidationConstraints(Constraint...$constraints)
    {
        self::$config->setValidationConstraints(
            ...$constraints
        );
        return $this;
    }

    public function validate()
    {
        $constraints = self::$config->validationConstraints();
        try {
            self::$config->validator()->assert($this->decodeToken, ...$constraints);
        } catch (RequiredConstraintsViolated $e) {
            throw new NeedPermissionException($e->getMessage());
        }
    }

    public function validateToken($token)
    {
        if (!$token) {
            throw new JwtTokenMissingException();
        }
        self::instance()
            ->setToken($token)
            ->setValidationConstraints(
                new Constraint\IssuedBy($this->iss),
                new Constraint\PermittedFor($this->aud),
                new Constraint\SignedWith(self::$config->signer(), self::$config->verificationKey())
            )
            ->decode()
            ->validate();
    }


    public function verify()
    {
    	$clock  = new SystemClock(new DateTimeZone(self::$timezone));
        self::instance()
            ->setValidationConstraints(
                new Constraint\IssuedBy($this->iss),
                new Constraint\PermittedFor($this->aud),
                new Constraint\SignedWith(self::$config->signer(), self::$config->verificationKey()),
                new Constraint\ValidAt($clock)
            )
            ->decode()
            ->validExpiration()
            ->validate();
    }
}
