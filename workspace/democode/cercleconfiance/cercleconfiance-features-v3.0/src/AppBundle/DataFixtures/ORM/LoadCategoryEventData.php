<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 31/05/17
 * Time: 15:06
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\CategoryEvent;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategoryEventData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $categoryEvent0 = new CategoryEvent();
        $categoryEvent0->setName('medical');
        $categoryEvent0->setColor("#00FF00");
        $manager->persist($categoryEvent0);

        $categoryEvent1 = new CategoryEvent();
        $categoryEvent1->setName('social');
        $categoryEvent1->setColor("#FFFF00");
        $manager->persist($categoryEvent1);

        $categoryEvent2 = new CategoryEvent();
        $categoryEvent2->setName('loisir');
        $categoryEvent2->setColor("#0000FF");
        $manager->persist($categoryEvent2);

        $categoryEvent3 = new CategoryEvent();
        $categoryEvent3->setName('administratif');
        $categoryEvent3->setColor("#FF0000");
        $manager->persist($categoryEvent3);

        $manager->flush();

    }

    /**
     * Get the order of this fixture
     * @return integer
     */
    public function getOrder()
    {
        return 0;
    }
}