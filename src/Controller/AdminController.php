<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as Password;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserRepository $ur, Request $request, EMI $em, Password $passwordEncoder)
    {
        $user = new User;
        $frm = $this->createForm(UserType::class, $user);
        $frm->handleRequest($request);
        if( $frm->isSubmitted() && $frm->isValid() ){
            $mdp = $passwordEncoder->encodePassword($user, $frm->get("password")->getData());
            $user->setPassword( $mdp );
            $em->persist($user);
            $em->flush();
            $user = new User;
            $frm = $this->createForm(UserType::class, $user);
        }
        $users = $ur->findAll();

        return $this->render('admin/index.html.twig', [ 
            "users" => $users,
            "formulaire" => $frm->createView()
        ]);
    }
}
