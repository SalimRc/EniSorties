<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\CreateProfileType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant", name="participant_")
     */
    public function login(): Response
    {
        return $this->render('participant/login.html.twig', [
            //todo aller chercher les identifiants en bdd
        ]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     * @param int $id
     * @param ParticipantRepository $participantRepository
     * @return Response
     */
    public function detail(int $id, ParticipantRepository $participantRepository): Response
    {
        $participantName= $participantRepository->find($id);
        return $this->render('participant/detail.html.twig', [

        ]);
    }

    /**
     * @Route("/createProfile", name="createProfile")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function createProfile(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $participant=new Participant();
        $participantForm = $this ->createForm(CreateProfileType::class, $participant);

        $participantForm->handleRequest($request);

        if ($participantForm->isSubmitted()){
            $participant->setAdministrateur(false);
            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profile a été crée !');
            return $this->redirectToRoute('detail',['id'=>$participant->getId()] );
        }

        return $this->render('participant/createProfile.html.twig', [
            'participantForm'=> $participantForm->createView()
        ]);
    }
}
