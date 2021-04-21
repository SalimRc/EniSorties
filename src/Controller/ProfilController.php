<?php

namespace App\Controller;

use App\Entity\Utilisateur;

use App\Form\ModifProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Affichage et modification des utilisateurs
 *
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{

    /**
     * Affichage du profil
     *
     * @Route("/{id}", name="profil_utilisateur", requirements={"id": "\d+"})
     */
    public function profile(Utilisateur $utilisateur): Response
    {

        return $this->render('profil/index.html.twig', ['id' => $utilisateur->getId()]);

    }


    /**
     * @Route("/modifier", name="modifier_mon_profil")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        //récupère le user en session
        //ne jamais récupérer le user en fonction de l'id dans l'URL !
        $user = $this->getUser();

        $form = $this->createForm(ModifProfilType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //recuperer le new pass (1) - je l'encode /hasher (1+(bonus)) - remplacer l ancien mot de passe utilisateur par le nouveau pass - (1)



            $mdp =  $form->get('new_password')->getData();
            $hash = $passwordEncoder->encodePassword($user, $mdp);
            $user->setPassword($hash);

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profil modifié !');

            return $this->redirectToRoute('main_home');
        } else {

            $em->refresh($user);

        }

        return $this->render('profil/index.html.twig', [
            'profileForm' => $form->createView(),

        ]);


    }
}
