<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Entity\Livre;
use App\Repository\EmpruntRepository;
use Symfony\Component\HttpFoundation\Request;

class LivreController extends AbstractController
{
    /**
     * Autowiring = injection de dépendance 
     * 
     * @Route("/admin/livre", name="livre")
     */
    public function index()
    {
        return $this->render('livre/index.html.twig', [
            'controller_name' => 'LivreController',
        ]);
    }

    /**
     * @Route("/admin/livre/liste", name="liste_livres")
     */
    public function liste(LivreRepository $livreRepository, EmpruntRepository $empruntRepository){
        $liste_livres = $livreRepository->findAll();
        // EXO : mettre un array contenant des objets Livre non rendus dans la variable $liste_livres_non_rendus
        // $liste_livres_non_rendus = [];
        // $emprunts = $empruntRepository->findByRetourNull();
        // foreach($emprunts as $emprunt){
        //     $liste_livres_non_rendus[] = $emprunt->getLivre();
        // }

        $liste_livres_non_rendus = $livreRepository->findByNonRendus();
        return $this->render("livre/liste.html.twig", compact("liste_livres", "liste_livres_non_rendus"));
    }

    /**
     * @Route("livre/{id}", name="fiche_livre", requirements={"id"="\d+"})
     */
    public function fiche(LivreRepository $livreRepository, $id){
        $livre = $livreRepository->find($id);
        return $this->render("livre/fiche.html.twig", compact("livre"));
    }

    /**
     * @Route("/admin/livre/ajouter", name="ajouter_livre")
     */
    public function ajouter(EMI $entityManager, Request $request){

        if($request->isMethod("POST")){
            // La classe EntityManager va être utilisé pour les requêtes de modification de la BDD
            // c'est-à-dire INSERT INTO, UPDATE, DELETE
            $livre = new Livre;
            // Dans $request, il y a une propriété 'request' qui contient un objet contenant ce qu'il y a
            //                dans $_POST
            //              , une propriété 'query' contient $_GET
            $livre->setTitre($request->request->get("titre"));   // $_POST["titre"]
            $livre->setAuteur($request->request->get("auteur")); // $_POST["auteur"]

            // La méthode 'persist' de l'EntityManager va créer une requête INSERT INTO avec les informations
            // de l'objet passé en paramètre et la mettre en attente
            // comme la méthode 'prepare' de PDO
            $entityManager->persist($livre);

            // La méthode 'flush' exécute les requêtes en attente
            // comme la méthode 'execute' de PDO
            $entityManager->flush();

            // Je redirige vers la route de la liste des livres
            return $this->redirectToRoute("liste_livres");
        }

        // si ce n'est pas un appel POST, j'affiche le formulaire
        return $this->render("livre/formulaire.html.twig");
    }

    /**
     * @Route("/admin/livre/modifier/{id}", name="modifier_livre", requirements={"id"="\d+"})
     */
    public function modifier(EMI $entityManager, Request $request, LivreRepository $livreRepository, int $id)
    {
        $livre = $livreRepository->find($id);
        if($request->isMethod("POST")){
            $livre->setTitre($request->request->get("titre"));   // $_POST["titre"]
            $livre->setAuteur($request->request->get("auteur")); // $_POST["auteur"]

            // La méthode 'persist' peut aussi créer une requête UPDATE avec l'objet qui est passé en paramètre
            // Pour savoir s'il faut faire une requête INSERT ou UPDATE, cela dépend de la valeur de la propriété id
            $entityManager->persist($livre);
            
            // Toutes les requêtes en attente sont exécutées lors du lancement de la méthode 'flush'
            $entityManager->flush();
            return $this->redirectToRoute("liste_livres");
        }
        return $this->render("livre/formulaire.html.twig", [ "livre" => $livre ]);
    }


    /**
     * @Route("/admin/livre/supprimer/{id}", name="supprimer_livre", requirements={"id"="\d+"})
     */
    public function supprimer(EMI $entityManager, LivreRepository $livreRepository, int $id)
    {
        $livre = $livreRepository->find($id);
        // La méthode 'remove' crée une requête DELETE en attente
        $entityManager->remove($livre);
        $entityManager->flush();
        return $this->redirectToRoute("liste_livres");
    }
}
