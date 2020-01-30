<?php

namespace App\Controller;

use App\Form\Type\LoginType;
use App\QSSAPI\Login;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LoginController extends BaseController
{
    /**
     * @var Login
     */
    private $login;

    /**
     * LoginController constructor.
     * @param Login $login
     * @param SessionInterface $session
     */
    public function __construct(Login $login, SessionInterface $session)
    {
        parent::__construct($session);
        $this->login = $login;
    }

    /**
     * Login user to session
     *
     * @Route("/", name="login")
     *
     * @param Request $request
     *
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function login(Request $request): Response
    {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $login = $this->login->login($form->getData());

            if ($login) {
                return $this->redirectToRoute('login_success');
            } else {
                return $this->render("login/login.html.twig", [
                    'form' => $form->createView(),
                    'error' => 'Email and password didn\'t match. Login unsuccessful'
                ]);
            }
        }

        return $this->render("login/login.html.twig", [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Show login success info to the user
     *
     * @Route("/login_success", name="login_success")
     *
     * @return Response
     */
    public function loginSuccess(): Response
    {
        if (!$this->authenticate()) {
            return $this->redirectToRoute('login');
        }

        return $this->render("login/login-success.html.twig", [
            'user' => $this->session->get('user'),
        ]);
    }

    /**
     * Logout user from session
     *
     * @Route("/logout", name="logout")
     *
     * @return Response
     */
    public function logout(): Response
    {
        $this->login->logout();

        return $this->redirectToRoute('login');
    }
}