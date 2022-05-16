<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Bluemmb\Faker\PicsumPhotosProvider;
use Bezhanov\Faker\Provider\Commerce;
use Faker\Factory;
use Liior\Faker\Prices;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture {

    protected SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger) {

        $this -> slugger = $slugger;

    }

    public function load(ObjectManager $manager): void {

        $faker = Factory::create("fr_FR");
        $faker -> addProvider(new Prices($faker));
        $faker -> addProvider(new Commerce($faker));
        $faker -> addProvider(new PicsumPhotosProvider($faker));

        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category -> setName($faker -> department)
                    -> setSlug(strtolower($this -> slugger -> slug($category -> getName())));

            $manager -> persist($category);

            for ($j = 0; $j < mt_rand(15, 20); $j++) {

                $product = new Product();
                $product -> setName($faker -> productName)
                    -> setPrice($faker -> price(4000, 20000))
                    -> setSlug(strtolower($this -> slugger -> slug($product ->  getName())))
                    -> setCategory($category)
                    -> setShortDescription($faker -> paragraph)
                    -> setPicture($faker -> imageUrl(400, 400, true));
                $manager -> persist($product);
            }
        }

        $manager -> flush();
    }
}