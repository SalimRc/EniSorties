<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Utilisateur extends AbstractController
{
    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function login(): Response
    {
        return $this->render('utilisateur/login.html.twig', [
            //todo aller chercher les identifiants en bdd
        ]);
    }
}
