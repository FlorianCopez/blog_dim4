<?php

namespace App\Controller\Front;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    /**
     * display the form for adding comment
     * 
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Article $article id of article
     * @return Response
     */
    #[Route('/{id}/commentaire/ajouter/', name: 'app_front_comment_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, Article $article): Response
    {
        $comment = new Comment();
        
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $article->addComment($comment);

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash("success", "Le commentaire a bien été ajouté à l'article.");

            return $this->redirectToRoute('app_front_main_home', [], Response::HTTP_SEE_OTHER);
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash("danger", "Le commentaire n'a pas pu être ajouté.");
        }

        return $this->render('front/comment/add.html.twig', [
            'form' => $form,
        ]);
    }
}
