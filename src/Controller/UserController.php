<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $assets = $user->getAssets();

        return $this->render('user/index.html.twig', [
            'assets' => $assets
        ]);
    }
}