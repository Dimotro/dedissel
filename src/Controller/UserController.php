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
use App\Entity\ObjectProductPeriod;
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
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// De UserController is bedoeld voor alle routes die beschikbaar moeten zijn voor klant-gebruikers
class UserController extends Controller
{

//    public function viewOrder($order)
//    {
//        $order = $this->getDoctrine()->getRepository(KlantOrder::class)->find($order);
//        if (!$order) {
//            $this->redirectToRoute('user_overview_orders');
//        } else {
//            return $this->render('user/view-order.html.twig', array(
//                'order' => $order
//            ));
//        }
//    }

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

    public function orderSuccess()
    {
        return $this->render('user/order-success.html.twig');
    }

    public function overviewOrder(UserInterface $user)
    {
        $user = $this->getDoctrine()->getRepository(Klantaccount::class)->find($user);
        $discount = $this->getDoctrine()->getRepository(ActiePeriode::class)->getCurrentDiscount();

        $bestellingen = $user->getBestellings();
        $prijsArr = array();

        foreach ($bestellingen as $key => $order) {
            $object = $order->getObjectPeriod()->getObjectProduct();
            $objectPrijs = $object->getPrijs();
            $datumUit = $order->getObjectPeriod()->getDatumUit();
            $datumTerug = $order->getObjectPeriod()->getDatumTerug();
            $difference = $datumUit->diff($datumTerug)->days;
            $prijs = $object->getPrijs();

            if ($discount && $order->getObjectPeriod()->getDatumUit() > $discount[0]->getActiePeriodeStart() && $order->getObjectPeriod()->getDatumTerug() < $discount[0]->getActiePeriodeEinde() ){
                $discountDay = $discount[0]->getActiePercentage();
                $totalPrice = ($prijs * $discountDay) * $difference;
            } else {
                $totalPrice = $difference * $prijs;
            }
            $prijsArr[$object->getId()] = $totalPrice;
        }
        return $this->render('user/overview-order.html.twig', array(
            'orders' => $bestellingen,
            'prijsArr' => $prijsArr
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
        $order->setOrdernummer(random_int(1, 900000000));
        $orderPeriod = new ObjectProductPeriod();
        $orderPeriod->setKlantOrder($order);
        $orderPeriod->setObjectProduct($object);
        $order->setObjectPeriod($orderPeriod);

        $orderForm = $this->createForm(AddOrderType::class, $order);
        $orderForm->handleRequest($request);

        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            $periodValid = $this->getDoctrine()->getRepository(ObjectProductPeriod::class)->isAvailibleAt($objectId, $orderPeriod->getDatumUit(), $orderPeriod->getDatumTerug());
            if(!$periodValid){
                $this->addFlash('error', 'Object is al gereserveerd in dit tijdvlak');

                return $this->render('user/new-order.html.twig', array(
                        'object' => $order->getObjectPeriod()->getObjectProduct(),
                        'klantaccount' => $user,
                        'kortingMultiplier' => $kortingMultiplier,
                        'orderForm' => $orderForm->createView()
                    )
                );
            }
            $rijbewijsBenodigd = $object->getSpecificatie()->getRijbewijsBenodigd();
            $rijbewijsArr = explode(',', $rijbewijsBenodigd);
            foreach ($rijbewijsArr as $key => $value){
                $klantHasRijbewijs = $user->getKlantPersoonlijkeGegevens()->getRijbewijs()->getRijbewijsType()[$value];
                if($klantHasRijbewijs){
                    $rijbewijsArr[$key] = true;
                } else{
                    $rijbewijsArr[$key] = false;
                }
            }
            foreach($rijbewijsArr as $type){
                if(!$type){
                    $this->addFlash('error', 'Uw bezit een van de benodigde rijbewijzen niet');
                    $this->redirectToRoute('user_add_order', array('objectId' => $object->getId()));
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
            return $this->redirectToRoute('user_order_success');
        }

        return $this->render('user/new-order.html.twig', array(
                'object' => $order->getObjectPeriod()->getObjectProduct(),
                'klantaccount' => $user,
                'kortingMultiplier' => $kortingMultiplier,
                'orderForm' => $orderForm->createView()
            )
        );
    }

    public function disableUser(UserInterface $user){
        // Haal entity manager op, beschikbaar gesteld door 'extends Controller'
        $em = $this->getDoctrine()->getManager();
        // Haal repository op die bij het entiteit hoort
        $user = $em->getRepository(Klantaccount::class)
            // Zoek op primaire sleutel (id) dat als route parameter meegestuurd wordt naar de controller
            ->find($user);
        // Zet isActive op false
        $user->setIsActive(false);
        // Zet gebruiker tijdelijk in opslag om later databasebewerkingen uit te voeren
        $em->persist($user);
        // Voer alle database bewerkingen die persist zijn door naar de database
        $em->flush();
        // Laat Klanten overzicht pagina zien met de zichtbare verandering
        return $this->redirectToRoute('logout');
    }

    public function addOrderWithOptions($optionsArr){

    }

    public function deleteOrder($order)
    {
        $order = $this->getDoctrine()->getRepository(KlantOrder::class)->find($order);
        $em = $this->getDoctrine()->getManager();
        if ( $order->getOrderDatum()->diff(new \DateTime('now'))->days > 61 ){
               $em->remove($order);
               $em->flush();
               $this->addFlash('success', 'Uw order is succesvol verwijderd');
               return $this->redirectToRoute('user_overview_orders');
        } else{
            $this->addFlash('error', 'Alleen orders die 2 maanden geleden of langer geplaatst zijn kunnen worden verwijderd');
            return $this->redirectToRoute('user_overview_orders');
        }
    }
}