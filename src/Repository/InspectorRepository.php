<?php

namespace App\Repository;

use App\Entity\Inspector;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inspector>
 *
 * @method Inspector|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inspector|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inspector[]    findAll()
 * @method Inspector[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InspectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inspector::class);
    }

    public function add(Inspector $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Inspector $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
