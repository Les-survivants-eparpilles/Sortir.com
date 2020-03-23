<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MounirController extends AbstractController
{
    /**
     * Inscrire l'utilisateur à une sortie
     * @Route("/{id}/inscrireParticipant", name="inscrire_participant", requirements={"id"="\d+"})
     */
    public function inscrireParticipant($id, EntityManagerInterface $em, Request $request)
    {
	    //Récupération de l'utilisateur courant
	    $user = $this->getUser();
    	//Récupérer la liste des participants de la sortie
	    $sortie = $em->getRepository(Sortie::class)->find($id);

	   if ($sortie==null){
	   	throw $this->createNotFoundException("Sortie inconnu");
	   }

		  // Ajouter une nouvelle relation à la sortie
		  $sortie->addRelation($user);

		  // sauvegarder les données dans la base
		  $em->persist($sortie);
		  $em->flush();
		  // ajout d'un message pour l'utilisateur
		  $this->addFlash("success", "Vous êtes bien inscrit à la sortie : )");

		  // modification de l'état de la sortie si le nbInscritMax est atteint
	    if ($sortie->getNbInscriptioonsMax()==count($sortie->getRelation())){
	    	// cloturer la sortie

//			    $etat = new Etat();
//			    $etat->setId(3);

		    $etatRepo=$this->getDoctrine()->getRepository(Etat::class);
		    $etat=$etatRepo->find(3);
		    $sortie->setEtat($etat);

		    //sauvegarde en BDD
		    $em->persist($sortie);
		    $em->flush();
	    }
		  // redirection
		  return $this->redirectToRoute("sortie_detail",[
			  'id' =>$sortie->getId()
		  ]);

    }

	/**
	 * Inscrire l'utilisateur à une sortie
	 * @Route("/{id}/desisteParticipant", name="desiste_participant", requirements={"id"="\d+"})
	 */
	public function desisteParticipant($id, EntityManagerInterface $em, Request $request)
	{
		//Récupération de l'utilisateur courant
		$user = $this->getUser();

		//Récupérer la liste des participants de la sortie
		$sortie = $em->getRepository(Sortie::class)->find($id);
		if ($sortie==null){
			throw $this->createNotFoundException("Sortie inconnu");
		}

		// Ajouter une nouvelle relation à la sortie
		$sortie->removeRelation($user);

		// sauvegarder les données dans la base
		$em->persist($sortie);
		$em->flush();
		// ajout d'un message pour l'utilisateur
		$this->addFlash("success", "Vous n'êtes plus inscrit à la sortie : )");
		// modification de l'état de la sortie si le nbInscritMax est atteint
		if ($sortie->getNbInscriptioonsMax()>count($sortie->getRelation())){
			// cloturer la sortie

//			    $etat = new Etat();
//			    $etat->setId(3);

			$etatRepo=$this->getDoctrine()->getRepository(Etat::class);
			$etat=$etatRepo->find(2);
			$sortie->setEtat($etat);

			//sauvegarde en BDD
			$em->persist($sortie);
			$em->flush();
		}
		// redirection
		return $this->redirectToRoute("sortie_detail",[
			'id' =>$sortie->getId()
		]);

	}
}
