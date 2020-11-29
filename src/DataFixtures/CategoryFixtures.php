<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    const CATEGORY_COUNT = 5;
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i<self::CATEGORY_COUNT; $i++) {
            $category = new Category();
            $category->setName($faker->unique()->words(2, true));
            $manager->persist($category);
            $this->addReference('category'.$i, $category);
        }

        $manager->flush();
    }
}
