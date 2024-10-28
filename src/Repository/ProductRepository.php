<?php

namespace App\Repository;

use App\Entity\Product;
use App\Data\SearchData;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    protected PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }

    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this->getSearchQuery($search)->getQuery();
        return $this->paginator->paginate($query, $search->page, 6);
    }

    public function countItems(SearchData $search): int
    {
        return count($this->getSearchQuery($search)->getQuery()->getResult());
    }

    public function findMinMaxPrice(SearchData $search): array
    {
        return $this->findMinMax($search, 'price');
    }

    public function findMinMaxKms(SearchData $search): array
    {
        return $this->findMinMax($search, 'kilometers');
    }

    public function findMinMaxDate(SearchData $search): array
    {
        $results = $this->getSearchQuery($search, false, false, true)
            ->select('MIN(p.circulationAt) as minDate, MAX(p.circulationAt) as maxDate')
            ->getQuery()
            ->getResult();
        return [$results[0]['minDate'], $results[0]['maxDate']];
    }

    private function findMinMax(SearchData $search, string $field): array
    {
        $results = $this->getSearchQuery($search, true, false, false)
            ->select("MIN(p.$field) as minValue, MAX(p.$field) as maxValue")
            ->getQuery()
            ->getScalarResult();
        return [(int)$results[0]['minValue'], (int)$results[0]['maxValue']];
    }

    private function getSearchQuery(SearchData $search, bool $ignorePrice = false, bool $ignoreKms = false, bool $ignoreDate = false): QueryBuilder
    {
        $query = $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->join('p.model', 'm')
            ->select('c, m, p');

        if (!empty($search->q)) {
            $query->andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        $this->addPriceFilters($query, $search, $ignorePrice);
        $this->addKmsFilters($query, $search, $ignoreKms);
        $this->addDateFilters($query, $search, $ignoreDate);
        $this->addCategoryAndModelFilters($query, $search);

        return $query->orderBy('p.name', 'ASC');
    }

    private function addPriceFilters(QueryBuilder $query, SearchData $search, bool $ignore): void
    {
        if (!$ignore) {
            if (!empty($search->minPrice)) {
                $min = $search->minPrice * 100;
                $query->andWhere('p.price >= :min')->setParameter('min', $min);
            }
            if (!empty($search->maxPrice)) {
                $max = $search->maxPrice * 100;
                $query->andWhere('p.price <= :max')->setParameter('max', $max);
            }
        }
    }

    private function addKmsFilters(QueryBuilder $query, SearchData $search, bool $ignore): void
    {
        if (!$ignore) {
            if (!empty($search->minKms)) {
                $query->andWhere('p.kilometers >= :minKms')->setParameter('minKms', $search->minKms);
            }
            if (!empty($search->maxKms)) {
                $query->andWhere('p.kilometers <= :maxKms')->setParameter('maxKms', $search->maxKms);
            }
        }
    }

    private function addDateFilters(QueryBuilder $query, SearchData $search, bool $ignore): void
    {
        if (!$ignore) {
            if (!empty($search->minCirculationAt)) {
                $query->andWhere('YEAR(p.circulationAt) >= YEAR(:minDate)')->setParameter('minDate', $search->minCirculationAt);
            }
            if (!empty($search->maxCirculationAt)) {
                $query->andWhere('YEAR(p.circulationAt) <= YEAR(:maxDate)')->setParameter('maxDate', $search->maxCirculationAt);
            }
        }
    }

    private function addCategoryAndModelFilters(QueryBuilder $query, SearchData $search): void
    {
        if (!empty($search->categories)) {
            $query->andWhere('c.id IN (:categories)')->setParameter('categories', $search->categories);
        }
        if (!empty($search->model)) {
            $query->andWhere('m.id IN (:model)')->setParameter('model', $search->model);
        }
    }

    public function findByLowMileage(int $maxKilometers = 50000): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.kilometers < :maxKilometers')
            ->andWhere('p.price <= :maxPrice') // Condition pour le prix
            ->setParameter('maxKilometers', $maxKilometers)
            ->setParameter('maxPrice', 12000) // Limite de prix à 12 000 euros
            ->orderBy('p.kilometers', 'ASC')
            ->getQuery()
            ->getResult();
    }
    

    public function findByLowPrice(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.price <= :maxPrice') // Condition pour le prix
            ->setParameter('maxPrice', 15000) // Limite de prix à 15 000 euros
            ->orderBy('p.price', 'ASC') // Trier par prix croissant
            ->setMaxResults(10) // Limiter le nombre de résultats si besoin
            ->getQuery()
            ->getResult();
    }

    public function findByDirection(int $maxKilometers = 30000): array
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.kilometers < :maxKilometers')
        ->andWhere('p.circulationAt >= :minDate') // Limite pour les véhicules récents
        ->setParameter('maxKilometers', $maxKilometers)
        ->setParameter('minDate', (new \DateTime())->modify('-3 years')) // Ajustez la période selon vos critères
        ->orderBy('p.circulationAt', 'DESC')
        ->getQuery()
        ->getResult();
}

    


}
