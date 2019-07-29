<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class CalloutRepository extends EntityRepository implements CalloutRepositoryInterface
{
    public function findOrdered(): array
    {
        return $this->createQueryBuilder('o')
            ->addOrderBy('o.priority', 'desc')
            ->getQuery()
            ->getResult()
        ;
    }
}
