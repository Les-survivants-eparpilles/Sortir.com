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

		return $this->render('participants/register.html.twig', ['registerForm' => $registerForm->createView()]);
	}
}


