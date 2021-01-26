<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/modifier-mon-mot-de-passe", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;

        $user = $this->getUser(); // on récupère l'user
        $form = $this->createForm(ChangePasswordType::class, $user); // on fait appel au formulaire que l'on a créé et on passe l'objet User en 2eme paramètre

        $form->handleRequest($request);// On recense toutes les données insérées dans le formulaire

        if($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();
            
            /**
             *  Renvoie true si le password est exacte,
             *  en premier paramètre c'est le mot de passe enregistré dans l'entité User en bdd 
             *  et en deuxième paramètre c'est l'ancien mot de passe qu'on vient de taper
            */ 
            if($encoder->isPasswordValid($user, $old_pwd)){ 
                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_pwd);

                $user->setPassword($password);
                //*** dans le cas d'une mise à jour (update), la methode persist() n'est pas obligatoire
                // $this->entityManager->persist($user);
                $this->entityManager->flush();
                $notification = "Votre mot de passe à bien été mis à jour";
            } else {
                $notification = "Votre mot de passe n'est pas le bon";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
