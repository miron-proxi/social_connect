<?php

namespace App\Controller;

use Facebook\Facebook;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginFacebookController extends AbstractController
{
    /**
     * @Route("/login/facebook", name="login_facebook")
     */
    public function index(): Response
    {
        $fb = new Facebook([
            'app_id' => '{app-id}',
            'app_secret' => '{app-secret}',
            'default_graph_version' => 'v2.10',
            ]);
          
          $helper = $fb->getRedirectLoginHelper();
          
          $permissions = ['email']; // Optional permissions
          $loginUrl = $helper->getLoginUrl('https://example.com/fb-callback.php', $permissions);

        return $this->view('security/login.html.twig', ['loginUrl' => $loginUrl]);
    }
}
