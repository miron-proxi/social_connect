<?php

namespace App\Controller;

use Facebook\Facebook;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Facebook
        $fb = new Facebook([
            'app_id' => '316673569784985',
            'app_secret' => '83d47b9812e5b31e900c0667693bd177',
            'default_graph_version' => 'v2.10',
            ]);
          $helper = $fb->getRedirectLoginHelper();
          
          $permissions = ['email']; // Optional permissions
          $loginUrl = $helper->getLoginUrl('https://127.0.0.1:8000/login', $permissions);
          

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'loginUrl' => $loginUrl]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
