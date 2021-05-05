<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $data = array();

        $data["books"] = $entityManager
            ->getRepository(Book::class)
            ->findAll();

        return $this->render('book/index.html.twig', $data);
    }

    /**
     * @Route("/book/create", name="create_book")
     */
    public function createBook(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $book = new Book();
        $book->setTitle('Little Dorrit');
        $book->setAuthor('Charles Dickens');
        $book->setISBN('9780141439969');
        $book->setImage('https://s1.adlibris.com/images/6947291/little-dorrit.jpg');

        // tell Doctrine you want to (eventually) save the book (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new book with id ' . $book->getId());
    }
}
