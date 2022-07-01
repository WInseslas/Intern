<?php

namespace App\Repository;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\ORMException;
use App\Entity\{Certificate, People, User};
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @extends ServiceEntityRepository<Certificate>
 *
 * @method Certificate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Certificate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Certificate[]    findAll()
 * @method Certificate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Certificate::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Certificate $entity, bool $flush = true): void
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
    public function remove(Certificate $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByType(int $post = 0) : ? array
    {
        if ($post) {
            return $this->createQueryBuilder('c')
                ->innerJoin(People::class, 'p', Expr\Join::WITH, 'c.people = p.id')
                ->leftJoin(User::class, 'u', Expr\Join::WITH, 'p.user = u.id')
                ->andWhere('u.fullname =:fullname')
                ->setParameters(['fullname' => 'The Modern Application Factory'])
                ->getQuery()
                ->getResult()
            ;
        }
        return $this->createQueryBuilder('c')
            ->innerJoin(People::class, 'p', Expr\Join::WITH, 'c.people = p.id')
            ->innerJoin(User::class, 'u', Expr\Join::WITH, 'p.user = u.id')
            ->andWhere('u.fullname !=:fullname')
            ->setParameters(['fullname' => 'The Modern Application Factory'])
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Certificate[] Returns an array of Certificate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Certificate
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
