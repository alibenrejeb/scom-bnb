<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\RoleRepository;
use App\Services\PaginatorService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    #[Route('/admin/users/{page<\d+>?1}', name: 'app_admin_users_index')]
    public function index(PaginatorService $paginator, $page): Response
    {
        $paginator->setEntityClass(User::class);

        return $this->render('admin/user/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    #[Route('/admin/users/{id}/edit', name: 'app_admin_users_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $em, RoleRepository $roleRepository): Response
    {
        $userRoles = $user->getUserRoles()->toArray();
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        $formRoles = $form['userRoles']->getData()->getValues();

        $removeRoles = array_udiff($userRoles, $formRoles,
            function ($userRole, $formRole) {
                return $userRole->getId() != $formRole->getId();
            }
        );

        $addRoles = array_udiff($formRoles, $userRoles,
            function ($formRole, $userRole) {
                return $formRole->getId() != $userRole->getId();
            }
        );    

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($addRoles as $role) {
                $role->addUser($user);
            }
    
            foreach ($removeRoles as $role) {
                $role->removeUser($user);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "L'utilisateur n°<strong>{$user->getId()}</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('app_admin_users_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    #[Route('/admin/users/{id}/delete', name: 'app_admin_users_delete')]
    public function delete(User $user): Response
    {
        $this->addFlash(
            'danger',
            "L'utilisateur n°<strong>{$user->getId()}</strong> a bien été supprimée"
        );
        return $this->redirectToRoute('app_admin_users_index');
    }
}
