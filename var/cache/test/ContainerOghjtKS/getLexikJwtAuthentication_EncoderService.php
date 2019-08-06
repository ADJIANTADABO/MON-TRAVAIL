<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'lexik_jwt_authentication.encoder' shared service.

include_once $this->targetDirs[3].'/vendor/lexik/jwt-authentication-bundle/Encoder/JWTEncoderInterface.php';
include_once $this->targetDirs[3].'/vendor/lexik/jwt-authentication-bundle/Encoder/HeaderAwareJWTEncoderInterface.php';
include_once $this->targetDirs[3].'/vendor/lexik/jwt-authentication-bundle/Encoder/LcobucciJWTEncoder.php';

return $this->services['lexik_jwt_authentication.encoder'] = new \Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder(($this->privates['lexik_jwt_authentication.jws_provider.lcobucci'] ?? $this->load('getLexikJwtAuthentication_JwsProvider_LcobucciService.php')));