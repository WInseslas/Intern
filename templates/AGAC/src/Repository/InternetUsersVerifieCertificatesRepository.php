<?php

namespace App\Repository;

use App\Entity\InternetUsersVerifieCertificates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InternetUsersVerifieCertificates>
 *
 * @method InternetUsersVerifieCertificates|null find($id, $lockMode = null, $lockVersion = null)
 * @method InternetUsersVerifieCertificates|null findOneBy(array $criteria, array $orderBy = null)
 * @method InternetUsersVerifieCertificates[]    findAll()
 * @method InternetUsersVerifieCertificates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InternetUsersVerifieCertificatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InternetUsersVerifieCertificates::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(InternetUsersVerifieCertificates $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(InternetUsersVerifieCertificates $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return InternetUsersVerifieCertificates[] Returns an array of InternetUsersVerifieCertificates objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InternetUsersVerifieCertificates
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
