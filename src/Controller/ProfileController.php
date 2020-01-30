<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends BaseController
{
    /**
     * Show user profile
     *
     * @Route("/profile", name="profile")
     *
     * @return Response
     */
    public function showProfile(): Response
    {
        if (!$this->authenticate()) {
            return $this->redirectToRoute('login');
        }

        return $this->render("profile/profile.html.twig");
    }
}