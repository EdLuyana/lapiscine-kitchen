<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\AdminRecipeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminRecipeController extends AbstractController
{


    #[Route('/admin/recipe/create', 'admin_create_recipe', methods: ['GET'])]
    public function createRecipe(Request $request, EntityManagerInterface $entityManager)
    {
        $recipe = new Recipe();

        $adminRecipeForm = $this->createForm(AdminRecipeType::class, $recipe);

        $adminRecipeForm->handleRequest($request);

        if ($adminRecipeForm->isSubmitted()) {
            $this->addFlash('success', 'Enregistrement recette confirmé');
            $entityManager->persist($recipe);
            $entityManager->flush();
        }

        $formView = $adminRecipeForm->createView();

        return $this->render('admin/recipe/create.html.twig', [
            'formView' => $formView,
        ]);
    }

}