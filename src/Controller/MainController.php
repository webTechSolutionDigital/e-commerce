<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(Request $request, RecipeRepository $recipeRepository, UserPasswordHasherInterface $password, EntityManagerInterface $em): Response
    {
        // $user = new User();
        // $user->setUsername("John");
        // $user->setEmail("John@example.com");
        // $user->setPassword($password->hashPassword($user, 'password'));
        // $user->setRoles(['ROLE_USER']);

        // $em->persist($user);
        // $em->flush();


        

        $recipes = $recipeRepository->findAll();
        // dd($recipe);

        return $this->render('main/index.html.twig', [

            'recipes' => $recipes,
        ]);
    }


    #[Route('/main/{slug}-{id}', name: 'app_main_show', requirements: ['slug' => '[a-z0-9-]+', 'id' => '\d+'])]
    public function show(Request $request, RecipeRepository $recipeRepository, int $id, string $slug): Response
    {
        // Récupération des informations de la recette en utilisant l'identifiant
        $recipeAll =  $recipeRepository->find($id);

        // Vérification si la recette avec l'ID fourni existe
        if (!$recipeAll) {
            // Redirection vers une page d'erreur ou une page par défaut si la recette n'existe pas
            return $this->redirectToRoute('app_recipe');
        } else {
            // Vérification si le slug fourni dans l'URL correspond au slug de la recette
            if ($recipeAll->getSlug() != $slug) {
                // Si les slugs ne correspondent pas, l'utilisateur est redirigé vers l'URL correcte pour la recette
                return $this->redirectToRoute('app_recipe_show', ['slug' => $recipeAll->getSlug(), 'id' => $recipeAll->getId()]);
            }
        }

        // Rend la vue 'recipe/show.html.twig' avec les informations de la recette pour l'affichage
        return $this->render('main/show.html.twig', [
            'recipeAll' => $recipeAll,
        ]);
    }
}
