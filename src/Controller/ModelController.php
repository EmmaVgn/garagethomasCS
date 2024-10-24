<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Repository\ModelRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModelController extends AbstractController
{
    
    protected $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }
    
    #[Route('/brand/{slug}', name: 'model', priority: -1)]
    public function model($slug): Response
    {
        $model = $this->modelRepository->findOneBy(['slug' => $slug]);
    
        if (!$model) {
            throw $this->createNotFoundException("La marque demandée n'existe pas");
        }
    
        // Récupération des produits associés à la marque
        $products = $model->getProducts();
    
        return $this->render('product/model.html.twig', [
            'slug' => $slug,
            'model' => $model,
            'products' => $products,
        ]);
    }
    

    public function renderMenuList(): Response
    {
        // 1. Aller chercher les marques dans la BDD
        $models = $this->modelRepository->findAll();
        // 2. Renvoyer le rendu HTML sous la forme d'une Response ($this->render)
        return $this->render('model/_menu.html.twig', [
            'models' => $models,
        ]);
    }



}
