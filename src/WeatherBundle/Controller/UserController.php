<?php

namespace WeatherBundle\Controller;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WeatherBundle\Entity\Town;
use WeatherBundle\Entity\User;
use WeatherBundle\Entity\UsersTowns;
use WeatherBundle\Form\UserType;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $passwordHash =
                $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordHash);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('users/register.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile()
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($this->getUser());

        return $this->render('users/profile.html.twig',
            [
                'user' => $user
            ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     * @throws Exception
     */
    public function logout()
    {
        throw new Exception('Logout failed!');
    }


    /**
     * @param Request $request
     * @Route("/favoriteTowns", name="favorite_towns")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function myFavoriteTowns(Request $request)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();
        $userId = $currentUser->getId();

        $usersTownsRepository = $this->getDoctrine()->getRepository(UsersTowns::class);

        $myFavoriteTowns = $usersTownsRepository->myTowns($userId)->execute();

        return $this->render('towns/myFavoriteTowns.html.twig',
            [
                'myFavoriteTowns' => $myFavoriteTowns
            ]);
    }

    /**
     * @Route("/addFavoriteTown", name="add_favorite_towns")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return Response
     */
    public function addFavoriteTown(Request $request)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();
        $userId = $currentUser->getId();
        $townId = (int)$request->request->get('town_id');
        $usersTowns = new UsersTowns();
        $usersTowns->setTownId($townId);
        $usersTowns->setUserId($userId);

        $usersTownsRepository = $this->getDoctrine()->getRepository(UsersTowns::class);

        $usersTownsData = $usersTownsRepository->checkUserTown($userId, $townId)->execute();

        if(!$usersTownsData) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($usersTowns);
            $em->flush();
        }

        $response = [];

        $json = json_encode($response);
        return Response::create($json);
    }

    /**
     * @Route("/removeTown", name="remove_town")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return Response
     */
    public function removeTown(Request $request)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();
        $userId = $currentUser->getId();
        $townId = (int)$request->request->get('town_id');

        $usersTownsRepository = $this->getDoctrine()->getRepository(UsersTowns::class);
        $usersTownsRepository->removeUserTown($userId, $townId);

        $myFavoriteTowns = $usersTownsRepository->myTowns($userId)->execute();

        $towns = [];
        $index = 0;
        foreach ($myFavoriteTowns as $town){
            /**
             * @var Town $town
             */
            $towns[$index]['id'] = $town->getId();
            $towns[$index]['name'] = $town->getName();
            $index++;
        }
        $json = json_encode($towns);

        return Response::create($json);
    }
}
