<?php

namespace FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('FrontBundle:Default:index.html.twig');
    }

    /**
     * @Route("/services", name="services")
     */
    public function serviceAction()
    {
        return $this->render('FrontBundle:Default:services.html.twig');
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function quiSommesNousAction()
    {
        return $this->render('FrontBundle:Default:quisommesnous.html.twig');
    }

    /**
     * @Route("/objets", name="pres_objets")
     */
    public function objetsAction()
    {
        return $this->render('FrontBundle:Default:objets.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="mentions_legales")
     */
    public function mentionsLegales()
    {
        return $this->render('FrontBundle:Default:mentions_legales.html.twig');
    }

    /**
     * @Route("/nos-partenaires", name="nos_partenaires")
     */
    public function nosPartenaires()
    {
        return $this->render('FrontBundle:Default:nos_partenaires.html.twig');
    }

}
