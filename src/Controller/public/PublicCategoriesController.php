<?php

namespace App\Controller\Public;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicCategoriesController extends AbstractController
{
    #[Route('/categories', name: 'public_categories_list', methods: ['GET'])]
    public function listRecipes(CategoryRepository $categoryRepository) : Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('public/categories/list.html.twig', ['categories'=>$categories]);
    }

    #[Route(path:'category/{id}', name: 'public_category_show', requirements: ['id'=>'\d+'], methods: ['GET'] )]
    public function showRecipe (int $id, CategoryRepository $categoryRepository) : Response
    {
        $category = $categoryRepository->find($id);

        if(!$category) {
            $this->addFlash('warning', 'Catégorie inexistante');
            return $this->redirectToRoute('public_categories_list');
        }
        return $this->render('public/categories/showCategory.html.twig', ['category'=>$category]);
    }
}