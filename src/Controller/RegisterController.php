<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user); // créer un formulaire

        $form->handleRequest($request); // écoute la requête

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData(); // tu injectes toute les données reçu dans le formulaire

            $password = $encoder->encodePassword($user, $user->getpassword());
            $user->setPassword($password);
            // dd($password);

            $this->entityManager->persist($user); // fige les data pour pouvoir les enregistrer plus tard
            $this->entityManager->flush(); // enregistre les data dans la db
        }
        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView(), // créer la vue
        ]);
    }
}
