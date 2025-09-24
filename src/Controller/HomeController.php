<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')] // pour execution  path /home.., 
    public function index(): Response //
    {
        return $this->render('home/index.html.twig', [ // reder men absreact conroler win paht=templet mta3 conroler jdid 
            'controller_name' => 'HomeController',// varable hat feha chene 
            'identifiant'=>5 
        ]);
    }
    #[Route('/hello', name: 'hello')] 
    public function hello():Response{
        return new Response(content:"Hello 3a26");

    }

#[Route('/contact/{tel}', name: 'contact')] // kol wahda 3andha name wahdaha 
    public function contact($tel):Response{
        return $this->render('home/contact.html.twig',['telephone'=>$tel]);

    }
 #[Route('/show', name: 'show')] 
    public function show():Response{
        return new Response(content:"bienvenuse a ooredoooo");

    }


    #[Route('/affiche', name: 'affiche')] // kol wahda 3andha name wahdaha 
    public function affiche():Response{
        return $this->render('home/apropos.html.twig');

    }



       #[Route('/', name: 'app_home')]
    public function rediriger(): Response
    {
        return $this->render('home/index.html.twig');
    }

}
