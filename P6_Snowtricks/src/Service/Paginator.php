<?php

namespace App\Service;

use Doctrine\Persistence\ObjectManager;

class Paginator
{
    private $entityClass;
    private $limit = 10;
    private $page = 1;
    private $manager;
    private $orderBy = [];
    private $filterBy = [];

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getPages()
    {
        $repository = $this->manager->getRepository($this->entityClass);
        $total = count($repository->findAll());
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function getData()
    {
        $offset = $this->page * $this->limit - $this->limit;
        $repository = $this->manager->getRepository($this->entityClass);
        $data = $repository->findBy($this->filterBy, $this->orderBy, $this->limit, $offset);

        return $data;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setFilterBy($filterBy)
    {
        $this->filterBy = $filterBy;
        return $this;
    }
}
