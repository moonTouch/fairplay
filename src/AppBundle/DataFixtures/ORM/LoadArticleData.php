<?php  

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Creates 20 articles with random content
     * Add a random user creator
     */
    public function load(ObjectManager $manager)
    {
    	for( $i=0 ; $i < 20 ; $i++ ){

			$title = simplexml_load_file('http://www.lipsum.com/feed/xml?amount=1&what=words&start=0')->lipsum;
			$content = simplexml_load_file('http://www.lipsum.com/feed/xml?amount=1&what=paras&start=0')->lipsum;
    		$user = 'user'.mt_rand(0,4);

    		$article = new Article();

        	$article	
        		->setTitle($title)
        		->setContent($content)
        		->setUser($this->getReference($user));

        	$manager->persist($article);
        	$manager->flush();
    	}  
    }

    public function getOrder()
    {
        return 2;
    }
}
