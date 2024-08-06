<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recipe')]
class RecipeController extends AbstractController
{

    public function __construct(private readonly RecipeRepository $recipeRepository) {

    }

    #[Route('/', name: 'app_recipe_index')]
    public function index(): Response
    {
        $recipes = $this->recipeRepository->findAll();

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
    
    #[Route('/{slug}-{id}', name: 'app_recipe_show', requirements: ['id' => '\d+', 'slug' => '[a-zA-Z0-9-]+'])]
    public function show(string $slug, int $id): Response
    {
        $recipe = $this->recipeRepository->find($id);

        if (!$recipe || $recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('app_recipe_show', [
                'id' => $recipe ? $recipe->getId() : $id,
                'slug' => $recipe ? $recipe->getSlug() : $slug
            ], 301);
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}
