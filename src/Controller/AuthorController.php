<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; // <- CORRECT !
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Form\AuthorType;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

final class AuthorController extends AbstractController
{
    // Page d'accueil
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    // Affiche un auteur par son nom
    #[Route('/author/name/{name}', name: 'show_author_by_name')]
    public function showAuthor(string $name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

    // Liste complète des auteurs (statique)
    #[Route('/authors', name: 'list_authors')]
    public function listAuthors(): Response
    {
        $authors = [
            [
                'id' => 1,
                'picture' => '/images/Victor-Hugo.jpg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com',
                'nb_books' => 100
            ],
            [
                'id' => 2,
                'picture' => '/images/william-shakespeare.jpg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ],
            [
                'id' => 3,
                'picture' => '/images/Taha_Hussein.jpg',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            ],
        ];

        return $this->render('author/list.html.twig', [
            'authors' => $authors
        ]);
    }

    // Affiche le détail d'un auteur (statique)
    #[Route('/author/details/{id}', name: 'author_details')]
    public function authorDetails(int $id): Response
    {
        $authors = [
            [
                'id' => 1,
                'picture' => '/assets/images/image1.jpeg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com',
                'nb_books' => 100
            ],
            [
                'id' => 2,
                'picture' => '/assets/images/image2.jpeg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ],
            [
                'id' => 3,
                'picture' => '/assets/images/image3.png',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            ],
        ];

        $author = null;
        foreach ($authors as $a) {
            if ($a['id'] === $id) {
                $author = $a;
                break;
            }
        }

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author
        ]);
    }

    // Liste des auteurs à partir de la BD
    #[Route('/showAll', name: 'showAll')]
    public function showAll(AuthorRepository $repo): Response
    {
        $authors = $repo->findAll(); // récupère tous les auteurs depuis la BD

        return $this->render('author/showAll.html.twig', [
            'list' => $authors
        ]);
    }

    //  Ajoute un auteur dans la base de données
    #[Route('/author/add', name: 'add_author')]
    public function add(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $author = new Author();
        $author->setUsername('New Author');
        $author->setEmail('new.author@gmail.com');
        $author->setNbBooks(5);

        $entityManager->persist($author);
        $entityManager->flush();

        return new Response('Auteur ajouté avec succès : ' . $author->getUsername());
    }

#[Route('/deleteAuthor/{id}', name: 'delete_author')]
public function deleteAuthor(int $id, AuthorRepository $repo, ManagerRegistry $doctrine): Response
{
    //  Récupérer l’auteur à supprimer à partir de son identifiant
    $author = $repo->find($id);

    //  Vérifier si l’auteur existe dans la base de données
    if (!$author) {
        throw $this->createNotFoundException('Auteur non trouvé avec l’ID : ' . $id);
    }

    //  Supprimer l’auteur trouvé
    $em = $doctrine->getManager();
    $em->remove($author);

    //  Appliquer les modifications dans la base de données
    $em->flush();

    //  Rediriger vers la page qui affiche la liste complète des auteurs
    return $this->redirectToRoute('showAll');
}

#[Route('/author/{id}', name: 'author_show')]
public function show(int $id, AuthorRepository $repo): Response
{
    $author = $repo->find($id);

    if (!$author) {
        throw $this->createNotFoundException('Auteur non trouvé');
    }

    return $this->render('author/showDetail.html.twig', [
        'author' => $author
    ]);
}


#[Route('/author/update/{id}', name: 'update_author')]
public function update(int $id, Request $request, AuthorRepository $repo, ManagerRegistry $doctrine): Response
{
    $author = $repo->find($id);
    if (!$author) {
        throw $this->createNotFoundException('Auteur non trouvé');
    }

    $author->setUsername($author->getUsername() . ' (modifié)');
    $doctrine->getManager()->flush();

    return $this->redirectToRoute('author_show', ['id' => $author->getId()]);
}



#[Route('/addForm', name: 'addForm')]
public function addForm(Request $request, ManagerRegistry $doctrine): Response
{
    $author = new Author();

    $form = $this->createForm(AuthorType::class, $author);
    $form->add('save', SubmitType::class, ['label' => 'Enregistrer']);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('showAll');
    }

   return $this->render('author/addForm.html.twig', [
    'form' => $form->createView(),
]);

}

#[Route('/showAllqr', name: 'app_show_all_qr')]
public function showAllqr(AuthorRepository $repository): Response
{
    $authors = $repository->showAllQr(); // Utilise la méthode du repository

    return $this->render('author/showAll.html.twig', [
        'authors' => $authors
    ]);
}


    #[Route('/authors/search', name: 'authors_search')]
    public function searchByBookCount(Request $request, AuthorRepository $authorRepository): Response
    {
        // Récupérer les paramètres de la requête GET
        $minBooks = $request->query->get('minBooks');
        $maxBooks = $request->query->get('maxBooks');
        
        $authors = [];
        
        // Si les deux paramètres sont présents, effectuer la recherche
        if ($minBooks !== null && $minBooks !== '' && $maxBooks !== null && $maxBooks !== '') {
            $authors = $authorRepository->findAuthorsByBookCountRange(
                (int)$minBooks, 
                (int)$maxBooks
            );
        }

        return $this->render('author/search.html.twig', [
            'authors' => $authors,
            'minBooks' => $minBooks,
            'maxBooks' => $maxBooks
        ]);
    }

    #[Route('/authors/delete-zero-books', name: 'authors_delete_zero_books')]
    public function deleteAuthorsWithZeroBooks(AuthorRepository $authorRepository): Response
    {
        $deletedCount = $authorRepository->deleteAuthorsWithZeroBooks();
        
        $this->addFlash('success', $deletedCount . ' auteurs sans livres ont été supprimés.');

        return $this->redirectToRoute('authors_search');
    }
}