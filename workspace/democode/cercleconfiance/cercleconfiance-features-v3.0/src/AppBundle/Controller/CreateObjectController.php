<?php
/**
 * Created by PhpStorm.
 * User: necro
 * Date: 01/06/17
 * Time: 16:28
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Circle;
use Doctrine\Tests\Common\DataFixtures\TestEntity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\ModelType;
use Symfony\Component\HttpFoundation\Request;


class CreateObjectController extends Controller
{
    /**
     * @Route("/cercles/{token}/admin/objets", name="objets")
     */
    public function editObjectAction(Request $request, Circle $circle){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $circleUser = $em->getRepository('AppBundle:CircleUser')
            ->findOneBy(['circle'=>$circle->getId(), 'user'=>$user->getId()]);
        if ($circleUser == null || $circleUser->getAdminCircle() == false) {
            return $this->redirectToRoute('errorAccess');
        }
        $objectWithInfo = $em->getRepository('AppBundle:ObjectEntry')
            ->findBy(array("circleUser" => $circleUser->getId()));
        return $this->render('FrontBundle:Admin:adminObjets.html.twig', array("objects" => $objectWithInfo, 'token'=> $circle->getToken(), 'circleUser'=>$circleUser));
    }

    /**
     * @Route("/cercles/{token}/admin/objets/{objectId}", name="admin_objets")
     */
    public function activateObjectAction(Request $request, Circle $circle, $objectId) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $circleUser = $em->getRepository('AppBundle:CircleUser')->findOneBy(['circle'=>$circle->getId(), 'user'=>$user]);
        if ($circleUser == null || $circleUser->getAdminCircle() == false) {
            return $this->redirectToRoute('errorAccess');
        }
        $objectToActivate = $em->getRepository('AppBundle:ObjectEntry')
            ->findBy(['model'=>$objectId, 'circleUser'=>$circleUser->getId()]);
        foreach ($objectToActivate as $value) {
            $access = $value->getAccess();
            $value->setAccess(!$access);
            $em->persist($value);
            $em->flush();
        }
        $objectWithInfo = $em->getRepository('AppBundle:ObjectEntry')->findBy(array("circleUser" => $circleUser->getId()));

        return $this->render('FrontBundle:Admin:adminObjets.html.twig', array("objects" => $objectWithInfo, 'token' => $circle->getToken(), 'circleUser'=>$circleUser));
    }
}