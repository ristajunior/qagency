<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BaseController extends AbstractController
{
    /**
     * @var SessionInterface $session
     */
    protected $session;

    /**
     * BaseController constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Authenticate for user session and redirect
     * to login page if session is out
     *
     * @return bool
     */
    protected function authenticate(): bool
    {
        if (!$this->session->get('user')) {
            return false;
        }

        return true;
    }
}