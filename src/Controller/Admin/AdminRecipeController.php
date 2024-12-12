<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\AdminRecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminRecipeController extends AbstractController
{


    #[Route('/admin/recipe/create', 'admin_create_recipe', methods: ['POST', 'GET'])]
    public function createRecipe(Request $request, EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag)
    {
        $recipe = new Recipe();

        $adminRecipeForm = $this->createForm(AdminRecipeType::class, $recipe);

        $adminRecipeForm->handleRequest($request);

        if ($adminRecipeForm->isSubmitted() && $adminRecipeForm->isValid()) {

            $recipeImage = $adminRecipeForm->get('image')->getData();

            if ($recipeImage) {

                $imageNewName = uniqid() . '.' . $recipeImage->guessExtension();

                $rootDir = $parameterBag->get('kernel.project_dir');
                $uploadsDir = $rootDir . '/public/assets/uploads';

                $recipeImage->move($uploadsDir, $imageNewName);

                $recipe->setImage($imageNewName);
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'Enregistrement recette confirmé');
        }

        $formView = $adminRecipeForm->createView();

        return $this->render('admin/recipe/create_recipe.html.twig', [
            'formView' => $formView,
        ]);
    }

    #[Route('/admin/recipes/list', 'admin_list_recipes', methods: ['GET'])]
    public function listRecipes(RecipeRepository $recipeRepository)
    {
        $recipes = $recipeRepository->findAll();

        return $this->render('admin/recipe/list_recipes.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/admin/recipes/{id}/delete', 'admin_delete_recipe', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function deleteRecipe(int $id, RecipeRepository $recipeRepository, EntityManagerInterface $entityManager)
    {

        $recipe = $recipeRepository->find($id);

        $entityManager->remove($recipe);
        $entityManager->flush();

        $this->addFlash('success', 'La recette a bien été envoyée dans la poubelle des recettes foireuses');

        return $this->redirectToRoute('admin_list_recipes');
    }

    #[Route('/admin/recipe/{id}/update', 'admin_update_recipe', methods: ['POST', 'GET'], requirements: ['id' => '\d+'])]
    public function updateRecipe(int $id, RecipeRepository $recipeRepository, EntityManagerInterface $entityManager, Request $request, ParameterBagInterface $parameterBag)
    {
        $recipe = $recipeRepository->find($id);

        $adminRecipeForm = $this->createForm(AdminRecipeType::class, $recipe);

        $adminRecipeForm->handleRequest($request);

        if ($adminRecipeForm->isSubmitted() && $adminRecipeForm->isValid()) {

            $recipeImage = $adminRecipeForm->get('image')->getData();

            if ($recipeImage) {

                $imageNewName = uniqid() . '.' . $recipeImage->guessExtension();

                $rootDir = $parameterBag->get('kernel.project_dir');

                $uploadsDir = $rootDir . '/public/assets/uploads';

                $recipeImage->move($uploadsDir, $imageNewName);

                $recipe->setImage($imageNewName);
            }

            $entityManager->persist($recipe);
            $entityManager->flush();
            $this->addFlash('success', 'Modification recette confirmée');
        }

        $formView = $adminRecipeForm->createView();

        return $this->render('admin/recipe/update_recipe.html.twig', [
            'formView' => $formView,
            'recipe' => $recipe
        ]);

    }
}