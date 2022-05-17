<?php

namespace App\Utils;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductUtils {

    protected ProductRepository $repository;

    public function __construct(ProductRepository $repository) {

        $this -> repository = $repository;

    }

    public function getProductByName(string $name): array {

        return $this -> repository -> createQueryBuilder('product')
            -> select('product')
            -> innerJoin('App\Entity\Category', 'c', 'WITH', 'c.id = product.category')
            -> where('product.productName LIKE :productName')
            -> orWhere('c.categoryName LIKE :categoryName')
            -> setParameter('productName', "%" . $name . "%")
            -> setParameter('categoryName', "%" . $name . "%")
            -> getQuery()
            -> getResult();
    }

}