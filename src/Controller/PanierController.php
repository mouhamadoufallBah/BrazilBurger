<?php

namespace App\Controller;

use App\Repository\BurgerRepository;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier.index')]
    public function index(SessionInterface $session, BurgerRepository $burgerRepository,MenuRepository $menuRepository): Response
    {
        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'burger' => $burgerRepository->find($id),
                'menu' => $menuRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;
        foreach ($panierWithData as $item)
        {

            $totalItem = $item['burger']->getPrix() * $item['quantity'];
            $total+= $totalItem;
        }

            return $this->render('panier/index.html.twig', [
                'items' => $panierWithData,
                'total' => $total
            ]);

    }

    /**
     * * @Route ("/panier/add/{id}", name="panier.add")
     * @return Response
     */
    public function add($id, SessionInterface $session):Response
    {

        $panier = $session->get('panier', []);

        if(!empty($panier[$id]))
        {
            $panier[$id]++;
        }else
        {
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute("panier.index");

    }

    /**
     * @Route("/panier/remove/{id}", name="panier.remove")
     */
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if(!empty($panier[$id]))
        {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('panier.index');
    }

}
