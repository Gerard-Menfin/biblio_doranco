<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * La fonction Route permet de créer une action (=page) de votre site
     * Le 1er paramètre correspond à l'url relative (=ce qui est écrit dans la barre d'adresse du navigateur)
     * et le 2ème est le nom de la route (=équivalent au nom d'une variable)
     * 
     * @Route("/", name="home")
     */
    public function index()
    {
        $titre = "Page d'accueil";
        return $this->render("base.html.twig", compact("titre"));

        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/HomeController.php',
        // ]);
    }


    // Exo : créer une route pour l'url "/test"
    //       pour une méthode test()
    //       le message sera "page test"

    /**
     * @Route("/test", name="test")
     */
    public function test(){
        return $this->render("test.html.twig", [
            'titre' => 'page test',
        ]);
    }

    /**
     * @Route("/bienvenue-sur-mon-site", name="bienvenue")
     */
    public function bienvenue(){
        return $this->json([
            'message' => 'Soyez les bienvenus !',
        ]);
    }


    /**
     * Si on veut mettre une variable dans l'url, on place cette variable entre {}
     * On pourra récupérer la valeur de cette variable dans la méthode en la plaçant dans
     * les paramètres de la méthode
     * 
     * @Route("/condition/{nombre}", name="condition")
     */
    public function condition($nombre){
        // $nombre = 12;
        return $this->render("test/condition.html.twig", compact("nombre"));
    }

    /**
     * @Route("/boucle")
     */
    public function boucle(){
        $tableau = [ "un" => 1, "deux" => 2, "trois" => 3 ];
        return $this->render("test/boucle.html.twig", compact("tableau"));
    }
}
