<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Bluemmb\Faker\PicsumPhotosProvider;
use Bezhanov\Faker\Provider\Commerce;
use Faker\Factory;
use Liior\Faker\Prices;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture {

    protected SluggerInterface $slugger;
    protected UserPasswordHasherInterface $hasher;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hasher) {

        $this -> slugger = $slugger;
        $this -> hasher = $hasher;

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
                    -> setPicture($faker -> imageUrl(400, 400, true))
                    -> setQuantity(mt_rand(2, 7));
                $manager -> persist($product);
            }
        }

        $admin = new User();

        $hash = $this -> hasher -> hashPassword($admin, "adminPassword");

        $admin -> setEmail("admin@gmail.com")
                -> setPassword($hash)
                -> setFullName("Admin")
                -> setRoles(['ROLE_ADMIN']);

        $manager -> persist($admin);

        for ($x = 0; $x < 5; $x++) {

            $user = new User();

            $hash = $this -> hasher -> hashPassword($user, "password");
            $name = $faker -> name();

            $user
                -> setEmail(str_replace(' ', '.', strtolower($name))."@gmail.com")
                -> setFullName($name)
                -> setPassword($hash)
            ;

            $manager -> persist($user);
        }

        $manager -> flush();
    }
}
