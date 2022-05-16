<?php

namespace App\Utils;

use App\Repository\CategoryRepository;

class CategoryUtils {

    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this -> categoryRepository = $categoryRepository;
    }

    public function findAll(): array {
        return $this -> categoryRepository -> findAll();
    }

}