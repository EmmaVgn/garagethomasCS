<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Data\SearchData;
use App\Form\SearchFormType;
use App\Form\CommentFormType;
use App\Service\SendMailService;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ProductRepository $productRepository, CommentRepository $commentRepository): Response
    {
        $products = $productRepository->findBy([], [], 6);

        $comments = $commentRepository->findBy(['isValid' => true], ['createdAt' => 'DESC']);
        $averageRating = $commentRepository->averageRating();

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'comments' => $comments,
            'averageRating' => $averageRating
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

    #[Route('/search', name: 'search')]
    public function search(Request $request, ProductRepository $productRepository): Response
    {
        $searchData = new SearchData();
        $searchForm = $this->createForm(SearchFormType::class, $searchData);
        $searchForm->handleRequest($request);

        $vehicles = $productRepository->findSearch($searchData);

        return $this->render('shared/_results.html.twig', [
            'searchForm' => $searchForm->createView(),
            'vehicles' => $vehicles,
        ]);
    }

    #[Route('/donner-avis', name: 'home_notice')]
    public function notice(Request $request, EntityManagerInterface $em, SendMailService $mail): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setFullname(ucwords($form->get('fullname')->getData()));
            $em->persist($comment);
            $em->flush();
            $mail->sendMail(
                'no-reply@monsite.net',
                'Demande de contact',
                'contact@monsite.net',
                'Nouveau commentaire sur le site',
                'comment',
                []
            );
            $this->addFlash('success', 'Votre avis a bien été envoyé, il sera publié après validation !');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('home/notice.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
