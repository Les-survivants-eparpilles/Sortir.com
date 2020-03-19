<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
	public function findAllPersonaliser()
	{
		$qb = $this->createQueryBuilder('a');
		$qb->addSelect('s')
			->addSelect('par')
			->addSelect('si')
			->addSelect('l')
			->addSelect('v')
			->addSelect('e')
			->join('s.organisateur', 'par')
			->join('s.site_id', 'si')
			->join('s.lieu_id', 'l')
			->join('s.etat_id', 'e')
			->join('l.ville_id', 'v')
			->orderBy("s.date_heure_debut", "asc");

		// On crée l'objet Query
		$query = $qb->getQuery();

		// On retourne le résultat
		return new Paginator($query);
	}
}
