<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Sortie;
use App\Form\LocationType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    /**
     * @Route("/sortie", name="sortie_")
     */
    public function sortie(): Response
    {
        return $this->render('sortie/sortie.html.twig', [

        ]);
    }


    /**
     * @Route("/createSortie", name="createSortie")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */

    public function createSortie(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted()&& $sortieForm->isValid()) {
            $sortie->setEtat('Créée');
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Evènement crée !');
            return $this->redirectToRoute('afficherSortie', ['id' => $sortie->getId()]);

        }

        $locationForm = $this->createForm(LocationType::class);

        //on passe les 2 forms pour affichage
        return $this->render('sortie/createSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'locationForm' => $locationForm->createView()
        ]);
    }

    /**
     * @Route("afficherSortie/{id}", name="afficherSortie")
     * @param int $id
     * @param SortieRepository $sortieRepository
     * @return Response
     */
    public function afficherSortie(int $id, SortieRepository $sortieRepository): Response
    {

        $sortie= $sortieRepository->find($id);
        return $this->render('sortie/afficherSortie.html.twig', [
            "sortie"=>$sortie,

        ]);
    }
}