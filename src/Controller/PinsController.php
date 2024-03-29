<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class PinsController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_home")
     * @param PinRepository $pinRepository
     * @return Response
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods="GET")
     * @param Pin $pin
     * @param PinRepository $pinRepository
     * @return Response
     */
    public function show(Pin $pin, PinRepository $pinRepository)
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }


    /**
     * @Route("/pins/create", name="app_pins_create")
     * @Security("is_granted('ROLE_USER') && user.isVerified()")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $pin = new Pin;
        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pin->setUser($this->getUser());
            $this->em->persist($pin);
            $this->em->flush();
            $this->addFlash('success','Pin successfully created !!');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET","PUT"})
     * @IsGranted("PIN_EDIT",subject="pin")
     * @param Pin $pin
     * @param Request $request
     * @return Response
     */
    public function edit(Pin $pin, Request $request): Response
    {
        //   $pin = $pinRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(PinType::class, $pin, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success','Pin successfully updated !!');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_delete", methods={"DELETE"})
     * @param Pin $pin
     * @param Request $request
     * @return Response
     */
    public function delete(Pin $pin, Request $request): Response
    {
        $this->denyAccessUnlessGranted('PIN_DELETE', $pin);

        if ($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $request->request->get('crsf_token'))) {
            $this->em->remove($pin);
            $this->em->flush();
            $this->addFlash('info','Pin successfully deleted !!');
        }
        return $this->redirectToRoute('app_home');
    }
}