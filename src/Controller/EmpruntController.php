<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EmpruntRepository;
use App\Entity\Emprunt;
use App\Form\EmpruntType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class EmpruntController extends AbstractController
{
    /**
     * @Route("/emprunt/liste", name="liste_emprunts")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(EmpruntRepository $empruntRepository)
    {
        $liste_emprunts = $empruntRepository->findAll();
        $liste_emprunts_non_rendus = $empruntRepository->findByRetourNull();
        return $this->render('emprunt/liste.html.twig', compact("liste_emprunts", "liste_emprunts_non_rendus"));
    }

    /**
     * @Route("/emprunt/ajouter", name="ajouter_emprunt")
     */
    public function ajouter(Request $request, EMI $entityManager)
    {
        $emprunt = new Emprunt;
        $formulaire = $this->createForm(EmpruntType::class, $emprunt);
        $formulaire->handleRequest($request);
        if( $formulaire->isSubmitted() && $formulaire->isValid() ){
            $entityManager->persist($emprunt);
            $entityManager->flush();
            return $this->redirectToRoute("liste_emprunts");
        }
        return $this->render("emprunt/formulaire.html.twig", [ "formulaire" => $formulaire->createView() ]);
    }

    /**
     * @Route("/emprunt/{id}", name="fiche_emprunt", requirements={"id"="\d+"})
     */
    public function fiche(EmpruntRepository $empruntRepository, $id){
        $emprunt = $empruntRepository->find($id);
        return $this->render("emprunt/fiche.html.twig", compact("emprunt"));
    }

    /**
     * @Route("/emprunt/modifier/{id}", name="modifier_emprunt", requirements={"id"="\d+"})
     */
    public function modifier(Request $request, EMI $entityManager, EmpruntRepository $empruntRepository, $id)
    {
        $emprunt = $empruntRepository->find($id);
        $formulaire = $this->createForm(EmpruntType::class, $emprunt);
        $formulaire->handleRequest($request);
        if( $formulaire->isSubmitted() && $formulaire->isValid() ){
            $entityManager->flush();
            return $this->redirectToRoute("liste_emprunts");
        }
        return $this->render("emprunt/formulaire.html.twig", [ "formulaire" => $formulaire->createView() ]);
    }

    /**
     * @Route("/emprunt/supprimer/{id}", name="supprimer_emprunt", requirements={"id"="\d+"})
     */
    public function supprimer(EMI $entityManager, EmpruntRepository $empruntRepository, int $id)
    {
        $emprunt = $empruntRepository->find($id);
        $entityManager->remove($emprunt);
        $entityManager->flush();
        return $this->redirectToRoute("liste_emprunts");
    }

}
