<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 06/06/17
 * Time: 17:36
 */

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Circle;
use AppBundle\Form\Circle_userType;
use AppBundle\Form\ObjectAccessType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Circle_user;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use AppBundle\Form\ObjectAccessTypeType;



class AdminUsersController extends Controller
{

    /**
     * @Route("cercles/{token}/admin/membres", name="listMembers")
     */
    public function listUsersAction(Request $request, Circle $circle)
    {
        $userToinvite = new User();

        $form = $this->createFormBuilder($userToinvite)
            ->add('email', EmailType::class, ['label'=>'Renseigner l\'email de la personne à inviter : '])
            //->add('name', TextType::class, ['label'=>'Nom : (optionnel)', 'required' => false])
            ->add('envoyer', SubmitType::class, array(
                    'attr' => array('class' => 'btn btn-default btn-submit-resize')));
        $form = $form->getForm();

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $circleId = $circle->getId();
        $circleName = $circle->getName();
        $users = $em->getRepository('AppBundle:CircleUser')->findBy(['circle'=>$circleId]);
        $objects = $em->getRepository('AppBundle:ObjectEntry')->findBy(['circleUser'=>$users]);
        $currentCircleUser = $em->getRepository('AppBundle:CircleUser')->findOneBy(['user' => $user->getId(), 'circle' => $circleId]);

        $number_circle_users = $circle->getNumberCircleUsers();

        if ($currentCircleUser == null || $currentCircleUser->getAdminCircle() == false) {
            return $this->redirectToRoute('accueil');
        }
        $form->handleRequest($request);

        $userAdmin = null;
        $userCenter = null;
        $userOther = null;

        foreach($users as $user){
            if(true == $user->getAdminCircle()){
                $userAdmin = $user;
            }
            elseif(true == $user->getCircleCenter()){
                $userCenter = $user;
            }else{
                $userOther[] = $user;
            }
        }
        $usersWithAdminFirst[0] = $userAdmin;

        if (isset($userCenter)) {
            $usersWithAdminFirst[1] = $userCenter;
        }

        if (count($userOther) > 0) {
            foreach ($userOther as $user) {
                $usersWithAdminFirst[] = $user;
            }
        }

        $currentUser = $this->getUser();
        $circleUser = $em->getRepository('AppBundle:CircleUser')
            ->findOneBy(['circle'=>$circle->getId(), 'user'=>$currentUser->getId()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $mailer = $this->get('mailer');
            $message = new \Swift_Message('Invitation Cercle Confiance : ' . $circleName);
            $message->setTo($userToinvite->getEmail())
            ->setFrom([$this->getParameter('mailer_user') => 'Cercle Confiance'])
            ->setBody($this->renderView('invitation.html.twig', array('circleName' => $circleName, 'name' => $userToinvite->getName(), 'token'=>$circle->getToken())), 'text/html');

            $mailer->send($message);

            if ($mailer->send($message)) {
                $mailSent = true;
            } else {
                $mailSent = false;
            }

            return $this->render('FrontBundle:Admin:adminUsers.html.twig', ['users'=>$usersWithAdminFirst, 'number_circle_users'=>$number_circle_users, 'token'=>$circle->getToken(), "form" => $form->createView(), 'objects'=>$objects, 'circleUser'=>$circleUser, 'mailSent'=>$mailSent, 'invitEmail' => $userToinvite->getEmail()]);
        }
        return $this->render('FrontBundle:Admin:adminUsers.html.twig', ['users'=>$usersWithAdminFirst, 'number_circle_users'=>$number_circle_users, 'token'=>$circle->getToken(), "form" => $form->createView(), 'objects'=>$objects, 'circleUser'=>$circleUser]);

    }


    /**
     * @Route("cercles/{token}/admin/membres/{idUser}", name="editMember")
     */
    public function editUsersAccessAction(Circle $circle, $idUser, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $circleUser = $em->getRepository('AppBundle:CircleUser')->findBy(['id'=>$idUser]);
        $circleUser = $circleUser[0];
        $objects = $em->getRepository('AppBundle:ObjectEntry')->findBy(array("circleUser" => $circleUser));
        $user = $this->getUser();
        $currentCircleUser = $em->getRepository('AppBundle:CircleUser')
            ->findOneBy(['user' => $user->getId(), 'circle' => $circle->getId()]);
        if ($currentCircleUser == null || $currentCircleUser->getAdminCircle() == false) {
            return $this->redirectToRoute('accueil');
        }

        $userAccess = [];
        $formBuilder = $this->createFormBuilder($userAccess);


        $formBuilder->add('Visio', ChoiceType::class, array('choices' => array('Autoriser l\'acces'=>true, 'Refuser l\'acces'=>false), 'data' => $circleUser->getCallAccess()))
                    ->add('Mur', ChoiceType::class, array('choices' => array('Autoriser l\'acces'=>true, 'Refuser l\'acces'=>false), 'data' => $circleUser->getWallAccess()))
                    ->add('Cloud', ChoiceType::class, array('choices' => array('Autoriser l\'acces'=>true, 'Refuser l\'acces'=>false), 'data' => $circleUser->getCloudAccess()))
                    ->add('Agenda', ChoiceType::class, array('choices' => array('Autoriser l\'acces'=>true, 'Refuser l\'acces'=>false), 'data' => $circleUser->getAgendaAccess()))
                ;

                    foreach ($objects as $object){
                        $formBuilder->add(''.$object->getModel()->getReference(), ChoiceType::class, array('choices' => array('Autoriser l\'acces'=>true, 'Refuser l\'acces'=>false), 'data' => $object->getAccess()));
                    }
                ;

        $formBuilder->add('Valider', SubmitType::class);


        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $circleUser->setCallAccess($form->getData()['Visio']);
            $circleUser->setWallAccess($form->getData()['Mur']);
            $circleUser->setCloudAccess($form->getData()['Cloud']);
            $circleUser->setAgendaAccess($form->getData()['Agenda']);

            foreach ($objects as $object){

                $fct = ''.$object->getModel()->getReference();
                $object->setAccess($form->getData()[$fct]);
                $em->persist($object);

            }

            $em->persist($circleUser);
            $em->flush();
            return $this->redirectToRoute('listMembers', ['token'=>$circle->getToken()]);
        }

        return $this->render('FrontBundle:Admin:editUserAccess.html.twig', [
            'form' => $form->createView(),
            'user' => $circleUser,
            'token'=>$circle->getToken(),
        ]);
    }

    /**
     * @Route("cercles/{token}/admin/membres/{idUser}/delete", name="deleteMember")
     * @Method({"GET", "POST"})
     */
    public function deleteAction($idUser, $token)
    {
        $em = $this->getDoctrine()->getManager();

        $circleUser = $em->getRepository('AppBundle:CircleUser')->findOneBy(['id'=>$idUser]);
        $data = $em->getRepository('AppBundle:DataApp')->findBy(['circleUser'=>$idUser]);

        if ($data != null) {

            $circleToken = $em->getRepository('AppBundle:Circle')->findOneBy(['token'=>$token]);
            $circleId = $circleToken->getId();

            $admin = $em->getRepository('AppBundle:CircleUser')->findOneBy(['circle'=>$circleId, 'adminCircle'=>1]);

            $data = $em->getRepository('AppBundle:DataApp')->findOneBy(['circleUser'=>$idUser]);
            $newData = $data->setCircleUser($admin);
            $em->persist($newData);

        }

        $em->remove($circleUser);
        $em->flush();

        return $this->redirectToRoute('listMembers', ['token'=>$token]);
    }

}
