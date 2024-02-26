<?php

namespace App\Controller\Front;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    /**
     * display home page with list of articles
     *
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/', name: 'app_front_main_home', methods: ['GET'])]
    public function home(ArticleRepository $articleRepository): Response
    {
        return $this->render('front/main/home.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
}
