<?php

namespace App\QSSAPI\Request;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class RequestHandler gets default URL based on environment
 * and gets Symfony HTTP Client for extended request classes to use it
 * @package App\QSSAPI\Request
 */
abstract class RequestHandler
{
    /**
     * URL of the QSS API
     * @var string $url
     */
    protected $url;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * Symfony HTTP Client
     * @var HttpClientInterface $client
     */
    protected $client;

    /**
     * RequestHandler constructor.
     * @param string $url
     * @param SessionInterface $session
     */
    public function __construct(string $url, SessionInterface $session)
    {
        $this->url = $url;
        $this->session = $session;
        $this->client = HttpClient::create();
    }
}