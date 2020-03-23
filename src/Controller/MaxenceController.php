<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\AnnulerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MaxenceController extends AbstractController
{
    /**
     * @Route("/maxence", name="maxence")
     */
    public function index()
    {
        return $this->render('maxence/index.html.twig', [
            'controller_name' => 'MaxenceController',
        ]);
    }

    /**
	 * @Route("/sortie/publier/{id}", name="gestionEtat_publier")
    */
    public function publier($id, Request $request, EntityManagerInterface $em){

        //Récupération de l'objet sortie
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        if($sortie == null) {
            throw $this->createNotFoundException("Sortie inconnu");
        }

        //Mise à jour de l'état en mode "ouverte" seulement si l'état précédent est "créé"
        if($sortie->getEtat()->getId()==1) {
            $etatRepo=$this->getDoctrine()->getRepository(Etat::class);
            $etat=$etatRepo->find(2);
            $sortie->setEtat($etat);
            $em->persist($sortie);
            $em->flush();
        }
        //Redirection vers la fonction controlleur d'affichage globale
        return $this->redirectToRoute("sortie_liste");
    }

    /**
     * @Route("/sortie/annuler/{id}", name="gestionEtat_annuler")
     */
    public function annuler($id, Request $request, EntityManagerInterface $em)
    {

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if($sortie == null) {
            throw $this->createNotFoundException("Sortie inconnu");
        }

        $sortieForm = $this->createForm(AnnulerType::class, $sortie);
        $sortieForm->handleRequest($request);
        //Pour annuler, la sortir doit être en état ouverte avec un motif d'annulation d'inséré
        if ($sortieForm->isSubmitted() && $sortieForm->isValid() &&
            $sortieForm->get("motifAnnulation")->getData() !=null
            && $sortie->getOuverteOuNon()==true){

            $etatRepo=$this->getDoctrine()->getRepository(Etat::class);
            $etat=$etatRepo->find(6);
            $sortie->setEtat($etat);
            $em->persist($sortie);
            //Mise à jour de la sortie en mode annulation
            $em->flush();
            $this->addFlash("success", "La sortie a été annulée");
            return $this->redirectToRoute("sortie_liste");
        }
        else if($sortieForm->isSubmitted() && $sortieForm->isValid() &&
            $sortieForm->get("motifAnnulation")->getData() ==null){
            $this->addFlash("danger", "Le motif d'annulation est obligatoire.");
        }

        return $this->render('sortie/annulerSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'sortie' => $sortie
        ]);

    }



}
