<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
   #[Route('/author/{name}', name: 'show_author')] //{name}:vaiable 
public function showAuthor(string $name): Response
{
    return $this->render('author/show.html.twig', [ //templete =render []=tableau
        'name' => $name,
    ]);
}
}
