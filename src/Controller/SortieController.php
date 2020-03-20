<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



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
        return $this->render('sorties.html.twig', [
        	'sorties' => $sorties,
        ]);
    }

    /**
     * Détail de la sortie
     * @Route("/{id}", name="sortie_detail", requirements={"id"="\d+"},
     *     methods={"GET","POST"})
     */
    public function detail(Request $request, $id){
	    //récupérer la liste des sorties dans la bases ainsi que toutes leurs informations
	    $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);

	    $sorties = $sortieRepo->find($id);
	    if($sorties == null) {
	    	throw $this->createNotFoundException("Sortie inconnu");
	    }
	    return $this->render('sortie/afficherSortie.html.twig', [
		    'sorties' => $sorties
	    ]);
    }
}
