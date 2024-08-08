<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RecipeRepository $recipeRepository): Response
    {

        dump($recipeRepository->findWithDurationLowerThan(10));
        $recipes = $recipeRepository->findAll();
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
}
