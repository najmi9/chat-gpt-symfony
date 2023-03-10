<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ChatType;
use App\Service\ChatGPTClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ChatGPTClient $chatGPTClient
    ): Response {
        $answer = null;

        $form = $this->createForm(ChatType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $prompt = $form->get('prompt')->getData();
            $answer = $chatGPTClient->getAnswer($prompt);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'answer' => $answer,
        ]);
    }
}
