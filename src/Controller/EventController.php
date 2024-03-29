<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EventController extends AbstractController
{
    #[Route('/', name: 'app_event')]
    public function index(EntityManagerInterface $em): Response
    {
        $events = $em->getRepository(Event::class)->findAll();

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/event/show/{slug}', name: 'app_event_show')]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/event/create', name: 'app_event_create')]
    public function create(EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $event = new Event();

        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()){
            $slug = $slugger->slug($event->getName()) . '-' . uniqid();
            $event->setSlug($slug);

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('app_event');
        }

        return $this->render('event/create.html.twig', [
            'eventForm' => $eventForm->createView(),
        ]);
    }

    #[Route('/event/edit/{id}', name: 'app_event_edit')]
    public function edit(EntityManagerInterface $em, Request $request, Event $event): Response
    {
        $editForm = $this->createForm(EventType::class, $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()){
            $em->flush();

            return $this->redirectToRoute('app_event');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'editForm' => $editForm->createView()
        ]);
    }

    #[Route('/event/delete/{id}', name: 'app_event_delete')]
    public function delete(EntityManagerInterface $em, Event $event, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('csrf'))){
            $em->remove($event);
            $em->flush();
        }

        return $this->redirectToRoute('app_event');
    }
}
