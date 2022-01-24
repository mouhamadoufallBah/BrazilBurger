<?php

namespace App\Controller\Gestionnaire;

use App\Entity\Burger;
use App\Form\BurgerType;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class GestionnaireBurgerController extends AbstractController
{
    /**
     * @var BurgerRepository
     */
    private BurgerRepository $repository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    public function __construct(BurgerRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;

        $this->em = $em;
    }

    /**
     * @Route ("/gestionnaire", name="gestionnaire.burger.index")
     * @return Response
     */
    public  function index(): Response
    {
        $burgers = $this->repository->findAll();
        return $this->render('gestionnaire/burger/index.html.twig', compact('burgers'));
    }

    /**
     * @Route ("/gestionnaire/burger/create", name="gestionnaire.burger.new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, SluggerInterface $slugger):Response
    {
        $burger = new Burger();
        $form = $this->createForm(BurgerType::class, $burger);
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
                        $this->getParameter('burger_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $burger->setImage($newFilename);
            }
            $this->em->persist($burger);
            $this->em->flush();
            $this->addFlash('success','Ajout avec succés');
            return $this->redirectToRoute('gestionnaire.burger.index');
        }
        return $this->render('gestionnaire/burger/new.html.twig',[
            'burger' => $burger,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route ("/gestionnaire/burger/edit/{id}", name="gestionnaire.burger.edit", methods={"GET|POST"})
     * @param Burger $burger
     * @param Request $request
     * @return Response
     */
    public function edit(Burger $burger, Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(BurgerType::class, $burger);
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
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('burger_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $burger->setImage($newFilename);
            }
            $this->em->flush();
            $this->addFlash('success','Modication avec succés');
            return $this->redirectToRoute('gestionnaire.burger.index');
        }

        return $this->render('gestionnaire/burger/edit.html.twig',[
            'burger' => $burger,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/gestionnaire/burger/delete/{id}", name="gestionnaire.burger.delete", methods={"DELETE"})
     * @param Burger $burger
     * @param Request $request
     * @return Response
     */
    public function delete(Burger $burger, Request $request):Response
    {
        if($this->isCsrfTokenValid('delete' . $burger->getId(), $request->get('_token')))
        {
            $this->em->remove($burger);
            $this->em->flush();
            $this->addFlash('success','Supression avec succés');
        }

        return $this->redirectToRoute('gestionnaire.burger.index');

    }
}

