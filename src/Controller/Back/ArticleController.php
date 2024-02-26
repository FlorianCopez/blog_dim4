<?php

namespace App\Controller\Back;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class ArticleController extends AbstractController
{
    /**
     * display list of articles
     *
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/', name: 'app_back_article_list', methods: ['GET'])]
    public function list(ArticleRepository $articleRepository): Response
    {
        return $this->render('back/article/list.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * display the form for adding an article
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/article/ajouter', name: 'app_back_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success", "L'article a bien été créé.");

            return $this->redirectToRoute('app_back_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash("danger", "L'article n'a pas pu être créé.");
        }

        return $this->render('back/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * display one article by ID
     * 
     * @param Article $article id of article 
     * @return Response
     */
    #[Route('/article/{id}', name: 'app_back_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('back/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * display the form for edit a article
     * 
     * @param Request $request
     * @param Article $article id of article
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/article/{id}/modifier', name: 'app_back_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash("success", "L'article a bien été modifié.");

            return $this->redirectToRoute('app_back_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash("danger", "L'article n'a pas pu être modifié. Veuillez vérifier vos données.");
        }

        return $this->render('back/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * Route for delete a article
     * 
     * @param Request $request
     * @param Article $article id of article
     * @param EntityManager $entityManager
     * @return Response
     */
    #[Route('/article/{id}', name: 'app_back_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash("success", "L'article a bien été supprimé.");
        } else {
            $this->addFlash("danger", "L'article n'a pas pu être supprimé.");
        }

        return $this->redirectToRoute('app_back_article_list', [], Response::HTTP_SEE_OTHER);
    }
}
