<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Content;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker=\Faker\Factory::create('fr_FR');

        for($i=1;$i<=3;$i++)
        {
            $categorie=new Category();
            $categorie->setTitle($faker->sentence())
            ->setDescription($faker->paragraph());
            $manager->persist($categorie);
        
            
            for($j=1;$j <= mt_rand(4,6); $j++)
            {
                $content='<p>'.join($faker->paragraphs(5),'</p><p>').'</p>';

                $article=new Article();
                $article->setTitle($faker->sentence())
                ->setContent("<p>contenu de l'article n°$i)</p>")
                ->setImage($faker->imageUrl())
                ->setCreatedAt($faker->dateTimeBetween('-4 months'))
                ->setCategory($categorie);
                $manager->persist($article);

                for($k=1;$k<= mt_rand(4,10);$k++)
                {
                    $content='<p>'.join($faker->paragraphs(5),'</p><p>').'</p>';

                    $now= new \DateTime();
                    $days=$now->diff($article->getCreatedAt())->days;
                    $minimum='-'.$days.'days';

                    $comment=new Content();
                    $comment->setAuthor($faker->name)
                    ->setContent($content)
                    ->setArticle($article)
                    ->setCreateAt($faker->dateTimeBetween($minimum));
                    $manager->persist($comment);
                }
                
            }
        }



       

        $manager->flush();
    }
}
