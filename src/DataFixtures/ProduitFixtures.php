<?php
namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $product = new Produit();
            $product->setNom('product '.$i);
            $product->setPrix(mt_rand(1, 100));
            $product->setDescription('description '.$i);
            $manager->persist($product);
        }

        $manager->flush();
    }
}