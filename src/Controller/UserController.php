<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 20/01/2018
 * Time: 13:56
 */

namespace App\Controller;

use App\Entity\ActiePeriode;
use App\Entity\Klantaccount;
use App\Entity\Klantadres;
use App\Entity\Klantgegeven;
use App\Entity\KlantOrder;
use App\Entity\ObjectProduct;
use App\Entity\Rijbewijs;
use App\Form\AddObjectType;
use App\Form\AddOrderType;
use App\Form\AdresType;
use App\Form\EditCredentialsType;
use App\Form\DetailsType;
use App\Form\EditObjectDateType;
use App\Form\OrderDetailsType;
use App\Form\RijbewijsType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// De UserController is bedoeld voor alle routes die beschikbaar moeten zijn voor klant-gebruikers
class UserController extends Controller
{

    public function viewOrder($order)
    {

    }

    public function viewUserDetails(UserInterface $user)
    {
        $klant = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);

        $klantgegevens = $user->getKlantPersoonlijkeGegevens();
        if (!$klantgegevens) {
            $klantgegevens = new Klantgegeven();
        }

        $rijbewijs = $klantgegevens->getRijbewijs();
        if ( !$rijbewijs ){
            $rijbewijs = new Rijbewijs();
        }

        $klantNAW = $klantgegevens->getKlantNAW();
        if ( !$klantNAW ) {
            $klantNAW = new Klantadres();
        }

        return $this->render('user/user-overview.html.twig', array(
            'klant' => $klant,
            'klantGegeven' => $klantgegevens,
            'klantNAW' => $klantNAW,
            'rijbewijs' => $rijbewijs
        ));
    }

    public function editUserCredentials(UserInterface $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $klant = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);
        $form  = $this->createForm(EditCredentialsType::class, $klant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($klant, $klant->getPlainPassword());
            $klant->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($klant);
            $em->flush();
            return $this->redirectToRoute('user_view_details');
        }
        return $this->render('user/edit-user-credentials.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editUserDetails(UserInterface $user, Request $request)
    {
        $klant = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);
        $details = $klant->getKlantPersoonlijkeGegevens();
        if( !$details ){
            $details = new Klantgegeven();
            $klant->setKlantPersoonlijkeGegevens($details);
        }
        $adres = $details->getKlantNAW();
        $detailsForm  = $this->createForm( DetailsType::class, $details, array(
            'attr' => array(
                'class' => 'default_form'
            )
        ));
        if (!$adres){
            $adres = new Klantadres();
            $details->setKlantNAW($adres);
        }
        $adresForm = $this->createForm( AdresType::class, $adres);
        $detailsForm->handleRequest($request);
        $adresForm->handleRequest($request);
        if ($detailsForm->isSubmitted() && $detailsForm->isValid() && $adresForm->isSubmitted() && $adresForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adres);
            $em->persist($details);
            $em->flush();
            return $this->redirectToRoute('user_view_details');
        };
        return $this->render('user/edit-user-details.html.twig', array(
            'detailsForm' => $detailsForm->createView(),
            'adresForm' => $adresForm->createView()
        ));
    }

    public function editUserLicense(Request $request, UserInterface $user)
    {
        $klant = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);
        $klantGegevens = $klant->getKlantPersoonlijkeGegevens();
        $rijbewijs = $klantGegevens->getRijbewijs();
        if (!$rijbewijs) {
            $rijbewijs = new Rijbewijs();
        }
        $form = $this->createForm(RijbewijsType::class, $rijbewijs);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if( $rijbewijs->getRijbewijsGecontroleerd() ){
                $rijbewijs->setRijbewijsGecontroleerd(false);
            }
            $klantGegevens->setRijbewijs($rijbewijs);
            $em = $this->getDoctrine()->getManager();
            $em->persist($klantGegevens);
            $em->persist($rijbewijs);
            $em->flush();
            return $this->redirectToRoute('user_view_details');
        }
        return $this->render('user/edit-user-rijbewijs.html.twig', array(
            'form' => $form->createView(),
            ''
        ));
    }

    public function orderSuccess($objectId, $optionsArr)
    {
        return $this->render('user/order-success.html.twig');
    }

    public function overviewOrder(UserInterface $user)
    {
        $user = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);
        $bestellingen = $user->getBestellings();
        return $this->render('user/overview-order.html.twig', array(
            'orders' => $bestellingen
        ));
    }

    public function addOrder($objectId, UserInterface $user, Request $request)
    {
        // Gegevens ophalen
        $discount = $this->getDoctrine()->getRepository(ActiePeriode::class)->getCurrentDiscount();
        $object = $this->getDoctrine()->getRepository(ObjectProduct::class)->find($objectId);
        $user = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);
        $kortingMultiplier = 1;
        if($discount){
            $kortingMultiplier = $discount[0]->getActiePercentage() ? (1 - $discount[0]->getActiePercentage()) : 1;
        }

        $order = new KlantOrder();
        $order->setKlant($user);
        $order->setObjectProduct($object);

        $orderForm = $this->createForm(AddOrderType::class, $order);
        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
            return $this->redirectToRoute('user_order_success');
        }

        return $this->render('user/new-order.html.twig', array(
                'object' => $order->getObjectProduct(),
                'klantaccount' => $user,
                'kortingMultiplier' => $kortingMultiplier,
                'orderForm' => $orderForm->createView()
            )
        );
//        $userDetails = $user->getKlantPersoonlijkeGegevens();
//        if (!$userDetails) {
//            $userDetails = new Klantgegeven();
//            $userNAW = new Klantadres();
//            $rijbewijs = new Rijbewijs();
//        } else {
//            $userNAW = $userDetails->getKlantNAW();
//            $rijbewijs = $userDetails->getRijbewijs();
//        }
//
//        $userInfoForm = $this->createForm(OrderDetailsType::class, $userDetails);
//        $adresForm = $this->createForm(AdresType::class, $userNAW);
//        $rijbewijsForm = $this->createForm(RijbewijsType::class, $rijbewijs);
//
//
//
//        $tempObject = new ObjectProduct();
//        $tempObject->setObjDatumTerug(new \DateTime('now + 1 day'));
//        $tempObject->setObjDatumUit(new \DateTime('now'));
//
//
//
//        $form = $this->createForm(EditObjectDateType::class, $tempObject);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid() && $adresForm->isSubmitted() && $adresForm->isValid() && $userInfoForm->isSubmitted() && $userInfoForm->isValid()) {
//            return new Response('YAY');
//        } else {
//            return $this->render('user/new-order.html.twig', array(
//                    'object' => $object,
//                    'klantaccount' => $user,
//                    'form' => $form->createView(),
//                    'kortingMultiplier' => $kortingMultiplier,
//                    'userInfoForm' => $userInfoForm->createView(),
//                    'rijbewijsForm' => $rijbewijsForm->createView(),
//                    'adresForm' => $adresForm->createView()
//                )
//            );
//        }
    }

    public function addOrderWithOptions($optionsArr){

    }
}