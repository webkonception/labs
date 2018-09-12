<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 11/07/17
 * Time: 16:45
 */

namespace AppBundle\Services;


use AppBundle\Entity\Model;
use Doctrine\Common\Persistence\ObjectManager;

class ModelSetter
{
    public function setModels(ObjectManager $manager)
    {
        $brands = $manager->getRepository('AppBundle:Brand')->findAll();
        $type_objects = $manager->getRepository('AppBundle:TypeObject')->findAll();

        $model0 = new Model();
        $model0->setBrand($brands[0]);
        $model0->setDescription('Ceci est un thermomètre Hitachi');
        $model0->setReference('UX220AD');
        $model0->setUniqId(md5(uniqid()));
        $model0->setPrice(10);
        $model0->setTypeObject($type_objects[0]);
        $model0->setDocUrl('http://www.chirped.org/c/c3/thermometres%20et%20prise%20de%20temperature.pdf');
        $manager->persist($model0);

        $model1 = new Model();
        $model1->setBrand($brands[2]);
        $model1->setDescription('Ceci est un detecteur de fumée Siemens');
        $model1->setReference('R2D2');
        $model1->setUniqId(md5(uniqid()));
        $model1->setPrice(4.5);
        $model1->setTypeObject($type_objects[3]);
        $model1->setDocUrl('http://www.chirped.org/c/c3/thermometres%20et%20prise%20de%20temperature.pdf');
        $manager->persist($model1);

        $model2 = new Model();
        $model2->setBrand($brands[1]);
        $model2->setDescription('Ceci est un robot de surveillance');
        $model2->setReference('SysPo');
        $model2->setUniqId(md5(uniqid()));
        $model2->setPrice(5.5);
        $model2->setTypeObject($type_objects[2]);
        $model2->setDocUrl('http://www.chirped.org/c/c3SysPo.pdf');
        $manager->persist($model2);

        $manager->flush();

        return [$model0, $model1, $model2];
    }
}
