<?php

    namespace App\Repository;


    use Doctrine\ORM\Query\Expr;
    use App\Entity\{Certificate, People, User};
    use Doctrine\Persistence\ManagerRegistry;
    use Doctrine\ORM\OptimisticLockException;
    use Doctrine\ORM\{QueryBuilder, Query, ORMException};
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


    /**
     * @extends ServiceEntityRepository<People>
     *
     * @method People|null find($id, $lockMode = null, $lockVersion = null)
     * @method People|null findOneBy(array $criteria, array $orderBy = null)
     * @method People[]    findAll()
     * @method People[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class PeopleRepository extends ServiceEntityRepository
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, People::class);
        }

        /**
         * @throws ORMException
         * @throws OptimisticLockException
         */
        public function add(People $entity, bool $flush = true): void
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
        public function remove(People $entity, bool $flush = true): void
        {
            $this->_em->remove($entity);
            if ($flush) {
                $this->_em->flush();
            }
        }

        public function findByYears(\DateTime $start, \DateTime $end)
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.startdate >= :start')
                ->andWhere('p.startdate <= :end')
                ->setParameters(['start' => $start, 'end' => $end])
                ->orderBy('p.startdate', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }
        
        // public function findByPost(int $post = 0) : ? array
        // {
        //     if ($post) {
        //         return $this->createQueryBuilder('p')
        //             ->leftJoin(User::class, 'u', Expr\Join::WITH, 'p.user = u.id')
        //             ->andWhere('u.fullname =: fullname')
        //             ->setParameters(['fullname' => 'The Modern Application Factory'])
        //             ->getQuery()
        //             ->getResult()
        //         ;
        //     }
        //     return $this->createQueryBuilder('p')
        //         ->leftJoin(User::class, 'u', Expr\Join::WITH, 'p.user = u.id')
        //         ->andWhere('u.fullname != :fullname')
        //         ->setParameters(['fullname' => 'The Modern Application Factory'])
        //         ->getQuery()
        //         ->getResult()
        //     ;
        // }

        public function people(int $post = 0): ? Array
        {
            if ($post) {
                return $this->createQueryBuilder('p')
                    ->leftJoin(Certificate::class, 'c', Expr\Join::WITH, 'p.id = c.people')
                    ->leftJoin(User::class, 'u', Expr\Join::WITH, 'p.user = u.id')
                    ->andWhere('c.people IS NULL')
                    ->andWhere('u.fullname !=:fullname')
                    ->setParameters(['fullname' => 'The Modern Application Factory'])
                    ->orderBy(sort: 'p.firstname', order: 'ASC')
                    ->getQuery()
                    ->getResult()
                ;
            }
            return $this->createQueryBuilder('p')
                ->leftJoin(Certificate::class, 'c', Expr\Join::WITH, 'p.id = c.people')
                ->leftJoin(User::class, 'u', Expr\Join::WITH, 'p.user = u.id')
                ->andWhere('c.people IS NULL')
                ->andWhere('u.fullname =:fullname')
                ->setParameters(['fullname' => 'The Modern Application Factory'])
                ->orderBy(sort: 'p.firstname', order: 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }


        public function findInternQuery() : QueryBuilder
        {
            return $this->createQueryBuilder('p')
                ->orderBy('p.id', 'DESC')
            ;
        }

        public function search($search) : ? Array
        {
            $query = $this->findInternQuery();
            $query = $this->searching($search, $query);
            return $query->getQuery()->getResult();
        }
      

        private function searching(String $search, QueryBuilder $query) : QueryBuilder
        {
            $query = $query
                ->andWhere('p.topic LIKE :TopicS OR p.topic LIKE :TopicM OR p.topic LIKE :TopicI OR p.lastname LIKE :lastnameS OR p.lastname LIKE :lastnameM OR p.lastname LIKE :lastnameI OR p.school LIKE :ShoolS OR p.school LIKE :ShoolM OR p.school LIKE :ShoolI')
                ->setParameter('ShoolS', '%%'.$search)
                ->setParameter('ShoolM', '%'.$search.'%')
                ->setParameter('ShoolI', $search.'%')
                ->setParameter('lastnameS', '%%'.$search)
                ->setParameter('lastnameM', '%'.$search.'%')
                ->setParameter('lastnameI', $search.'%')
                ->setParameter('TopicS', '%%'.$search)
                ->setParameter('TopicM', '%'.$search.'%')
                ->setParameter('TopicI', $search.'%')
                ->orderBy('p.startdate', 'desc')
                ->distinct()
            ;
            
            return $query;   
        }

        // /**
        //  * @return People[] Returns an array of People objects
        //  */
        /*
        public function findByExampleField($value)
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.exampleField = :val')
                ->setParameter('val', $value)
                ->orderBy('p.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        }
        */

        /*
        public function findOneBySomeField($value): ?People
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.exampleField = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
        */
    }
