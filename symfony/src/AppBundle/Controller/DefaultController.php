<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Services\Helpers;

class DefaultController extends Controller
{
  
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    public function pruebaAction(){
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository("BackendBundle:User");
        $users = $userRepo->findAll();

        $helpers = $this->get(Helpers::class);
        return $helpers->json(array(
            'status' => 'success',
            'users'  => $users
        ));
        /*die;

        return $this->json(array(
            'status' => 'success',
            'users'  => $users[0]
        ));*/
    }
}
