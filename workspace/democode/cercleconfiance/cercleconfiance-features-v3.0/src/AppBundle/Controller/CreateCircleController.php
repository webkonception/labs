<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 30/05/17
 * Time: 11:37
 */

namespace AppBundle\Controller;

use AppBundle\Entity\CircleUser;
use AppBundle\Entity\Model;
use AppBundle\Entity\ObjectEntry;
use AppBundle\Form\CircleUserType;
use AppBundle\Form\CircleType;
use AppBundle\Form\ObjectEntryType;
use AppBundle\Form\UserInvitType;
use AppBundle\Form\VerifAccountType;
use AppBundle\Form\VerifCenterAccountType;
use AppBundle\Services\ModelSetter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Circle;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class CreateCircleController extends Controller
{
    public function convertToCamelCase(string $value, string $encoding = null) {
        if ($encoding == null){
            $encoding = mb_internal_encoding();
        }
        $stripChars = "()[]{}=?!.:,-_+\"#~/";
        $len = strlen( $stripChars );
        for($i = 0; $len > $i; $i ++) {
            $value = str_replace( $stripChars [$i], " ", $value );
        }
        $value = mb_convert_case( $value, MB_CASE_TITLE, $encoding );
        $value = preg_replace( "/\s+/", "", $value );
        return $value;
    }

    /**
     * @Route("cercles/creer", name="create")
     */


    public function createCircleAction(Request $request, ModelSetter $modelSetter)
    {
        $all = $request->request->all();
        $firstname = $request->request->get('appbundle_circleUser')['user']['firstname'];
        if (null !== $firstname) {
            $all["appbundle_circleUser"]['user']['firstname'] = ucwords(strtolower($firstname));
        }
        $name = $request->request->get('fos_user_registration_form')['user']['name'];
        if (null !== $name) {
            $all["appbundle_circleUser"]['user']['name'] = strtoupper($name);
        }
        ////////
        /// autofill username with email's informations
        $email = $request->request->get('appbundle_circleUser')['user']['email'];
        if (null !== $email) {
            $pattern = "/([a-z0-9\-._+]+).*/i";
            preg_match_all($pattern, $email,$matches);
            $username = $this->convertToCamelCase($matches[1][0]);
            $all["appbundle_circleUser"]['user']['username'] = $username;
        }
        ////////
        $request->request->replace($all);

        $user = $this->getUser();
        $cercle = new CircleUser();
        $form = $this->createForm(CircleUserType::class, $cercle);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $cercle->getCircle()->setToken(md5(uniqid()));
            $em->persist($cercle);
            $em->flush();

            $idCercle = $cercle->getCircle();
            $centre = $cercle->getUser();
            $centre->setEnabled(true);
            $em->persist($centre);

            $adminCircle = new CircleUser();
            $adminCircle->setUser($user);
            $adminCircle->setCircle($idCercle);
            $adminCircle->setAdminCircle(true);
            $adminCircle->setCircleCenter(false);
            $adminCircle->setCallAccess(true);
            $adminCircle->setWallAccess(true);
            $adminCircle->setAgendaAccess(true);
            $adminCircle->setCloudAccess(true);
            $em->persist($adminCircle);
            $em->flush();

            $models = $modelSetter->setModels($em);
            $CUsers = $em->getRepository('AppBundle:CircleUser')->findBy(['circle' => $idCercle]);
            foreach ($CUsers as $CUser) {
                foreach ($models as $model) {
                    $objectEntry = new ObjectEntry();
                    $objectEntry->setCircleUser($CUser);
                    $objectEntry->setModel($model);
                    $objectEntry->setAccess(true);
                    $em->persist($objectEntry);
                    $em->flush();
                }
            }

            return $this->redirectToRoute('accueil');
        }
        return $this->render('FrontBundle:Default:createCircle.html.twig', array("form" => $form->createView()));
    }

    /**
     * @Route("cercles/creer/centreAdmin", name="centreAdmin")
     */

    public function createCenterAdminAction(Request $request, ModelSetter $modelSetter)
    {
        $cercle = new Circle();
        $form = $this->createForm(CircleType::class, $cercle);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $cercle->setToken(md5(uniqid()));
            $em->persist($cercle);
            $em->flush();


            $idCercle = $em->getRepository('AppBundle:Circle')->findOneBy(['id' => $cercle->getId()]);

            $centerCircle = new CircleUser();
            $centerCircle->setUser($this->getUser());
            $centerCircle->setCircle($idCercle);
            $centerCircle->setAdminCircle(true);
            $centerCircle->setCircleCenter(true);
            $centerCircle->setCallAccess(true);
            $centerCircle->setWallAccess(true);
            $centerCircle->setAgendaAccess(true);
            $centerCircle->setCloudAccess(true);

            $em->persist($centerCircle);
            $em->flush();

            $models = $modelSetter->setModels($em);
            foreach ($models as $model) {
                $objectEntry = new ObjectEntry();
                $objectEntry->setCircleUser($centerCircle);
                $objectEntry->setModel($model);
                $objectEntry->setAccess(true);
                $em->persist($objectEntry);
                $em->flush();
            }

            return $this->redirectToRoute('accueil');
        }

        return $this->render('FrontBundle:Default:centerAdmin.html.twig', array("form" => $form->createView()));
    }

    /**
     * @Route("{token}/invit")
     */

    public function userInvitAction(Request $request, Circle $circle, ModelSetter $modelSetter)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $circleId = $circle->getId();
        if ($user != null && $circleId != null) {
            $currentCircleUser = $em->getRepository('AppBundle:CircleUser')->findOneBy(['user' => $user->getId(), 'circle' => $circleId]);
            if ($currentCircleUser != null) {
                return $this->redirectToRoute('verif_account', ['token' => $circle->getToken()]);
            }
        }

        $all = $request->request->all();
        $firstname = $request->request->get('appbundle_circleUser')['user']['firstname'];
        if (null !== $firstname) {
            $all["appbundle_circleUser"]['user']['firstname'] = ucwords(strtolower($firstname));
        }
        $name = $request->request->get('fos_user_registration_form')['user']['name'];
        if (null !== $name) {
            $all["appbundle_circleUser"]['user']['name'] = strtoupper($name);
        }
        ////////
        /// autofill username with email's informations
        $email = $request->request->get('appbundle_circleUser')['user']['email'];
        if (null !== $email) {
            $pattern = "/([a-z0-9\-._+]+).*/i";
            preg_match_all($pattern, $email,$matches);
            $username = $this->convertToCamelCase($matches[1][0]);
            $all["appbundle_circleUser"]['user']['username'] = $username;
        }
        ////////
        $request->request->replace($all);

        $email_invit = '';
        $email_invit = $request->query->get('email_invit');
        $nom_invit = $request->query->get('nom_invit');

        $invit = new CircleUser();

        $form = $this->createForm(UserInvitType::class, $invit);
        $request->attributes->set('email', $email_invit);
        $form->handleRequest($request);


      //  , 'email' => $email_invit, 'username' => $nom_invit
      //  $form->set

        $em = $this->getDoctrine()->getManager();

        $circleCurrent = $em->getRepository('AppBundle:Circle')->findBy(['token' => $circle->getToken()]);
        $circleName = $circle->getName();
        $circleId = $circleCurrent[0]->getId();
        $circleUsers = $em->getRepository('AppBundle:CircleUser')->findBy(['circle' => $circleId]);

        //$number_circle_users = $this->getParameter('number_circle_users');
        $number_circle_users = $circle->getNumberCircleUsers();
        if (isset($circleUsers) && count($circleUsers) >= $number_circle_users) {
            $errorMsg = 'Le nombre maximal ('. $number_circle_users . ') de membres pour le cercle "' . $circleName . '" est atteint';
            return $this->render('FrontBundle:Admin:invitUser.html.twig', array('token' => $circle->getToken(), 'error' => $errorMsg, "form" => $form->createView()));
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $invit->setCircle($circle);
            $invit->setCircleCenter(0);
            $invit->getUser()->setEnabled(true);
            $em->persist($invit);
            $em->flush();

            $models = $modelSetter->setModels($em);
            foreach ($models as $model) {
                $objectEntry = new ObjectEntry();
                $objectEntry->setCircleUser($invit);
                $objectEntry->setModel($model);
                $objectEntry->setAccess(false);
                $em->persist($objectEntry);
                $em->flush();
            }

            $admin = $em->getRepository('AppBundle:CircleUser')->findOneBy(['circle' => $circleId, 'adminCircle' => 1]);
            $mailer = $this->get('mailer');
            $message = new \Swift_Message('Nouveau membre du Cercle Confiance : ' . $circleName);
            $message->setTo($admin->getUser()->getEmail())
                ->setFrom([$this->getParameter('mailer_user') => 'Cercle Confiance'])
                ->setBody($this->renderView('confirmation.html.twig', array('circleName' => $circleName, 'token'=>$circle->getToken(), 'adminName' => $admin->getUser()->getUsername(), 'invitName' => $invit->getUser()->getUsername())), 'text/html');
            $mailer->send($message);


            $circle_users = $em->getRepository('AppBundle:CircleUser')->findBy(['user' => $invit->getId()]);
            return $this->redirectToRoute('accueil',
                ['CUsers' => $circle_users]);

        }

        return $this->render('FrontBundle:Admin:invitUser.html.twig',
            array("form" => $form->createView(), 'token' => $circle->getToken()));

    }

    /**
     * @Route("{token}/verif_account", name="verif_account")
     */
    public function verifAccountAction(Circle $circle, Request $request, ModelSetter $modelSetter)
    {
        $data = [];
        $form = $this->createForm(VerifAccountType::class, $data);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $circleUsers = $em->getRepository('AppBundle:CircleUser')->findBy(['circle' => $circle]);
        $circleName = $circle->getName();

        //$number_circle_users = $this->getParameter('number_circle_users');
        $number_circle_users = $circle->getNumberCircleUsers();
        if (isset($circleUsers) && count($circleUsers) >= $number_circle_users) {
          $errorMsg = 'Le nombre maximal ('. $number_circle_users . ') de membres pour le cercle "' . $circleName . '" est atteint';
          return $this->render('FrontBundle:Admin:invitUser.html.twig', array('token' => $circle->getToken(), 'error' => $errorMsg, "form" => $form->createView()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $invit = new CircleUser();
            $data = $form->getData();
            $usernameOrEmail = $data['username'];
            if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
                $data['username'] = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($usernameOrEmail)->getUsername();
            }
            $invitedUser = $em->getRepository('UserBundle:User')->findOneBy(['username' => $data['username']]);
            //$invitedUsername = $invitedUser->getUsername();
            $invitedUsername = $data['username'];
            $invitedUserEmail = $invitedUserPassword = $salt = '';
            if ($invitedUser) {
                $invitedUserEmail = $invitedUser->getEmail();
                $invitedUserPassword = $invitedUser->getPassword();
                $salt = $invitedUser->getSalt();
            } else {
                $errorMsg = 'Ce membre n\'existe pas !';
                return $this->render('FrontBundle:Admin:verifAccount.html.twig', array('token' => $circle->getToken(), 'circleName' => $circleName, 'error' => $errorMsg, "form" => $form->createView()));
            }

            $pw = $data['password'];
            $salted = $pw . '{' . $salt . '}';
            $digest = hash('sha256', $salted, true);
            for ($i = 1; $i < 5000; $i++) {
                $digest = hash('sha256', $digest . $salted, true);
            }
            $encodedPassword = base64_encode($digest);

            $cUsers = $circle->getCircleUsers();
            foreach ($cUsers as $cUser){
                if($invitedUserEmail === $cUser->getUser()->getEmail() && $invitedUsername === $cUser->getUser()->getUsername()) {
                    $errorMsg = 'Ce membre fait déjà parti de ce Cercle !';
                    return $this->render('FrontBundle:Admin:verifAccount.html.twig', array('token' => $circle->getToken(), 'circleName' => $circleName, 'error' => $errorMsg, "form" => $form->createView(), 'back'=>true));
                    //return $this->redirectToRoute('errorAccess', ['errorMsg'=>$errorMsg]);
                };
            }

            if ($encodedPassword !== $invitedUserPassword) {
                dump('$encodedPassword !== $invitedUserPassword');
                $errorMsg = 'Le mot de passe n\'est pas correct !';
                return $this->render('FrontBundle:Admin:verifAccount.html.twig', array('token' => $circle->getToken(), 'circleName' => $circleName, 'error' => $errorMsg, "form" => $form->createView()));
                //return $this->redirectToRoute('errorAccess');
            }

            $invit->setCircle($circle);
            $invit->setUser($invitedUser);
            $invit->setCircleCenter(0);
            $em->persist($invit);
            $em->flush();

            $models = $modelSetter->setModels($em);
            foreach ($models as $model) {
                $objectEntry = new ObjectEntry();
                $objectEntry->setCircleUser($invit);
                $objectEntry->setModel($model);
                $objectEntry->setAccess(false);
                $em->persist($objectEntry);
                $em->flush();
            }

            $admin = $em->getRepository('AppBundle:CircleUser')->findOneBy(['circle' => $circle->getId(),
                'adminCircle' => 1]);
            $mailer = $this->get('mailer');
            $message = new \Swift_Message('Nouveau membre du Cercle Confiance : ' . $circleName);
            $message->setTo($admin->getUser()->getEmail())
                ->setFrom([$this->getParameter('mailer_user') => 'Cercle Confiance'])
                ->setBody($this->renderView('confirmation.html.twig', array('circleName' => $circleName, 'token'=>$circle->getToken(), 'adminName' => $admin->getUser()->getUsername(), 'invitName' => $invit->getUser()->getUsername(), 'invitEmail' => $invit->getUser()->getEmail())), 'text/html');
            $mailer->send($message);


            $circle_users = $em->getRepository('AppBundle:CircleUser')->findBy(['user' => $invit->getId()]);
            return $this->redirectToRoute('accueil',
                ['CUsers' => $circle_users]);

        }
        return $this->render('FrontBundle:Admin:verifAccount.html.twig',
            array("form" => $form->createView(), 'token' => $circle->getToken(), 'circleName' => $circleName));
    }

    /**
     * @Route("verif_center_account", name="verif_center_account")
     */
    public function verifCenterAccountAction(Request $request, ModelSetter $modelSetter)
    {
        $user = $this->getUser();
        $data = [];
        $form = $this->createForm(VerifCenterAccountType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $usernameOrEmail = $data['username'];
            if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
                $data['username'] = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($usernameOrEmail)->getUsername();
            }
            $center         = $em->getRepository('UserBundle:User')->findOneBy(['username' => $data['username']]);

            $pw = $data['password'];
            $salt = $center->getSalt();
            $salted = $pw . '{' . $salt . '}';
            $digest = hash('sha256', $salted, true);
            for ($i = 1; $i < 5000; $i++) {
                $digest = hash('sha256', $digest . $salted, true);
            }
            $encodedPassword = base64_encode($digest);

            if ($encodedPassword !== $center->getPassword()) {
                return $this->redirectToRoute('errorAccess');
            }

            $circle = new Circle();
            $circle->setToken(md5(uniqid()));
            $circle->setName($data['circle']->getName());
            $circle->setOffer($data['circle']->getOffer());
            $circle->setNumberCircleUsers($data['circle']->getNumberCircleUsers());
            $em->persist($circle);
            $em->flush();

            $centerCircle = new CircleUser();
            $centerCircle->setUser($center);
            $centerCircle->setCircle($circle);
            $centerCircle->setAdminCircle(false);
            $centerCircle->setCircleCenter(true);
            $centerCircle->setCallAccess(true);
            $centerCircle->setWallAccess(true);
            $centerCircle->setAgendaAccess(true);
            $centerCircle->setCloudAccess(true);
            $em->persist($centerCircle);

            $adminCircle = new CircleUser();
            $adminCircle->setUser($user);
            $adminCircle->setCircle($circle);
            $adminCircle->setAdminCircle(true);
            $adminCircle->setCircleCenter(false);
            $adminCircle->setCallAccess(true);
            $adminCircle->setWallAccess(true);
            $adminCircle->setAgendaAccess(true);
            $adminCircle->setCloudAccess(true);
            $em->persist($adminCircle);
            $em->flush();

            $models = $modelSetter->setModels($em);
            $CUsers = $em->getRepository('AppBundle:CircleUser')->findBy(['circle' => $circle->getId()]);
            foreach ($CUsers as $CUser) {
                foreach ($models as $model) {
                    $objectEntry = new ObjectEntry();
                    $objectEntry->setCircleUser($CUser);
                    $objectEntry->setModel($model);
                    $objectEntry->setAccess(true);
                    $em->persist($objectEntry);
                    $em->flush();
                }
            }

            return $this->redirectToRoute('accueil');
        }
        return $this->render('FrontBundle:Admin:verifCenterAccount.html.twig', array("form" => $form->createView()));
    }
}
