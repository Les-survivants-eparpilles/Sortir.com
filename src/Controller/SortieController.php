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
     * @param SortieRepository $sortieRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function liste(SortieRepository $sortieRepo)
    {
    	//récupérer la liste des sorties dans la bases ainsi que toutes leurs informations
	    $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);

	    $sorties = $sortieRepo->findAllPersonaliser();
	   // $listeInscrits = $sortieRepo->findAllInscrit();
	    $sortiId = 1;
	   // $nbInscritsBySortie = $sortieRepo->findOneBySomeField($sortiId);

        return $this->render('sorties.html.twig', [
        	'sorties' => $sorties,
	        //'listeInscrits' => $listeInscrits,
	        //'nbInscrits' => $nbInscritsBySortie
        ]);
    }
}
