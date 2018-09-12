<?php
/**
 * Created by PhpStorm.
 * User: malik
 * Date: 02/06/17
 * Time: 11:06
 */

namespace AppBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\NumberType;

use AppBundle\Entity\Circle;
use AppBundle\Entity\Offer;
use AppBundle\Form\CircleType;
use AppBundle\Form\OfferType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class EditOfferController extends Controller
{
    /**
     * @Route("/cercles/{token}/admin/offres", name="edit_offer")
    *
     */
    public function editOfferAction(Request $request, Circle $circle)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $circleUser = $em->getRepository('AppBundle:CircleUser')
            ->findOneBy(['user' => $user->getId(), 'circle' => $circle->getId()]);
        if ($circleUser == null || $circleUser->getAdminCircle() == false) {
            return $this->redirectToRoute('errorAccess');
        }
        $number_circle_users = $circle->getNumberCircleUsers();
        //$form = $this->createForm(CircleType::class, $circle);
        $form = $this->createForm(CircleType::class, $circle)
            ->add('number_circle_users', NumberType::class, [
                "label"=>"Nombre de membres",
                'attr' => [
                    'min' => 2,
                    'max' => 12
                ],
                'data' => $number_circle_users,
                'empty_data' => 6
            ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($circle);
            $em->flush();
            return $this->redirectToRoute('admin', ['token'=>$circle->getToken(), 'circleUser'=>$circleUser, 'number_circle_users'=>$number_circle_users] );

        }

            return $this->render('FrontBundle:Admin:adminServices.html.twig',
                        array("form" => $form->createView(), 'token'=>$circle->getToken(), 'circleUser'=>$circleUser, 'number_circle_users'=>$number_circle_users));

    }

    /**
     * @Route("cercles/{token}/admin/offres/delete", name="deleteCircle")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Circle $circle)
    {
        $em = $this->getDoctrine()->getManager();
        $circle->setActive(0);
        $circle->setAvailabilityDate(new \DateTime());
        $em->persist($circle);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }

}
