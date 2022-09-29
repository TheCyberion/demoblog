<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Voiture;


class VoitureFixtures extends Fixture
{
public function load(ObjectManager $manager): void
    {
        for($i=1; $i<=15; $i++)
        {
        $voiture = new Voiture;
       
        
        $voiture->setModele("modele de la voiture n°$i")
                 ->setMarque("marque de la voiture n°$i")
                ->setDescription("description de la voiture n°$i")
                ->setPrix($i * 3.7);
                $manager->persist($voiture);
        }
    
    $manager->flush();
    }
}