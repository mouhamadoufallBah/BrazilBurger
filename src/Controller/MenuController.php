<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @var MenuRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    public function __construct(MenuRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }



    /**
     * @Route ("/menu", name="menu.index")
     * @return Response
     */
    public function index(MenuRepository $repository, PaginatorInterface $paginator, Request $request):Response
    {

        $menu = $paginator->paginate(
            $this->repository->findAllMenuQuery(),
            $request->query->getInt('page','1'),
            '12'
        );


        return  $this->render('menu/index.html.twig', [
            'current_menu' => 'menus',
            'menus' => $menu
        ]);
    }

    /**
     * @Route ("/menu/{slug}-{id}", name="menu.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public  function  show(Menu $menu, string $slug) :Response
    {
        if($menu->getSlug() !== $slug)
        {
            return $this->redirectToRoute('menu.show', [
                'id' =>$menu->getId(),
                'slug'=>$menu->getSlug()
            ], 301);
        }
        return $this->render('menu/show.html.twig', [
            'menu' => $menu,
            'current_menu' => 'menus'
        ]);
    }

}
