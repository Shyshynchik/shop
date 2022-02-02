<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProductFromType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="main_homepage")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $productList = $entityManager->getRepository(Product::class)->findAll();
        return $this->render('main/default/index.html.twig');
    }

    /**
     * @Route("/edit-product/{id}", methods="GET|post", name="product_edit", requirements={"id"="\d+"})
     * @Route("/add-product", methods="GET|post", name="product_add")
     */
    public function editProduct(Request $request, int $id = null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($id) {
            $product = $productList = $entityManager->getRepository(Product::class)->find($id);
        } else {
            $product = new Product();
        }
        $form = $this->createForm(EditProductFromType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }
        return $this->render('main/default/edit_product.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
