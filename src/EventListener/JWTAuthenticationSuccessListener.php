<?php

namespace App\EventListener;

use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Class JWTAuthenticationSuccessListener
 * @package App\EventListener
 */
class JWTAuthenticationSuccessListener
{
  /**
   * @var int
   */
  private $tokenLifetime;

  public function __construct(int $tokenLifetime)
  {
    $this->tokenLifetime = $tokenLifetime;
  }

  /**
   * Sets JWT as a cookie on successful authentication.
   * 
   * @param AuthenticationSuccessEvent $event
   * @throws Exception
   */
  public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
  {
    $event->getResponse()->headers->setCookie(
      new Cookie(
        '__Host-JWT', // Cookie name, should be the same as in config/packages/lexik_jwt_authentication.yaml.
        $event->getData()['token'], // cookie value
        0, //time() + $this->tokenLifetime
        '/', // path
        null, // domain, null means that Symfony will generate it on its own.
        true, // secure
        true, // httpOnly
        false, // raw
        'lax' // same-site parameter, can be 'lax' or 'strict'.
      )
    );
  }
}
