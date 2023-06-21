<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Picture;
use App\Entity\Location;
use App\Form\LocationType;
use App\Service\FileUploader;
use App\Repository\PictureRepository;
use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/location')]
class LocationController extends AbstractController
{
    #[Route('/', name: 'app_location_index', methods: ['GET'])]
    public function index(LocationRepository $locationRepository, PictureRepository $pictureRepository): Response
    {
        return $this->render('location/index.html.twig', [
            'locations' => $locationRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_location_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LocationRepository $locationRepository, PictureRepository $pictureRepository, FileUploader $fileUploader): Response
    {
        $location = new Location();
        $picture = new Picture();
        $location->addPicture($picture);
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request->files->get('location')["pictures"][0]["file"]->getClientOriginalName());
            $pictures = $location->getPictures();
            // dump($pictures);
            // dump($request->files->get('location')["pictures"]);
            foreach ($pictures as $key => $pic){
                $pictureFileName = $fileUploader->upload($request->files->get('location')["pictures"][$key]["file"], 'locations');
                $pic->setFile($pictureFileName);
            }
            // $pictures[0]->setFile();
            // dump($location);
            // dd($pictures);
            $location->setUser($this->getUser());
            $location->setCreatedAt(new DateTimeImmutable());
            $location->setModifiedAt(new DateTimeImmutable());
            $locationRepository->save($location, true);
            $pictureRepository->save($picture, true);

            return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/new.html.twig', [
            'location' => $location,
            'picture' => $picture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_location_show', methods: ['GET'])]
    public function show(Location $location): Response
    {
        return $this->render('location/show.html.twig', [
            'location' => $location,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_location_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Location $location, LocationRepository $locationRepository): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $locationRepository->save($location, true);

            return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('location/edit.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_location_delete', methods: ['POST'])]
    public function delete(Request $request, Location $location, LocationRepository $locationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$location->getId(), $request->request->get('_token'))) {
            $locationRepository->remove($location, true);
        }

        return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
    }
}
