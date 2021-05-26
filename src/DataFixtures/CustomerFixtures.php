<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Customer;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $customer = [];
        for ($k = 0; $k < 5; $k++) {
            $customer = new Customer();
            $customer->setLogin($faker->word())
                ->setName($faker->word())
                ->setPassword($faker->password())
                ->setEmail($faker->email())
                ->setNumberSiret($faker->siret())
                ->setCreationDate($faker->dateTime($max = 'now', $timezone = null));
            $customers[] = $customer;
            $manager->persist($customer);
        }

        $product = [];
        for ($j = 1; $j <= 10; $j++) {
            $product = new Product();
            $product->setName($faker->word())
                ->setDescription($faker->sentence())
                ->setDateCreation($faker->dateTime($max = 'now', $timezone = null))
                ->setPrice($faker->randomNumber(3))
                ->setImage($faker->imageUrl());
            $products[] = $product;
            $manager->persist($product);
        }

        for ($n = 1; $n <= 30; $n++) {
            $user = new User();

            $user->setName($faker->lastName())
                ->setFirstName($faker->firstName())
                ->setMail($faker->email())
                ->setAdress($faker->address())
                ->setDateOfBirth($faker->date($format = 'Y-m-d', $max = 'now'))
                ->setCustomer($customers[array_rand($customers)]);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
