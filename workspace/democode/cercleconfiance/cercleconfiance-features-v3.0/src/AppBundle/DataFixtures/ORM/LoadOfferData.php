<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 30/05/17
 * Time: 21:22
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Offer;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadOfferData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $offer0 = new Offer();
        $offer0->setName('Service Confiance');
        $offer0->setPrice(50);
        $manager->persist($offer0);

        $offer1 = new Offer();
        $offer1->setName('Service Secours');
        $offer1->setPrice(60);
        $manager->persist($offer1);

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