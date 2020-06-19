<?php

namespace App\Controller;

use App\Entity\Abonne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AbonneRepository;
use App\Form\AbonneType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as EMI;

class AbonneController extends AbstractController
{
    /**
     * @Route("/abonne", name="abonne")
     */
    public function index()
    {
        return $this->render('abonne/index.html.twig', [
            'controller_name' => 'AbonneController',
        ]);
    }

    /**
     * @Route("/abonne/liste", name="liste_abonnes")
     */
    public function liste(AbonneRepository $abonneRepository){
        $liste_abonnes = $abonneRepository->findAll();
        return $this->render("abonne/liste.html.twig", compact("liste_abonnes"));
    }

    /**
     * @Route("/abonne/{id}", name="fiche_abonne", requirements={"id"="\d+"})
     */
    public function fiche(AbonneRepository $abonneRepository, $id){
        $abonne = $abonneRepository->find($id);
        return $this->render("abonne/fiche.html.twig", compact("abonne"));
    }

    /**
     * @Route("/abonne/ajouter", name="ajouter_abonne")
     */
    public function ajouter(Request $request, EMI $entityManager)
    {
        $abonne = new Abonne;
        $formulaire = $this->createForm(AbonneType::class, $abonne);
        $formulaire->handleRequest($request);
        if( $formulaire->isSubmitted() && $formulaire->isValid() ){
            $entityManager->persist($abonne);
            $entityManager->flush();
            return $this->redirectToRoute("liste_abonnes");
        }

        return $this->render("abonne/formulaire.html.twig", [ "formulaire" => $formulaire->createView() ]);
    }

        /**
     * @Route("/abonne/modifier/{id}", name="modifier_abonne", requirements={"id"="\d+"})
     */
    public function modifier(EMI $entityManager, Request $request, AbonneRepository $abonneRepository, int $id){
        $abonne = $abonneRepository->find($id);
        $formulaire = $this->createForm(AbonneType::class, $abonne);
        $formulaire->handleRequest($request);
        if( $formulaire->isSubmitted() && $formulaire->isValid() ){
            // $entityManager->persist($abonne);
            $entityManager->flush();
            return $this->redirectToRoute("liste_abonnes");
        }

        return $this->render("abonne/formulaire.html.twig", [ "formulaire" => $formulaire->createView() ]);

    }

    /**
     * @Route("/abonne/supprimer/{id}", name="supprimer_abonne", requirements={"id"="\d+"})
     */
    public function supprimer(EMI $entityManager, AbonneRepository $abonneRepository, int $id)
    {
        $abonne = $abonneRepository->find($id);
        // La méthode 'remove' crée une requête DELETE en attente
        $entityManager->remove($abonne);
        $entityManager->flush();
        return $this->redirectToRoute("liste_abonnes");
    }

}
