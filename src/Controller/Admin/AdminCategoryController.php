<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\AdminCategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    #[Route(path: '/admin/create/category', name: 'admin_create_category', methods: ['POST', 'GET'])]
    public function adminCreateCategory(Request $request, EntityManagerInterface $entityManager) : Response
    {
        //dd('test route');
        $category = new Category();

        $formAdminCategory = $this->createForm(AdminCategoryType::class, $category);
        $formAdminCategory->handleRequest($request);

        $formView = $formAdminCategory->createView();

        if($formAdminCategory->isSubmitted()) {
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie créée');

            return $this->redirectToRoute('admin_create_category');
        }
        return $this->render('admin/categories/createCategory.html.twig', ['formView' => $formView]);
    }

    #[Route(path: '/admin/categories/list', name: 'admin_list_categories', methods: ['GET'])]
    public function adminListRecipes(CategoryRepository $categoryRepository): Response
    {
        //dd('test');
        $categories = $categoryRepository->findAll();

        return $this->render('admin/categories/listCategories.html.twig', ['categories' => $categories]);
    }

    #[Route(path: '/admin/update/category/{id}', name: 'admin_update_category',requirements: ['id'=>'\d+'], methods: ['POST', 'GET'])]
    public function adminUpdateCategory(int $id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        //dd('test route');
        $categoryToUpdate = $categoryRepository->find($id);

        if(!$categoryToUpdate) {
            $this->addFlash('error', 'Catégorie introuvable');
            $this -> redirectToRoute('admin_show_category');
        }

        $formAdminCategory = $this->createForm(AdminCategoryType::class, $categoryToUpdate);
        $formAdminCategory->handleRequest($request);

        $formView = $formAdminCategory->createView();

        if($formAdminCategory->isSubmitted()) {
            $entityManager->persist($categoryToUpdate);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie modifiée');

            return $this->redirectToRoute('admin_list_categories');
        }
        return $this->render('admin/categories/updateCategory.html.twig', ['formView' => $formView, 'categoryToUpdate'=>$categoryToUpdate]);
    }

    #[Route(path:'/admin/delete/category/{id}', name: 'admin_delete_category', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function adminDeleteRecipe(int $id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        //dd('test route');
        $categoryToDelete = $categoryRepository->find($id);
        if(!$categoryToDelete) {
            $this->addFlash('error', 'Cette catégorie n\'existe pas');
            $this->redirectToRoute('admin_list_categories');
        }

        //dd($recipe);
        $entityManager->remove($categoryToDelete);
        $entityManager->flush();

        $this->addFlash('success', 'Catégorie supprimée avec succès !');

        return $this->redirectToRoute('admin_list_categories');
    }
}