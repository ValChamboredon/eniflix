<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Response;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function findBySeveralCriterias(string $status, int $offset, int $nbParPage):array //on crée une méthode pour faire une requête personnalisée
    {
        $query= $this->createQueryBuilder('s') //on crée une requête sur l'entité Serie
            ->setMaxResults($nbParPage) //on limite le nombre de résultats
            ->setFirstResult($offset) //on définit le premier résultat à afficher
            ->orderBy('s.name', 'ASC') //on trie par nom de série
            ->where('s.genres like :genre1' )  //on filtre par genre
            ->setParameter('genre1', '%drama%') //on cherche les series de genre drama
            ->andWhere('s.firstAirDate >=:dateSeuil') //on filtre par date de première diffusion
            ->setParameter('dateSeuil', new \DateTime('2020-01-01')); //on cherche les series dont la date de première diffusion est supérieure à 2020-01-01

        if ($status !== 'all') { //si le status n'est pas 'all'
            $query->andWhere('s.status = :status') //on filtre par status
                ->setParameter('status', $status); //on cherche les series avec le status passé en paramètre
        }

        return $query->getQuery() //on exécute la requête
            ->getResult(); //on récupère les résultats
    }

    //    /**
    //     * @return Serie[] Returns an array of Serie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Serie
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
