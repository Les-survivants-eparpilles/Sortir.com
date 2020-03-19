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
     * @Route("/listeSortie", name="sortie_liste")
     */
    public function liste(SortieRepository $sortieRepo)
    {
    	//récupérer la liste des sorties dans la bases ainsi que toutes leurs informations
	    $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);

	    $sorties = $sortieRepo->findAllPersonaliser();



        return $this->render('sortie/index.html.twig', [
        	'sorties' => $sorties

        ]);
    }
}
