<?php

namespace AppBundle\Controller;

use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;
use BackendBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller{
    public function newAction(Request $request, $id = null){
        $helpers = $this->get(Helpers::class);
        $jwt_auth = $this->get(JwtAuth::class);

        $token = $request->get('authorization', null);
        $authCheck = $jwt_auth->checkToken($token);

        if($authCheck){
            $identity = $jwt_auth->checkToken($token, true);
            $json = $request->get("json", null);

            if($json != null ){
                //crear tarea

                $params = json_decode($json);

                $createdAt = new \DateTime('now');
                $updatedAt = new \DateTime('now');

                $user_id = ($identity->sub != null )?$identity->sub:null;

                $title = (isset($params->title))? $params->title : null;
                $description = (isset($params->description))? $params->description : null;
                $status = (isset($params->status))? $params->status : null;

                if($user_id != null && $title != null ){
                    $em = $this->getDoctrine()->getManager();


                    $user = $em->getRepository('BackendBundle:User')->findOneBy(array(
                            'id'=>$user_id
                        ));
                    if($id == null){
                        $task = new Task();
                        $task->setUser($user);
                        $task->setTitle($title);
                        $task->setDescription($description);
                        $task->setStatus($status);
                        $task->setCreatedAt($createdAt);
                        $task->setUpdatedAt($updatedAt);
                        $em->persist($task);
                        $em->flush();

                        $data = array(
                            "status"=> "success",
                            "code" => 200,
                            "data" =>$task
                        );
                    } else {
                        $task = $em->getRepository('BackendBundle:Task')->findOneBy(array(
                            'id'=>$id
                        ));
                        if(isset($identity->sub) && $identity->sub == $task->getUser()->getId()){

                            $task->setTitle($title);
                            $task->setDescription($description);
                            $task->setStatus($status);
                            $task->setUpdatedAt($updatedAt);
                            $em->persist($task);
                            $em->flush();

                            $data = array(
                                "status"=> "success",
                                "code" => 200,
                                "data" =>$task
                            );
                        } else {
                            $data = array(
                                "status"=> "error",
                                "code" => 400,
                                "msg" => "Task updated error. you not owner"
                            );
                        }
                    }


                } else {
                    $data = array(
                        "status"=> "error",
                        "code" => 400,
                        "msg" => "Task not created. validation failed!!"
                    );
                }


            } else {
                $data = array(
                    "status"=> "error",
                    "code" => 400,
                    "msg" => "Task not created. params failed!!"
                );
            }
        }else{
            $data = array(
                "status"=> "error",
                "code" => 400,
                "msg" => "Authorization not valid"
            );
        }

        return $helpers->json($data);
    }

    public function tasksAction(Request $request){
        $helpers = $this->get(Helpers::class);
        $jwt_auth = $this->get(JwtAuth::class);

        $token = $request->get('authorization', null);
        $authCheck = $jwt_auth->checkToken($token);

        if($authCheck) {
            $identity = $jwt_auth->checkToken($token, true);
            $data = array(
                "status"=> "success",
                "code" => 200,
                "msg" => "Ok"
            );
        }else{
            $data = array(
                "status"=> "error",
                "code" => 400,
                "msg" => "Authorization not valid"
            );
        }

        return $helpers->json($data);
    }
}