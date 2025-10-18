<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    // A/ Ajout d'un livre
    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $book->setPublished(true); // Initialisé à True
        
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Incrémentation de nb_books de l'auteur
            $author = $book->getAuthor();
            if ($author) {
                $author->incrementNbBooks();
                $entityManager->persist($author);
            }
            
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Book added successfully!');
            return $this->redirectToRoute('app_book_index');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    // B/ Affichage des livres
    #[Route('/', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        $publishedBooks = $bookRepository->findBy(['published' => true]);
        $unpublishedBooks = $bookRepository->findBy(['published' => false]);
        
        $publishedCount = count($publishedBooks);
        $unpublishedCount = count($unpublishedBooks);

        return $this->render('book/index.html.twig', [
            'books' => $publishedBooks,
            'published_count' => $publishedCount,
            'unpublished_count' => $unpublishedCount,
        ]);
    }

    // C/ Modification d'un livre
    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Book updated successfully!');
            return $this->redirectToRoute('app_book_index');
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    // D/ Affichage d'un livre
    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    // E/ Suppression d'un livre
    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
            
            $this->addFlash('success', 'Book deleted successfully!');
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }

    // F/ Livres publiés entre deux dates (UNE SEULE FOIS)
      #[Route('/date-range', name: 'books_date_range')] // ← Route finale: /book/date-range
    public function booksBetweenDates(BookRepository $bookRepository): Response
    {
        $startDate = new \DateTime('2014-01-01');
        $endDate = new \DateTime('2018-12-31');
        
        $books = $bookRepository->findBooksBetweenDates($startDate, $endDate);

        return $this->render('book/date_range.html.twig', [
            'books' => $books,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    

    // G/ Nombre de livres Romance
    #[Route('/books/romance/count', name: 'books_romance_count')]
    public function romanceCount(BookRepository $bookRepository): Response
    {
        $count = $bookRepository->countRomanceBooks();

        return $this->render('book/romance_count.html.twig', [
            'count' => $count
        ]);
    }
}