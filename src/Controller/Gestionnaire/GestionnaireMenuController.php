<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class GestionnaireMenuController extends AbstractController
{
    /**
     * @var MenuRepository
     */
    private MenuRepository $repository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    public function __construct(MenuRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;

        $this->em = $em;
    }

    /**
     * @Route ("/gestionnaire/menu", name="gestionnaire.menu.index")
     * @return Response
     */
    public  function index(): Response
    {
        $menus = $this->repository->findAll();
        return $this->render('gestionnaire/menu/index.html.twig', compact('menus'));
    }

    /**
     * @Route ("/gestionnaire/menu/create", name="gestionnaire.menu.new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, SluggerInterface $slugger):Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('menu_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $menu->setImage($newFilename);
            }

            $this->em->persist($menu);
            $this->em->flush();
            $this->addFlash('success','Ajout avec succés');
            return $this->redirectToRoute('gestionnaire.menu.index');
        }
        return $this->render('gestionnaire/menu/new.html.twig',[
            'menu' => $menu,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route ("/gestionnaire/menu/edit/{id}", name="gestionnaire.menu.edit", methods={"GET|POST"})
     * @param Menu $menu
     * @param Request $request
     * @return Response
     */
    public function edit(Menu $menu, Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('menu_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $menu->setImage($newFilename);
            }
            $this->em->flush();
            $this->addFlash('success','Modication avec succés');
            return $this->redirectToRoute('gestionnaire.menu.index');
        }

        return $this->render('gestionnaire/menu/edit.html.twig',[
            'menu' => $menu,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/gestionnaire/menu/delete/{id}", name="gestionnaire.menu.delete", methods={"DELETE"})
     * @param Menu $menu
     * @param Request $request
     * @return Response
     */
    public function delete(Menu $menu, Request $request):Response
    {
        if($this->isCsrfTokenValid('delete' . $menu->getId(), $request->get('_token')))
        {
            $this->em->remove($menu);
            $this->em->flush();
            $this->addFlash('success','Supression avec succés');
        }

        return $this->redirectToRoute('gestionnaire.menu.index');

    }
}
