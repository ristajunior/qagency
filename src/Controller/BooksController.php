<?php

namespace App\Controller;

use App\Form\Type\BookType;
use App\QSSAPI\Request\AuthorRequest;
use App\QSSAPI\Request\BookRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BooksController extends BaseController
{
    /**
     * @var BookRequest
     */
    private $bookRequest;

    /**
     * @var AuthorRequest
     */
    private $authorRequest;

    /**
     * AuthorController constructor.
     * @param BookRequest $bookRequest
     * @param AuthorRequest $authorRequest
     * @param SessionInterface $session
     */
    public function __construct(BookRequest $bookRequest, AuthorRequest $authorRequest, SessionInterface $session)
    {
        parent::__construct($session);
        $this->bookRequest = $bookRequest;
        $this->authorRequest = $authorRequest;
    }

    /**
     * Add book
     * @Route("/books/add", name="add_book")
     *
     * @param Request $request
     * @return Response
     */
    public function addBook(Request $request): Response
    {
        if (!$this->authenticate()) {
            return $this->redirectToRoute('login');
        }

        $authors = $this->getAllAuthors();
        $errors = null;

        if (!$authors) {
            $errors = 'This options is currently unavailable';
        }

        $form = $this->createForm(BookType::class, null, $authors);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addBook = $this->bookRequest->addBook(json_encode($form->getData()));

            if ($addBook) {
                $this->addFlash(
                    'message',
                    'Book was successfully created!'
                );
                return $this->redirectToRoute('authors');
            } else {
                $this->addFlash(
                    'error',
                    'Book was not removed. Please try again!'
                );
                return $this->redirectToRoute('authors');
            }
        }

        return $this->render("books/add-book.html.twig", [
                'form' => $form->createView(),
                'error' => $errors
        ]);
    }

    /**
     * Delete book
     * @Route("/books/delete/{id}", name="delete_book")
     *
     * @param int $id
     * @return Response
     */
    public function deleteBook(int $id): Response
    {
        if (!$this->authenticate()) {
            return $this->redirectToRoute('login');
        }

        $deleteBook = $this->bookRequest->deleteBook($id);

        if (!$deleteBook) {
            $this->addFlash(
                'error',
                'Book was not removed. Please try again!'
            );
        } else {
            $this->addFlash(
                'message',
                'Book was successfully removed!'
            );
        }

        return $this->redirectToRoute('authors');
    }

    /**
     * @return array|null
     */
    private function getAllAuthors(): ?array
    {
        $authors = $this->authorRequest->getAllAuthors();

        if (!$authors) {
            return null;
        }

        $allAuthors['choices'] = [];
        foreach (json_decode($authors) as $author) {
            $allAuthors['choices'][$author->first_name . ' ' . $author->last_name] = $author->id;
        }

        return $allAuthors;
    }
}