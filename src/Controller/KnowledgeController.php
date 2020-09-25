<?php

namespace App\Controller;

use App\Entity\Knowledge;
use App\Repository\KnowledgeRepository;
use App\Form\Type\KnowledgeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class KnowledgeController extends AbstractController
{
    /**
     * @Route("/knowledge/")
     */
    public function index(KnowledgeRepository $KnowledgeRepository)
    {
        $knowledges = $KnowledgeRepository->findAll();
        //var_dump($knowledges);
        // Another possibility to do:
        //$repository = $this->getDoctrine()->getRepository(Knowledge::class);
        //$knowledges = $repository->findAll();

        return $this->render('knowledge.html.twig', ['knowledges' => $knowledges]);
    }

    /**
     * @Route("/knowledge/new")
     */
    public function new(Request $request)
    {
        // creating form
        $knowledge = new Knowledge();
        $knowledge->setName('Enter the name');
        $knowledge->setChapo('Enter the chapo');
        $knowledge->setContent('Enter the content here');

        $form = $this->createForm(KnowledgeType::class, $knowledge);

        // Processing forms
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $knowledge= $form->getData();
            $knowledge->setName($knowledge->getName());
            $knowledge->setChapo($knowledge->getChapo());
            $knowledge->setContent($knowledge->getContent());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($knowledge);
            $entityManager->flush();
        }

        return $this->render('new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
