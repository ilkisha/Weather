<?php

namespace WeatherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WeatherBundle\Entity\Town;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="weather_index")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $townRepository = $this->getDoctrine()->getRepository(Town::class);
        $allTowns = $townRepository->findAll();

        $user = $this->getUser();


        return $this->render('default/index.html.twig',
            [
                'allTowns' => $allTowns,
                'user' => $user
            ]);
    }
}
