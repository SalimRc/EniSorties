<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    /**
     * Méthode appelée en AJAX seulement. Crée une nouvelle location.
     * Voir templates/event/create.html.twig pour le code JS !
     *
     * @Route("/api/location/create", name="location_create")
     */
    public function create(Request $request)
    {
        //récupère les données POST
        $locationData = $request->request->get('location');

        //récupère les infos de la ville associée à ce lieu
        $villeRepo = $this->getDoctrine()->getRepository(Ville::class);
        $ville = $villeRepo->find($locationData["ville"]);

        //@TODO: gérer si on ne trouve pas la ville

        //instancie notre Location et l'hydrate avec les données reçues
        $location = new Location();
        $location->setVille($ville);
        $location->setNom($locationData["nom"]);
        $location->setRue($locationData["rue"]);
        $location->setZip($locationData["zip"]);
            $location->setLatitude($locationData['lat']);
            $location->setLongitude($locationData['lng']);

        //sauvegarde en bdd
        $em = $this->getDoctrine()->getManager();
        $em->persist($location);
        $em->flush();

        //les données à renvoyer au code JS
        //status est arbitraire... mais je prend pour acquis que je renverrais toujours cette clé
        //avec comme valeur soit "ok", soit "error", pour aider le traitement côté client
        //je renvois aussi la Location. Pour que ça marche, j'ai implémenté \JsonSerializable dans l'entité, sinon c'est vide
        $data = [
            "etat" => "ok",
            "location" => $location
        ];

        //renvoie la réponse sous forme de données JSON
        //le bon Content-Type est automatiquement configuré par cet objet JsonResponse
        return new JsonResponse($data);
    }

    /**
     * Méthode appelée en AJAX seulement. Retourne la liste des villes correspondant à un code postal.
     * @Route("/api/location/cities/search", name="location_find_cities_by_zip")
     */
    public function findCitiesByZip(Request $request, VilleRepository $villeRepository)
    {
        $codePostal = $request->query->get('codePostal');
        $villes = $villeRepository->findBy(['codePostal' => $codePostal], ['nom' => 'ASC']);

        return $this->render('location/ajax_cities_list.html.twig', ['villes' => $villes]);
    }
}
