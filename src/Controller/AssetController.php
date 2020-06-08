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

    /**
     * @Route("/assets/new", name="new_asset")
     */
    public function new(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $asset = new Asset();
        $form = $this->createForm(AssetType::class, $asset);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $user = $this->getUser();
            
            $file = $form['attachment']->getData();
            $name = $this->createSlug($form['name']->getData());
            $newFilename = $this->generateFileName($file, $name);
            
            $file->move($this->getParameter('attachments_directory'), $newFilename);

            $asset->setOwner($user);
            $asset->setName($name);
            $asset->setPath($newFilename);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($asset);
            $entityManager->flush();

            return $this->redirectToRoute('asset');
        }

        return $this->render('asset/new.html.twig', [
            'form' => $form->createView(),
            ]);
    }

    private function generateFileName($file, $name){
        $extension = $file->guessExtension();
        if (!$extension) $extension = 'png';

        $name = $name.'.'.$extension;
        return $name;
    }

    private function createSlug($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }
}
