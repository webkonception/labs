<?php
/**
 * Created by PhpStorm.
 * User: necro
 * Date: 01/06/17
 * Time: 16:28
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Circle;
use AppBundle\Entity\DataApp;
use AppBundle\Entity\Wall;
use AppBundle\Form\CircleUserType;
use AppBundle\Form\WallType;
use Doctrine\Tests\Common\DataFixtures\TestEntity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class WallController extends Controller
{
    public function sendMessage($messageToSend){
        $fields = [
            'app_id' => $this->getParameter('one_signal_app_id')
        ] + $messageToSend;

        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic NGZhZjdlMGEtMzViMy00ZmNiLWFjOWEtMTc5ZjgyNjQzNDdk'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function getFileSize($filename)
    {
        $size = 0;

        if (is_file($filename)) {
            $size = filesize($filename) ?: 0;
        }
        if ($size >= 1024 * 1024 * 1024) {
            return sprintf('%.1f GB', $size / 1024 / 1024 / 1024);
        }

        if ($size >= 1024 * 1024) {
            return sprintf('%.1f MB', $size / 1024 / 1024);
        }

        if ($size >= 1024) {
            return sprintf('%d KB', $size / 1024);
        }

        return sprintf('%d B', $size);
    }


    /**
     * @Route("/cercles/{token}/mur", name="mur")
     */
    public function showWallAction(Request $request, Circle $circle){

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $circleId = $circle->getId();
        $currentCircleUser = $em->getRepository('AppBundle:CircleUser')->findOneBy(['user' => $user->getId(), 'circle' => $circleId]);
        if ($currentCircleUser == null || $currentCircleUser->getWallAccess() == false) {
            return $this->redirectToRoute('errorAccess');
        }

        $circleUsers = $em->getRepository('AppBundle:CircleUser')->findBy(['circle' => $circleId]);
        $circleName = $circle->getName();

        $circleUserId = array();
        $circleUsersIds = array();
        $wallUsersColors = array();

        $currentCircleUserId = $currentCircleUser->getUser()->getId();

        $colors = array('#0084FF','#483d3f','#44BEC7','#6699CC','#20CEF5','#67B868','#77685d','#13CF13','#7646FF','#058ed9','#a39a92','#FFC300', '#FA3C4C', '#D696BB', '#FF7E29', '#E68585', '#D4A88C', '#FF5CA1', '#A695C7', '1C75BC', '5598CD', '8EBBDE', 'C6DCEE', 'E9F2F9', '1863A0', '15588D', '0E3B5E', '071D2F', '030C13', '36ABE2', '69C0EA', '9BD6F1', 'CDEAF8', 'EBF7FD', '2E91C0', '2980AA', '1B5671', '0E2B39', '051117', 'C2B499', 'D2C7B3', 'E1DACD', 'F0ECE5', 'F9F8F5', 'A59982', '928773', '615A4D', '312D26', '13120F');
        foreach ($circleUsers as $key => $user) {
            $circleUserId[] = $user->getId();
            $circleUsersIds[] = $user->getUser()->getId();
            $wallUsersColors[$user->getUser()->getId()] = $colors[$key];
        }

        $dataApps = $em->getRepository('AppBundle:DataApp')->findBy(['circleUser'=>$circleUserId],['creationDate'=>'DESC']);

        $wall = new Wall();
        $wall->setContent(null);

        $form = $this->createForm(WallType::class, $wall);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $message = $form->getData()->getContent();

            $wall = new Wall();
            $wall->setContent($message);

            $creationDate = new \DateTime('now');
            $formContent = new DataApp();
            $formContent->setWall($wall);
            $formContent->setCreationDate($creationDate);
            $formContent->setCircleUser($currentCircleUser);
            $formContent->setAgenda(null);
            $formContent->setCloud(null);

            $em->persist($formContent);
            $em->flush();

            $currentUserName = $currentCircleUser->getUser()->getFirstname() . ' ' . $currentCircleUser->getUser()->getName();
            //$currentUserEmail = $currentCircleUser->getUser()->getEmail();

            $filters = [
                [
                    "field" => "tag", "key" => "user_id_circle_id", "relation" => "=", "value" => $circleId,
                ],
                [
                    "operator" => "AND"
                ],
                [
                    "field" => "tag", "key" => "user_id_circle_user_id", "relation" => "!=", "value" => $currentCircleUserId,
                ]
            ];

            $mailer = $this->get('mailer');
            $subject = 'Nouveau message de ' . $currentUserName . ' ajouté sur le Mur du Cercle Confiance : ' . $circleName;
            $mailer_message = new \Swift_Message($subject);
            foreach($circleUsers as $circleUser) {
                /*if(true == $circleUser->getAdminCircle()){
                    $circleUserAdmin = $circleUser;
                }
                elseif(true == $circleUser->getCircleCenter()){
                    $circleUserCenter = $circleUser;
                }else{
                    $circleUserOther[] = $circleUser;
                }*/
                if($currentCircleUserId != $circleUser->getUser()->getId()) {
                    $filters[] = [
                        "operator" => "OR"
                    ];
                    $filters[] = [
                        "field" => "tag", "key" => "user_id_circle_user_id", "relation" => "=", "value" => $circleUser->getUser()->getId(),
                    ];
                    $userEmail = $circleUser->getUser()->getEmail();
                    $userName = $circleUser->getUser()->getFirstname() . ' ' . $circleUser->getUser()->getName();
                    $mailer_message->addBcc($userEmail, $userName);
                }
            }

            $locale = 'fr';
            //$title = 'Cercle Confiance | Mur | ' . date('d/m/Y H:i:s');
            $title = 'Mur | ' . date('d/m/Y H:i:s');
            $headings = array(
                "en" => 'Wall | ' . date('d/m/Y H:i:s'),
                $locale => $title
            );
            $msg = $currentUserName .' a laissé un nouveau message sur le Mur du Cercle Confiance "' . $circleName .'"';
            $contents = [
                'en' => $currentUserName .' left a new message on the Circle of Trust Wall "' . $circleName .'"',
                $locale => $msg
            ];

            $url = 'https://cercle-confiance.fr' . $this->generateUrl('mur', ['token'=>$circle->getToken()]);
            $web_buttons = [];
            array_push($web_buttons, [
                "id" => "wall-button",
                "text" => 'Accéder au Mur du Cercle Confiance ' . $circleName,
                "icon" => "",
                "url" => $url
            ]);
            $notificationType = 'wall-feature-' . $circle->getToken();;
            $uniqId = uniqid($notificationType . '_' . time());
            $messageToSend = [
                'disable_badge_clearing' => true,
                'web_push_topic' => $uniqId,
                'notificationType' => $notificationType,
                'url' => $url,
                'headings' => $headings,
                'filters' => $filters,
                'contents' => $contents,
                'web_buttons' => $web_buttons,
                'android_group' => $notificationType,
                'android_group_message' => [
                    "en" => "You have $[notif_count] new messages",
                    'fr' => "Vous avez $[notif_count] nouveau(x) message(s)",
                ],
                'ios_badgeType' => 'Increase',
                'ios_badgeCount' => 1
            ];
            $response = $this->sendMessage($messageToSend);
            $return["allresponses"] = $response;
            $return = json_encode( $return);

            $mailer_message
                ->setFrom([$this->getParameter('mailer_user') => 'Cercle Confiance'])
                ->setBody($this->renderView('wall_confirmation.html.twig',
                    array(
                        'circleName' => $circleName,
                        'token'=>$circle->getToken(),
                        'creationDate'=>$creationDate,
                        'currentUserName' => $currentUserName
                    )
                ),
                    'text/html');
            $mailer->send($mailer_message);

            $this->addFlash('success', 'Message publié !');
            //$this->addFlash('success', $return);
            return $this->redirectToRoute('mur', ['token'=>$circle->getToken()]);
        }
        $params = [
            'this'=> $this,
            'upload_directory' => $this->getParameter('upload_directory'),
            'wallUsersColors'=>$wallUsersColors,
            'walldatas'=>$dataApps,
            'circleUser'=>$currentCircleUser,
            'token'=>$circle->getToken(),
            'form'=>$form->createView()
        ];
        return $this->render('FrontBundle:Default:wall.html.twig', $params);

    }
}