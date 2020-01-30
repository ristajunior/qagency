<?php

namespace App\Controller;

use App\QSSAPI\Request\AuthorRequest;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends BaseController
{
    /**
     * @var AuthorRequest
     */
    private $authorRequest;

    /**
     * AuthorController constructor.
     * @param AuthorRequest $authorRequest
     * @param SessionInterface $session
     */
    public function __construct(AuthorRequest $authorRequest, SessionInterface $session)
    {
        parent::__construct($session);
        $this->authorRequest = $authorRequest;
    }

    /**
     * Show all authors
     * @Route("/authors", name="authors", methods={"GET"})
     *
     * @return Response
     */
    public function showAllAuthors(): Response
    {
        if (!$this->authenticate()) {
            return $this->redirectToRoute('login');
        }

        return $this->render("authors/authors.html.twig", [
            'authors' => json_decode($this->authorRequest->getAllAuthors()) ?: null
        ]);
    }

    /**
     * Delete author
     * @Route("/authors/{id}", name="author")
     *
     * @param int $id
     * @return Response
     */
    public function showAuthor(int $id): Response
    {
        if (!$this->authenticate()) {
            return $this->redirectToRoute('login');
        }

        return $this->render("authors/author.html.twig", [
            'author' => json_decode($this->authorRequest->getAuthor($id)) ?: null
        ]);
    }

    /**
     * Delete author
     * @Route("/authors/delete/{id}", name="delete_author")
     *
     * @param int $id
     * @return Response
     */
    public function deleteAuthor(int $id): Response
    {
        if (!$this->authenticate()) {
            return $this->redirectToRoute('login');
        }

        $deleteAuthor = $this->authorRequest->deleteAuthor($id);

        if (!$deleteAuthor) {
            $this->addFlash(
                'error',
                'Author was not removed. Please try again!'
            );
        } else {
            $this->addFlash(
                'message',
                'Author was successfully removed!'
            );
        }

        return $this->redirectToRoute('authors');
    }
}