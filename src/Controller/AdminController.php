<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryFormType;
use App\Form\ProductFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_ADMIN', message: "Vous n'avez pas le droit d'accéder à cette ressource.")]
class AdminController extends AbstractController {

    #[Route('/admin/product/create', name: 'admin_product_create')]
    public function createProduct(Request $request, SluggerInterface $slugger, EntityManagerInterface $manager): Response {

        $product = new Product;

        $form = $this -> createForm(ProductFormType::class, $product);

        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
            $product -> setSlug(strtolower($slugger -> slug($product -> getName())));

            $manager -> persist($product);
            $manager -> flush();

            return $this -> redirectToRoute("product_show", [
               'category_slug' => $product -> getCategory() -> getSlug(),
               'product_slug' => $product -> getSlug()
            ]);
        }

        return $this -> render('admin/createProduct.html.twig', [
            'formView' => $form -> createView()
        ]);
    }

    #[Route('/admin/product/{id}/edit', name: "admin_product_edit")]
    public function editProduct(ProductRepository $productRepository, Request $request, EntityManagerInterface $manager, $id): Response {

        $product = $productRepository -> find($id);

        if (!$product)
            throw new NotFoundHttpException("Le produit demandé est introuvable.");

        $form = $this -> createForm(ProductFormType::class, $product);

        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
            $manager -> flush();
            return $this -> redirectToRoute("product_show", [
                'category_slug' => $product -> getCategory() -> getSlug(),
                'product_slug' => $product -> getSlug()
            ]);
        }

        return $this -> render('admin/editProduct.html.twig', [
            'product' => $product,
            'formView' => $form -> createView(),
        ]);
    }

    #[Route('/admin/category/create', name: 'app_category_create')]
    public function createCategory(Request $request, SluggerInterface $slugger, EntityManagerInterface $manager): Response {

        $category = new Category;

        $form = $this -> createForm(CategoryFormType::class, $category);

        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
            $category -> setSlug(strtolower($slugger -> slug($category -> getName())));

            $manager -> persist($category);
            $manager -> flush();

            return $this -> redirectToRoute('app_category_show', ['slug' => $category -> getSlug()]);
        }

        return $this -> render('admin/createCategory.html.twig', [
            'categoryView' => $form -> createView(),
        ]);
    }

    #[Route('/admin/category/{id}/edit')]
    public function editCategory(SluggerInterface $slugger, Request $request, CategoryRepository $repository, EntityManagerInterface $manager, $id):  Response {

        $category = $repository -> find($id);

        if (!$category)
            throw new NotFoundHttpException("La category demandé est introuvable.");

        $this -> denyAccessUnlessGranted('CAN_EDIT', $category, "Vous n'êtes pas le propriétaire de cette catégorie!");


        $form = $this -> createForm(CategoryFormType::class, $category);

        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {

            $category -> setSlug(strtolower($slugger -> slug($category -> getName())));

            $manager -> flush();

            return $this -> redirectToRoute('app_category_show', [
                'slug' => $category -> getSlug()
            ]);
        }

        return $this -> render('admin/editCategory.html.twig', [
            'category' => $category,
            'categoryView' => $form -> createView(),
        ]);
    }
}
