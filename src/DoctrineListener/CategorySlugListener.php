<?php

namespace App\DoctrineListener;

use App\Entity\Category;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugListener {

    protected SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger) {
        $this -> slugger = $slugger;
    }

    public function prePersist(Category $category, LifecycleEventArgs $event): void {

        if (empty($category -> getSlug()))
            $category -> setSlug(strtolower($this -> slugger -> slug($category -> getName())));
    }

}