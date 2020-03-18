<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * Liste des sorties
     * @Route("/sortie", name="sortie")
     */
    public function liste_Sortie(SortieRepository $sortieRepo)
    {
    	//récupérer la liste des sorties dans la bases ainsi que toutes leurs informations
	    $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);

	    $sorties = $sortieRepo->finAllPerso();



        return $this->render('sortie/index.html.twig', [
        	'sorties' => $sorties

        ]);
    }
}
