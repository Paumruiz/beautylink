<?php

namespace App\Repository;

use App\Entity\Empleados;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Empleados>
 *
 * @method Empleados|null find($id, $lockMode = null, $lockVersion = null)
 * @method Empleados|null findOneBy(array $criteria, array $orderBy = null)
 * @method Empleados[]    findAll()
 * @method Empleados[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpleadosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Empleados::class);
    }

    public function findOneByNombre($nombre)
    {
        return $this->createQueryBuilder('e')
            ->where('e.nombre_empleado = :nombre_empleado')
            ->setParameter('nombre_empleado', $nombre)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Empleados[] Returns an array of Empleados objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Empleados
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
