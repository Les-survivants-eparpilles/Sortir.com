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
    public function liste(SortieRepository $sortieRepo, Request $request)
    {
    	$user = $this->getUser();
    	$idUser = $user->getId();
    	$dropDownSite = $request->query->get("dropdownMenuLink");
    	$recherche = $request->query->get("recherche");
    	$dateDebutRecherche = $request->get("dateDebutRecherche");
    	$dateFinRecherche = $request->get("dateFinRecherche");
    	$checkOrganisateur = $request->get("checkOrganisateur");
	    $checkInscrit = $request->get("checkInscrit");
	    $checkPasInscrit = $request->get("checkPasInscrit");
	    $checkSortiesPassees = $request->get("checkSortiesPassees");
	    //récupérer la liste des sorties dans la bases ainsi que toutes leurs informations
	    $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);

	    $sorties = $sortieRepo->findAllPersonaliser($idUser, $dropDownSite, $recherche, $dateDebutRecherche, $dateFinRecherche, $checkOrganisateur, $checkInscrit, $checkPasInscrit, $checkSortiesPassees);
        return $this->render('sorties.html.twig', [
        	'sorties' => $sorties, 'user' => $user, 'dropdownMenuLink' => $dropDownSite, 'recherche' => $recherche, 'dateDebutRecherche' => $dateDebutRecherche,
	        'dateFinRecherche' => $dateFinRecherche, 'dropDownSite' => $dropDownSite, 'checkOrganisateur' => $checkOrganisateur,
	        'checkInscrit' => $checkInscrit, 'checkPasInscrit' => $checkPasInscrit, 'checkSortiesPassees' => $checkSortiesPassees

        ]);
    }

    /**
     * Détail de la sortie
     * @Route("sortie/{id}", name="sortie_detail", requirements={"id"="\d+"},
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
