<?php

namespace App\Repository;

use App\Entity\Centros;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Centros>
 *
 * @method Centros|null find($id, $lockMode = null, $lockVersion = null)
 * @method Centros|null findOneBy(array $criteria, array $orderBy = null)
 * @method Centros[]    findAll()
 * @method Centros[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CentrosRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Centros::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $centro, string $newHashedPassword): void
    {
        if (!$centro instanceof Centros) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $centro::class));
        }

        $centro->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($centro);
        $this->getEntityManager()->flush();
    }

    public function save(Centros $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Centros $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Centros[] Returns an array of Centros objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Centros
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
