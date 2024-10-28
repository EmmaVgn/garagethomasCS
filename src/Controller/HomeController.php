<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], [], 6);

        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/faible-kilometre', name: 'product_low_mileage')]
    public function lowMileage(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findByLowMileage();

        return $this->render('product/_low_mileage.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/petits-prix', name: 'product_low_price')]
    public function lowPrice(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findByLowPrice();

        return $this->render('product/_low_price.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/vehicules-directions', name: 'product_direction')]
    public function direction(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findByDirection();

        return $this->render('product/_direction.html.twig', [
            'products' => $products
        ]);
    }
}
