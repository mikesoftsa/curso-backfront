<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

class DefaultController extends Controller
{
  
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    public function  loginAction(Request $request){
        $helpers = $this->get(Helpers::class);

        //Recibir json por POST
        $json = $request->get('json', null);

        $data = array(
            'status' => 'error',
            'data' => 'Send json via post !!'
        );

        if($json != null){
            // me haces el login
            //convertimos un Json a un Array
            $params = json_decode($json);

            $email = (isset($params->email)) ? $params->email : null;
            $password = (isset($params->password)) ? $params->password : null;
            $getHash = (isset($params->getHash)) ? $params->getHash : null;

            $emailConstraint = new Assert\Email();
            $emailConstraint->message = "This email is not valid !!";
            $validate_email = $this->get("validator")->validate($email, $emailConstraint);

            if($email != null && count($validate_email) == 0 && $password != null){
                $jwt_auth = $this->get(JwtAuth::class);
                if($getHash == null || $getHash == false){
                    $signup = $jwt_auth->signup($email, $password);
                } else {
                    $signup = $jwt_auth->signup($email, $password, true);
                }


               return $this->json($signup);
            } else {
                $data = array(
                    'status' => 'error',
                    'data' => 'Email or password incorrect'
                );
            }


        }

        return $helpers->json($data);

    }


    public function pruebaAction(Request $request){

        $token = $request->get('autorization', null);
        $helpers = $this->get(Helpers::class);
        $jwt_auth = $this->get(JwtAuth::class);

        if($token && $jwt_auth->checkToken($token)){
            $em = $this->getDoctrine()->getManager();
            $userRepo = $em->getRepository("BackendBundle:User");
            $users = $userRepo->findAll();


            return $helpers->json(array(
                'status' => 'success',
                'users'  => $users
            ));
        } else {
            return $helpers->json(array(
                'status' => 'error',
                'code' => 400,
                'data'  => "Autorization not valid"
            ));
        }
    }
}
