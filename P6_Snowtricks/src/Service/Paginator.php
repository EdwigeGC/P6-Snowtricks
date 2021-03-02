<?php

namespace App\Service;

use Doctrine\Persistence\ObjectManager;

/**
 * Service to paginate object in templates
 *
 * @author Edwige Genty
 */
class Paginator
{
    /**
     * @var $entityClass to find name entity class concerned
     */
    private $entityClass;

    /**
     * @var int $limit maximum number of objects displayed
     */
    private $limit = 10;

    /**
     * @var int $page page number
     */
    private $page = 1;

    /**
     * @var ObjectManager $manager
     */
    private $manager;

    /**
     * @var array $orderBy orders the request with argument like 'desc'
     */
    private $orderBy =[];

    /**
     * @var array $filterBy filters the request. Corresponds to 'WHERE' in sql request
     */
    private $filterBy =[];

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Provides the number of pages according to the number of objects to display
     *
     * @return float $pages
     */
    public function getPages()
    {
        $repository = $this->manager->getRepository($this->entityClass);
        $total = count($repository->findAll());
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    /**
     * Provides the result of the request
     *
     * @return object[] $data
     */
    public function getData()
    {
        $offset= $this->page * $this->limit - $this->limit;
        $repository =$this->manager->getRepository($this->entityClass);
        $data = $repository->findBy($this->filterBy,$this->orderBy, $this->limit, $offset);

        return $data;
    }

    /**
     * Setter for EntityClass attribute
     * @param $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * Getter for EntityClass attribute
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Setter for limit attribute
     * @param $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Getter for limit attribute
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Setter for page attribute
     * @param $page
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Getter for page attribute
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Setter for orderBy attribute
     * @param $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Setter for filterBy attribute
     * @param $filterBy
     */
    public function setFilterBy($filterBy)
    {
        $this->filterBy = $filterBy;
        return $this;
    }

}
