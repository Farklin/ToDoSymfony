<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    /**
     * @Route("/todo", name="todo")
     */
    public function index(): Response
    {
        $user = $this->getUser(); 
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
            'user' => $user, 
        ]);
    }

    /**
     * @Route("/todo/api/create-job", methods={"POST"})
     * Создание новой задачи
     */
    public function createJob(Request $request)
    {   
       
    }   

       /**
     * @Route("/todo/api/delete-job", methods={"POST"})
     * Создание новой задачи
     */
    public function deleteJob(Request $request)
    {}   

       /**
     * @Route("/todo/api/end-job/{id}", methods={"PATCH"})
     * Создание новой задачи
     */
    public function endJob($id, Request $request)
    {}   

}
