<?php

namespace App\Controller;

use App\Entity\Job;
use App\Helper\Helper;
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
        $jobs = $entityManager->getRepository(Job::class)->findBy(['user' => $user->getId()]);

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
        //создание объекта
        $job = new Job();
        $job->setName($request->get('name'));
        $job->setStatus(true);
        $job->setUser($this->getUser());

        //Валидация
        $errorsString = Helper::validate($job, $validator);

        //добавление в базу
        $entityManager->persist($job);
        $entityManager->flush();

        //Вывод шаблона
        $element = $this->renderView('todo/job.html.twig', ['job' => $job]);

        return Helper::responseJson([
            'html' => $element,
            'errors' => $errorsString,
        ]);
    }

    /**
     * @Route("/todo/api/delete-job/{id}", methods={"DELETE"})
     * удаление задачи задачи
     */
    public function deleteJob(Request $request, $id, EntityManagerInterface $entityManager)
    {
        try {
            $job = $entityManager->getRepository(Job::class)->findOneBy(['id' => $id, 'user' => $this->getUser()]);
            $entityManager->remove($job);
            $entityManager->flush();
            $status = true;
        } catch (\Exception $e) {
            $status = false;
        }

        return Helper::responseJson([
            'status' => $status,
        ]);
    }

    /**
     * @Route("/todo/api/end-job/{id}", methods={"POST"})
     * Выполнение / развыполнение задачи
     */
    public function endJob($id, Request $request, EntityManagerInterface $entityManager)
    {
        $job = $entityManager->getRepository(Job::class)->findOneBy(['id' => $id, 'user' => $this->getUser()]);

        $job->setStatus(!$job->getStatus());
        $date = $job->getStatus() ? NULL : new \DateTime($request->request->get('date_finish'));
        $job->setDateFinish($date);

        $entityManager->flush();

        return Helper::responseJson([
            'status' => $job->getStatus(),
            'datetime' => $job->getDateFinish() == null ? NULL : $job->getDateFinish()->format('Y-m-d\ H:i'),
        ]);
    }


    /**
     * @Route("/todo/api/filter-job/", methods={"GET"})
     * Фильтрация задач
     */
    public function filterJob(Request $request, EntityManagerInterface $entityManager)
    {
        $filter = [
            'user' => $this->getUser(),
        ];

        if ($request->get('status') != '') {
            $filter['status'] =  $request->get('status');
        }

        $jobs = $entityManager->getRepository(Job::class)->findBy($filter);

        $html = '';
        foreach ($jobs as $job) {
            $html .= $this->renderView('todo/job.html.twig', ['job' => $job]);
        }

        return Helper::responseJson([
            'html' => $html,
        ]);
    }
}
