<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Entity\Category;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Helper\Helper;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/recipe', name: 'admin_')]
class RecipeController extends AbstractController
{

    #[Route('/', name: 'app_recipe')]
    public function index(Request $request, RecipeRepository $recipeRepository, CategoryRepository $category, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // dd($em->getRepository(Recipe::class));
        $recipes = $recipeRepository->findDuration(45);

        // dd($recipesTotalDurations);

        return $this->render('admin/recipe/index.html.twig', [

            'recipes' => $recipes,
        ]);
    }

    #[Route('/new', name: 'app_recipe_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {

        // Création d'une nouvelle instance de Recipe
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement de la nouvelle recette
            $em->persist($recipe);
            $em->flush();

            $this->addFlash('success', "Une nouvelle recette a été crée");
            return $this->redirectToRoute('admin_app_recipe');
        }

        // Rend la vue 'recipe/new.html.twig' avec le formulaire pour créer une nouvelle recette
        return $this->render('admin/recipe/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['POST', 'GET'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $em, UploaderHelper $helper): Response
    {


        // recupere url image upload
        // dd($helper->asset($recipe, 'thumbnailFile'));

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            dd($recipe);
            $em->persist($recipe);
            $em->flush();

            $this->addFlash('warning', "La recette a bien été modifiée");
            return $this->redirectToRoute('admin_app_recipe');
        }

        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView() // Assurez-vous de créer la vue du formulaire
        ]);
    }


    #[Route('/{id}/delete', name: 'app_recipe_delete', methods: 'DELETE', requirements: ['id' => Requirement::DIGITS])]
    public function delete(RecipeRepository $recipeRepository, int $id,  EntityManagerInterface $em): Response
    {

        // Récupération des informations de la recette en utilisant l'identifiant
        $recipe =  $recipeRepository->find($id);

        // Vérification si la recette avec l'ID fourni existe
        if (!$recipe) {

            // Redirection vers une page d'erreur ou une page par défaut si la recette n'existe pas
            $this->addFlash('danger', "La recette n'existe pas");
            // return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()]);
            return $this->redirectToRoute('admin_app_recipe');
        } else {

            // Vérification si Id fourni dans l'URL correspond au slug de la recette
            if ($recipe->getId() == $id) {
                $em->remove($recipe);
                $em->flush();

                $this->addFlash('danger', "La recette à bien été supprimer");
                return $this->redirectToRoute('admin_app_recipe');
            }
        }

        return $this->redirectToRoute('admin_app_recipe');
    }
}
