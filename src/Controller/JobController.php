<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends Controller
{
    /**

     * Lists all job entities.
     *
     * @Route("/job", name="job")
     *
     * @return Response
     */
    public function list(Request $request, EntityManagerInterface $em, FileUploader $fileUploader)
    {
        $data = $this->getDoctrine()->getRepository(Job::class)
        ->findAll();
       
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $logoFile */
            $logoFile = $form->get('logo')->getData();

            if ($logoFile instanceof UploadedFile) {
                $fileName = \bin2hex(\random_bytes(10)) . '.' . $logoFile->guessExtension();

                // moves the file to the directory where brochures are stored
                $logoFile->move(
                    $this->getParameter('jobs_directory'),
                    $fileName
                );

                $job->setLogo($fileName);
            }


            $em->persist($job);
            $em->flush();}
        return $this->render('job/index.html.twig', [
            'data' => $data,
            'form' => $form->createView(),
        ]);

    }

    /**
     * Creates a new job entity.
     *
     * @Route("/create", name="job.create")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $logoFile */
            $logoFile = $form->get('logo')->getData();

            if ($logoFile instanceof UploadedFile) {
                $fileName = \bin2hex(\random_bytes(10)) . '.' . $logoFile->guessExtension();

                // moves the file to the directory where brochures are stored
                $logoFile->move(
                    $this->getParameter('jobs_directory'),
                    $fileName
                );

                $job->setLogo($fileName);
            }


            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('job');
        }

        return $this->render('job/modal.html.twig', [
            'form' => $form->createView(),
        ]);
    }
      /**
     * Edit existing job entity
     *
     * @Route("/job/{id}/edit", name="job.edit", methods={"GET", "POST"}, requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(JobType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('job');
        }

        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
   
    /**
     * Finds and displays a job entity.
     *
     * @Route("/list", name="list")
     *
     * @param Job $job
     *
     * @return Response
     */
    public function show(): Response
    {
    $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();
        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
          
        ]);
    }
    
    
 

    

}