<?php

namespace App\Controller\Front;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    /**
     * display the form for adding article
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/article/ajouter', name: 'app_front_article_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success", "L'article a bien été créé.");

            return $this->redirectToRoute('app_front_main_home', [], Response::HTTP_SEE_OTHER);
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash("danger", "L'article n'a pas pu être ajouté.");
        }

        return $this->render('front/article/add.html.twig', [
            'form' => $form,
        ]);
    }
}
