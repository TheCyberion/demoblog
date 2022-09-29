<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1; $i<=10; $i++)
        {
        $article = new Article;
        // On instancie la classe article qui se trouve dans le dossier App/Entity
        //nous ouvons maintenant faire appel aux setters pour insérer des données
        
        $article->setTitle("Titre de l'article n°$i")
                ->setContent("<p>Contenu de l'article n°$i</P>")
                ->setImage("http://picsum.photos/250/150")
                ->setCreatedAt0(new \DateTime);  //J'instancie la classe DateTime pour récupérer la date d'aujourd'hui
        
        
                $manager->persist($article);
                //persist()permet de faire persister l'article dans le temps et preparer son insertion en BDD
            
            }
       $manager->flush();
       //flush() permet d'executer la requete SQL préparée grâce à persist()
    }
}
