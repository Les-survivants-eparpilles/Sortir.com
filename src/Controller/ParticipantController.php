<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Site;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantController extends AbstractController
{
	/**
	 * Créer un nouvel utilisateur
	 * @Route("/register", name="participant_registrer")
	 * @param Request $request
	 * @param EntityManagerInterface $em
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$participant = new Participant();
		$registerForm = $this->createForm(RegisterType::class, $participant);
		$registerForm->handleRequest($request);
		if ($registerForm->isSubmitted() && $registerForm->isValid()){
			$password = $encoder->encodePassword($participant, $participant->getPassword());
			$participant->setMotDePasse($password);
			$participant->setAdministrateur(0);
			$participant->setActif(1);

			$site = new Site();
			$site->setNom("ENI-Rennes");
			$em->persist($site);

			$participant->setSite($site);

			$em->persist($participant);
			$em->flush();
			return $this->redirectToRoute("app_login");
		}

		return $this->render('participant/register.html.twig', ['registerForm' => $registerForm->createView()]);
	}

	/**
	 * Récupére un participant en BDD par son ID pour l'affichage du profil
	 * @Route("/profil/{id}", name="participant_autreProfil")
	 * @param Request $request
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function autreProfil(request $request ,$id)
	{
		//récupérer le detail de l'article dans la BDD
		$participantRepo = $this->getDoctrine()->getRepository(Participant::class);
		$participant = $participantRepo->find($id);
		if ($participant == null){
			throw $this->createNotFoundException("Profil inconnu");
		}
		return $this->render("participant/autreProfil.html.twig", ['participant' => $participant]);
	}

    /**
     * Détail de l'article
     * @Route("/monProfil", name="participant_monProfil",
     *     methods={"GET", "POST"})
     */
    public function monProfil(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em) {

        //Récupération de l'utilisateur courant
        $user = $this->getUser();

        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid()){
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setMotDePasse($password);
            $user->setMotDePasse($password);
            $user->setAdministrateur(0);
            $user->setActif(1);

            $site = new Site();
            $site->setNom("ENI-Rennes");
            $em->persist($site);

            $user->setSite($site);
            //Mise à jour du profil
            $em->flush();
            return $this->redirectToRoute("sortie_liste");
        }

        return $this->render('participant/monProfil.html.twig', [
            'registerForm' => $registerForm->createView(),
            'user' => $user
            ]);

    }
}
}


