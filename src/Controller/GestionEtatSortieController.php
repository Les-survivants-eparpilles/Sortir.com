<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GestionEtatSortieController extends AbstractController
{
    /**
     * @Route("/gestion/etat/sortie", name="gestion_etat_sortie")
     */
    public function index()
    {
        return $this->render('gestion_etat_sortie/index.html.twig', [
            'controller_name' => 'GestionEtatSortieController',
        ]);
    }
}
