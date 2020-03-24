<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\CreerSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JeremyController extends AbstractController
{

    /**
     * Methode pour créer une nouvelle sortie avec possibilité de la publier directement
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/creerSortie", name="sortie_creer")
     *  methods={"GET", "POST"})
     */
    public function creerSortie(Request $request, EntityManagerInterface $em)
    {
        //On récupére l'utilisateur connecté
        $participant = $this->getUser();
        //On recupére l'objet site de l'utiilisateur
        $siteOrganisateur = $participant->getSite();
        //On récupére la ville de l'objet site de l'utilisateur
        $villeOrganisatrice = $siteOrganisateur->getNom();


        $sortie = new Sortie();
        $sortie->setSite($siteOrganisateur);
        $creerSortieForm = $this->createForm(CreerSortieType::class, $sortie);
        $creerSortieForm->handleRequest($request);

        if ($creerSortieForm->isSubmitted() && $creerSortieForm->isValid()){

            //On récupére l'etat en fonction du bouton cliqué
            $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
            if ($creerSortieForm->get('enregistrer')->isClicked()){
                $etat = $etatRepo->find(1);
                $this->addFlash("success", "Sortie enregistrée");
            }else if($creerSortieForm->get('publier')->isClicked()){
                $etat = $etatRepo->find(2);
                $this->addFlash("success", "Sortie publiée");
            }

            //on ajoute les champs manquant du formulaire dans l'objet Sortie
            $sortie->setEtat($etat);
            $sortie->setOrganisateur($participant);
            $sortie->setSite($siteOrganisateur);
            $sortie->setOuverteOuNon(0);


            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute("sortie_liste");
        }

        return $this->render('sortie/gestionSortie.html.twig', ['creerSortieForm' => $creerSortieForm->createView(), 'participant' => $participant ]);
    }

    /**
     * Methode pour modifier une sortie enregistrée. Possibilité de la publier ou de la supprimer
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/modificationSortie/{id}", name="sortie_modification")
     */
    public function modificationSortie(Request $request, EntityManagerInterface $em, $id)
    {
        //On récupére l'utilisateur connecté
        $participant = $this->getUser();

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $modificationSortieForm = $this->createForm(CreerSortieType::class, $sortie);
        $modificationSortieForm->handleRequest($request);

        if ($modificationSortieForm->isSubmitted() && $modificationSortieForm->isValid()){
            //On récupére l'etat en fonction du bouton cliqué
            $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
            if ($modificationSortieForm->get('enregistrer')->isClicked()){
                $etat = $etatRepo->find(1);
                $this->addFlash("success", "Modification de la sortie enregistrées");
            }else if($modificationSortieForm->get('publier')->isClicked()){
                $etat = $etatRepo->find(2);
                $this->addFlash("success", "Sortie publiée");
            }
            $sortie->setEtat($etat);
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute("sortie_liste");
        }

        return $this->render('sortie/gestionSortie.html.twig', ['creerSortieForm' => $modificationSortieForm->createView(), 'sortie' => $sortie, 'participant' => $participant ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/supressionSortie/{id}", name="sortie_supression")
     */
    public function supprimerSortie(Request $request, EntityManagerInterface $em, $id)
    {
        //On récupére l'utilisateur connecté
        $utilisateur = $this->getUser();

        //On récupére l'article en BDD
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        if($sortie==null){
            throw $this->createNotFoundException("Sortie inconnue ou déjà supprimée");
        }
        if(($sortie->getEtat()->getId() == 1) && ($utilisateur->getId() == $sortie->getOrganisateur()->getId())) {
            //suppression en BDD
            $em->remove($sortie);
            $em->flush();
            $this->addFlash("success", "Sortie supprimée");
        }
        return $this->redirectToRoute("sortie_liste");
    }
}
