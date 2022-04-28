<?php

namespace App\Repository;

use App\Entity\Command;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Command>
 *
 * @method Command|null find($id, $lockMode = null, $lockVersion = null)
 * @method Command|null findOneBy(array $criteria, array $orderBy = null)
 * @method Command[]    findAll()
 * @method Command[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Command::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Command $entity, bool $flush = true): void
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
    public function remove(Command $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findCommandBetwwenDates($minDate, $maxDate)
    {

        return $this->createQueryBuilder('c')
            ->where('c.CreatedAt > :min_date')
            ->andWhere('c.CreatedAt < :max_date')
            ->andWhere('c.status = 200 OR c.status = 300 OR c.status = 400 OR c.status = 500')
            ->setParameter('min_date', $minDate)
            ->setParameter('max_date', $maxDate)
            ->getQuery()->getResult();
    }

    public function findCommandAmountBetweenDates($minDate, $maxDate)
    {
        return $this->createQueryBuilder('ca')
            ->where('ca.CreatedAt > :date_min')
            ->andWhere('ca.CreatedAt < :date_max')
            ->andWhere('ca.status = 200 OR ca.status = 300')
            ->setParameter('date_min', $minDate)
            ->setParameter('date_max', $maxDate)
            ->getQuery()->getResult();
    }

    public function findBasketBetweenDates($minDate, $maxDate)
    {
        return $this->createQueryBuilder('cb')
            ->where('cb.CreatedAt > :date_min')
            ->andWhere('cb.CreatedAt < :date_max')
            ->andWhere('cb.status = 100')
            ->setParameter('date_min', $minDate)
            ->setParameter('date_max', $maxDate)
            ->getQuery()->getResult();
    }

    public function findBasketAverage($minDate, $maxDate)
    {

        return $this->createQueryBuilder('ba')
            ->where('ba.CreatedAt > :date_min')
            ->andWhere('ba.CreatedAt < :date_max')
            ->andWhere('ba.status = 100')
            ->setParameter('date_min', $minDate)
            ->setParameter('date_max', $maxDate)
            ->getQuery()->getResult();
    }

    public function findConversionBasketBetweenDates($minDate, $maxDate)
    {
        return $this->createQueryBuilder('cb')
            ->where('cb.CreatedAt > :date_min')
            ->andWhere('cb.CreatedAt < :date_max')
            ->andWhere('cb.status = 100')
            ->setParameter('date_min', $minDate)
            ->setParameter('date_max', $maxDate)
            ->getQuery()->getResult();
    }

    public function findRecurrenceBasketNewCustomerBetweenDates($minDate, $maxDate)
    {
        return $this->createQueryBuilder('rbc')
            ->innerJoin('rbc.user', 'u')
            ->where('rbc.CreatedAt > :date_min')
            ->andWhere('rbc.CreatedAt < :date_max')
            ->andWhere('u.createdAt > :date_min')
            ->andWhere('u.createdAt < :date_max')
            ->setParameter('date_min', $minDate)
            ->setParameter('date_max', $maxDate)
            ->getQuery()->getResult();
    }

    public function findRecurrenceBasketOldCustomerBetweenDates($minDate, $maxDate)
    {
        return $this->createQueryBuilder('rbc')
            ->innerJoin('rbc.user', 'u')
            ->where('rbc.CreatedAt > :date_min')
            ->andWhere('rbc.CreatedAt < :date_max')
            ->andWhere('u.createdAt < :date_min')
            ->setParameter('date_min', $minDate)
            ->setParameter('date_max', $maxDate)
            ->getQuery()->getResult();
    }
}
