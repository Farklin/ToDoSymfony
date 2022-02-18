<?php

namespace App\Controller;

use App\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class TodoController extends AbstractController
{
    /**
     * @Route("/todo", name="todo")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 
        $jobs = $entityManager->getRepository(Job::class)
            ->findBy(['user_id' => $user->getId()]);
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
            'user' => $user, 
            'jobs' => $jobs, 
        ]);
    }

    /**
     * @Route("/todo/api/create-job", methods={"POST"})
     * Создание новой задачи
     */
    public function createJob(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {   
        $job = new Job();
        $job->setName($request->get('name'));
        $job->setStatus(true);
        $job->setUserId($this->getUser());
    
        // ... do something to the $author object
    
        $errors = $validator->validate($job);
    
        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;
    
            return new Response($errorsString);
        }

        $entityManager->persist($job);
        $entityManager->flush();
        
        $element = $this->renderView('todo/job.html.twig', [ 'job' => $job ]); 

        $response = new Response();
        $response->setContent(json_encode([
            'html' => $element,
        ]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }   

       /**
     * @Route("/todo/api/delete-job/{id}", methods={"DELETE"})
     * удаление задачи задачи
     */
    public function deleteJob(Request $request, $id, EntityManagerInterface $entityManager)
    {   
        
        $job = $entityManager->getRepository(Job::class)->findOneBy(['id'=>$id]);
        $entityManager->remove($job);
        $entityManager->flush();
    }   

       /**
     * @Route("/todo/api/end-job/{id}", methods={"PATCH"})
     * Выполнение / развыполнение задачи
     */
    public function endJob($id, Request $request, EntityManagerInterface $entityManager)
    {
        $job = $entityManager->getRepository(Job::class)->find($id);
        $job->setStatus(!$job->getStatus()); 
        if($job->getStatus()){
            $job->setDataFinish(NULL); 
        }else{
            $job->setDataFinish(new \DateTime()); 
        }
        $entityManager->flush(); 

        $response = new Response();
        $response->setContent(json_encode([
            'status' => $job->getStatus(),
            'datetime' => $job->getDataFinish(),
        ]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }   

}
