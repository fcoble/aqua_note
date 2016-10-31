<?php
/**
 * Created by PhpStorm.
 * User: fcoble
 * Date: 10/31/16
 * Time: 2:07 PM
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{

    public function homepageAction()
    {
        return $this->render('main/homepage.html.twig');
    }


}