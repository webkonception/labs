<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 31/05/17
 * Time: 14:17
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\TypeObject;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTypeObjectData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $typeObject0 = new TypeObject();
        $typeObject0->setType('Temperature sensor');
        $manager->persist($typeObject0);

        $typeObject1 = new TypeObject();
        $typeObject1->setType('Humidity sensor');
        $manager->persist($typeObject1);

        $typeObject2 = new TypeObject();
        $typeObject2->setType('Opening sensor');
        $manager->persist($typeObject2);

        $typeObject3 = new TypeObject();
        $typeObject3->setType('Smoke sensor');
        $manager->persist($typeObject3);

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