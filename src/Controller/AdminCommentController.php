<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Services\PaginatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    #[Route('/admin/comments/{page<\d+>?1}', name: 'app_admin_comments_index')]
    public function index(PaginatorService $paginator, $page): Response
    {
        $paginator
            ->setEntityClass(Comment::class)
            ->setLimit(5);

        return $this->render('admin/comment/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    #[Route('/admin/comments/{id}/edit', name: 'app_admin_comments_edit')]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'success',
                "Le commentaire numéro {$comment->getId()} a bien été modifié"
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/comments/{id}/delete', name: 'app_admin_comments_delete')]
    public function delete(Comment $comment, EntityManagerInterface $em)
    {
        $id = $comment->getId();
        $em->remove($comment);
        $em->flush();

        $this->addFlash(
            'danger',
            "Le commentaire numéro <strong>{$id}</strong> a bien été supprimée"
        );

        return $this->redirectToRoute("app_admin_comments_index");
    }
}
