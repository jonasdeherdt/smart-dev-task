<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Entity\Asset;
use App\Form\Type\AssetType;

class AssetController extends AbstractController
{
    
    /**
     * @Route("/asset", name="asset")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $assets = $user->getAssets();
        return $this->render('asset/index.html.twig',[
            'assets' => $assets
        ]);
    }

    public function new(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $asset = new Asset();
        $asset->setName('Asset name');
        $asset->setPath('path to your asset');
        $asset->setOwner($user);

        $form = $this->createForm(AssetType::class, $asset);
        
        return $this->render('asset/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
