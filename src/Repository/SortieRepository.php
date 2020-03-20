<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use function Sodium\add;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


//    public function findOneBySomeField($value): ?Sortie
//	    //SELECT COUNT(participant_id) FROM `sortie_participant` WHERE sortie_id=1
//    {
//    	$em = $this->getEntityManager();
//    	// on crée la requête DQL
//	    $dql = "SELECT COUNT(participant_id)
//	            FROM sortie_participant
//	            WHERE sortie_id=$value";
//		// on crée un objet Query
//	    $query = $em->createQuery($dql);
//	    // on retourne le résultat
//
//        return $query ->getOneOrNullResult()
//        ;
//    }

	public function findAllPersonaliser()
	{
		// on crée un objet QueryBuilder
		$qb = $this->createQueryBuilder('s');
		$qb->addSelect('par')
			->addSelect('si')
			->addSelect('l')
			->addSelect('v')
			->addSelect('e')
			->join('s.organisateur', 'par')
			->join('s.site', 'si')
			->join('s.lieu', 'l')
			->join('s.etat', 'e')
			->join('l.ville', 'v')
			->orderBy("s.dateHeureDebut", "asc");

		// On crée l'objet Query
		$query = $qb->getQuery();

		// On retourne le résultat
		return new Paginator($query);
	}

	public function findAllInscrit()
	{
		//Ci-dessous la requette SQL afin d'afficher tout les inscrits d'une sortie
		//SELECT * FROM `sortie_participant` WHERE 1
		// on crée un objet QueryBuilder
		$qb = $this->createQueryBuilder('s');
		$qb->addSelect('par')
			->join('s.organisateur', 'par')
			->andWhere("s.id = par.id");

			//On crée l'objet Query
		$query = $qb->getQuery();

		// On retourne le résultat
		return new Paginator($query);
	}


	public function nbInscrits()
	{
		//Le nombre d'inscrit dans une sortie se trouve dans cette requette BDD

		//SELECT COUNT(participant_id) FROM `sortie_participant` WHERE sortie_id=1

		$qb = $this->createQueryBuilder('s');

		$qb	->addSelect('p')
			->join('s.organisateur', 'p')
			->select('count(p.id)')
			->groupBy('s.id');
		$query = $qb->getQuery();

		return $query->getScalarResult();

	}


}
