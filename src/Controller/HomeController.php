<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ChatGPTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(ChatGPTService $chatGPTService, Request $request): Response
    {
        $question = $request->query->get('question');

        $answer = $chatGPTService->getAnswerFromBot($question);

        return $this->render('home/index.html.twig', [
            'answer' => $answer,
            'question' => $question,
        ]);
    }
}
