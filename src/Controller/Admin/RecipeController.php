<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/admin/recette', name: 'admin_recipe_')]
class RecipeController extends AbstractController
{
    public function __construct(private readonly RecipeRepository $recipeRepository) {

    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $recipes = $this->recipeRepository->findAll();

        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
    
    #[Route('/{slug}-{id}', name: 'show', requirements: ['id' => Requirement::DIGITS, 'slug' => '[a-zA-Z0-9\-\p{L}]+'])]
    public function show(string $slug, int $id): Response
    {
        $recipe = $this->recipeRepository->find($id);

        if (!$recipe || $recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('app_recipe_show', [
                'id' => $recipe ? $recipe->getId() : $id,
                'slug' => $recipe ? $recipe->getSlug() : $slug
            ], 301);
        }

        return $this->render('admin/recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/ajout',  name: 'new', methods: ['GET','POST'])]
    public function new(Request $request,  EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success','La recette a bien été ajoutée.');

            return $this->redirectToRoute('admin_recipe_show', [
                'id' => $recipe->getId(),
                'slug' => $recipe->getSlug()
            ], 301);
        }


        return $this->render('admin/recipe/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => Requirement::DIGITS, 'slug' => '[a-zA-Z0-9-]+'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success','La recette a bien été mise à jour.');

            return $this->redirectToRoute('admin_recipe_show', [
                'id' => $recipe->getId(),
                'slug' => $recipe->getSlug()
            ], 301);
        }

        return $this->render('admin/recipe/edit.html.twig', [
            'form' => $form,
            'recipe' => $recipe
        ]);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(Recipe $recipe,EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success','La recette a bien été supprimée.');

        return $this->redirectToRoute('admin_recipe_index', [],301);
    }
}
