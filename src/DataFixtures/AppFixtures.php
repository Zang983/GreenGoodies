<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\OrderFactory;
use App\Factory\OrderHasProductFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $products = ProductFactory::createMany(10,function(){
            return [
              'picture' => random_int(1, 10) . '.webp'
            ];
        });
        $users = UserFactory::createMany(30, function () {
            return [
                "password" => $this->passwordHasher->hashPassword(new User(), "password")
            ];});
        $orders = OrderFactory::createMany(50, function () use ($users) {
            return [
                "user" => $users[array_rand($users,1)]
            ];

    });
        $ordersHasProducts = OrderHasProductFactory::createMany(150, function () use ($products, $orders) {
            return [
                "product" => $products[array_rand($products)],
                "order_reference" => $orders[array_rand($orders)]
            ];
        });

        // $manager->persist($product);

        $manager->flush();
    }
}
