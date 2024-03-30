<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/category', name: 'admin_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository, Request $request): Response
    {

        $category = $categoryRepository->findALl();

        return $this->render('admin/category/index.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/new', name: 'app_category_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash("success",  "La categorie " . $category->getName() . " à bien été ajouter");
            return $this->redirectToRoute('admin_app_category');
        }

        return $this->render('admin/category/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['POST', 'GET'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $request, Category $category, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($category);
            $em->persist($category);
            $em->flush();

            $this->addFlash('warning',  "La categorie " . $category->getName() . " à bien été modifier");
            return $this->redirectToRoute('admin_app_category');
        }
        return $this->render('admin/category/edit.html.twig', [
            
            'form' => $form,
            'category' => $category
        ]);
    }

    #[Route('/{id}/delete', name: 'app_category_delete', methods: ['DELETE'], requirements: ['id' =>  requirement::DIGITS])]
    public function delete(CategoryRepository $categoryRepository, int $id,  EntityManagerInterface $em): Response
    {

        $category = $categoryRepository->find($id);

        if (!$category) {
            $this->addFlash('warning', "La categorie n'existe pas");

            return $this->redirectToRoute("admin_app_category");
        } else {
            if ($category->getId() == $id) {
                $em->remove($category);
                $em->flush();

                $this->addFlash('danger', "La categorie " .  $category->getName() . " à bien été supprimer" );
                // dd($category);
            }
        }
        return $this->redirectToRoute('admin_app_category');
    }
}
