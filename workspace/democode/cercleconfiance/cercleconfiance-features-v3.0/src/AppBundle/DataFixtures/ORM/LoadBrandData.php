<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 30/05/17
 * Time: 21:27
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Brand;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBrandData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $brand0 = new Brand();
        $brand0->setName("Hitachi");
        $manager->persist($brand0);

        $brand1 = new Brand();
        $brand1->setName("WCS");
        $manager->persist($brand1);

        $brand2 = new Brand();
        $brand2->setName("Siemens");
        $manager->persist($brand2);

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