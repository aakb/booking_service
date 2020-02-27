<?php

namespace App\Controller;

use App\Service\ResourceDatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     *
     * @param \App\Service\ResourceDatabaseService $resourceDatabaseService
     *
     * @return Response
     */
    public function index(ResourceDatabaseService $resourceDatabaseService): Response
    {
        $data = $resourceDatabaseService->getData();

        $form = $this->createFormBuilder();
        $form->add('location', ChoiceType::class, [
            'choices' => array_reduce($data['locations'], function ($carry, $el) {
                $carry[$el->name] = $el->id;

                return $carry;
            }, []),
        ]);

        return $this->render('index.html.twig', [
            'form' => $form->getForm()->createView(),
            'data' => $data,
        ]);
    }
}
