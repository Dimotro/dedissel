<?php

namespace App\Controller;

use App\Entity\ActiePeriode;
use App\Entity\Klantaccount;
use App\Entity\KlantOrder;
use App\Entity\ObjectProduct;
use App\Entity\OptieProduct;
use App\Entity\Specificatie;
use App\Form\ActiePeriodeType;
use App\Form\AddObjectType;
use App\Form\EditObjectType;
use App\Form\KlantAccountType;
use App\Form\OptieType;
use App\Form\SpecificatieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// De AdminController is bedoeld voor alle routes die beschikbaar moeten zijn voor beheerder-gebruikers
class AdminController extends Controller
{
    // Controller voor toevoegen van objecten
    public function addObject(Request $request)
    {
        // Nieuwe instance van ObjectProduct
        $objectProduct = new ObjectProduct();
        // Stel het formulier samen op basis van ObjectType
        $objectForm = $this->createForm(AddObjectType::class, $objectProduct);
        // Handel POST requests naar deze route af
        $objectForm->handleRequest($request);
        // Check of gegevens voldoen aan eisen voordat database-calls worden gedaan
        if ($objectForm->isSubmitted() && $objectForm->isValid()) {
            // Sla de binaire inhoud van de foto op als een string
            $files = $objectProduct->getFotos();
            $fotosArr = array();

            for ($i = 0; $i <= (count($files) - 1); $i++) {
                // Genereer een unieke bestandsnaam en voed toe aan array
                $fileName = md5(uniqid()) . '.' . $files[$i]->guessExtension();
                array_push(
                    $fotosArr,
                    $fileName
                );
                // Maak een bestand aan op het product_images_location parameter aangegeven in congig/services.yaml met de unieke bestandsnaam
                $files[$i]->move(
                    $this->getParameter('product_images_location'),
                    $fileName
                );
            }
            $objectProduct->setFotos($fotosArr);
            // Haal entietiet manager op
            $em = $this->getDoctrine()->getManager();
            // Sla de nieuwe instance van ObjectProduct op in tijdelijke opslag
            $em->persist($objectProduct);
            // Voer alle database-bewerkingen uit
            $em->flush();
            // Stuur gebruiker terug naar overzichtpagina
            return $this->redirectToRoute('admin_overview_object');
        }
        return $this->render('admin/add-object.html.twig', array(
            'form' => $objectForm->createView()
        ));
    }

    public function test()
    {
        $object1 = new ObjectProduct();
        $object1->setBeschikbaarheid();
        $object1->setChassisnummer("7DR239047111147");
        $object1->setFotos();
        $object1->setKenteken("WP-12-AS");
        $object1->setObjDagenVerhuurd();
        $object1->setObjDatumTerug();
        $object1->setObjDatumUit();
        $object1->setObjNaam();
        $object1->setObjType();
        $object1->setPrijs();

        $specificatie1 = new Specificatie();
        $specificatie1->setObject();
        $specificatie1->setMerk();
        $specificatie1->setType();
        $specificatie1->setBouwjaar();
        $specificatie1->setMassaInventaris();
        $specificatie1->setMassaMax();
        $specificatie1->setLengteTot();
        $specificatie1->setLengteOpbouw();
        $specificatie1->setHoogte();
        $specificatie1->setRijbewijsBenodigd();
        $specificatie1->setPrijsDag();

        $object1->setSpecificatie();


    }

    // Controller voor toevoegen van opties
    public function addOption(Request $request)
    {
        // Initialiseer optie die doorgegeven wordt aan het formulier om gevuld te worden
        $optie = new OptieProduct();
        $form = $this->createForm(OptieType::class, $optie);

        $form->handleRequest($request);

        // Check of gegevens voldoen aan eisen voordat database-calls worden gedaan
        if ($form->isSubmitted() && $form->isValid()) {

            $files = $optie->getFotos();
            $fotosArr = array();
            for ($i = 0; $i <= (count($files) - 1); $i++) {
                // Genereer een unieke bestandsnaam en voed toe aan array
                $fileName = md5(uniqid()) . '.' . $files[$i]->guessExtension();
                array_push(
                    $fotosArr,
                    $fileName
                );
                // Maak een bestand aan op het product_images_location parameter aangegeven in congig/services.yaml met de unieke bestandsnaam
                $files[$i]->move(
                    $this->getParameter('product_images_location'),
                    $fileName
                );
            }

            // Zet de de naam van het bestand in het images veld in de database
            $optie->setFotos($fotosArr);

            // Haal de Entity Manager op dat beschikbaar wordt gesteld door de 'extends Controller'
            $em = $this->getDoctrine()->getManager();

            // Zet de gebruiker in tijdelijke opslag, klaar voor opslag. Hierna zouden bijvoorbeeld meerdere entities worden toegevoegd om die vervolgens in een database call in het database te zetten.
            $em->persist($optie);

            // Entity manager zet alle entities die gepersist werden in het database
            $em->flush();

            return $this->redirectToRoute('admin_overview_option');
        }

        // Laat optie toevoegen pagina zien met de zichtbare verandering, template-engine (this->render) beschikbaar gesteld door 'extends Controller'
        return $this->render('admin/add-option.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteDiscount($id)
    {
        $repository = $this->getDoctrine()->getRepository(ActiePeriode::class);

        $discount = $repository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($discount);
        $em->flush();
        return $this->redirectToRoute('admin_config');
    }

    public function deleteObject($id)
    {
        $object = $this->getDoctrine()->getRepository(ObjectProduct::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        $em->flush();
        return $this->redirectToRoute('admin_overview_object');
    }

    public function deleteOption($id)
    {
        // Haal entity manager op, beschikbaar gesteld door 'extends Controller'
        $em = $this->getDoctrine()->getManager();
        // Haal repository op die bij het entiteit hoort
        $option = $em->getRepository(OptieProduct::class)
            // Zoek op primaire sleutel (id) dat als route parameter meegestuurd wordt naar de controller
            ->find($id);
        // Check of optie is gevonden
        if ($option) {
            // Verwijder de gevonden gebruiker in tijdelijke opslag
            $em->remove($option);
            // Voer alle databasebewerkingen uit
            $em->flush();
            // Stuur terug naar overzichtpagina om veranderingen te zien
            return $this->redirectToRoute('admin_overview_option');
        }
        // Stuur terug naar overzichtspagina met foutmelding
        return $this->redirectToRoute('admin_overview_option', array(
            'error' => 'Interne fout: Optie kan niet verwijderd worden'
        ));
    }

    public function deleteUser($id)
    {
        $user = $this->getDoctrine()->getRepository(Klantaccount::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('admin_overview_user');
    }

    // Controller voor het deactiveren van een klant
    public function disableUser($id)
    {
        // Haal entity manager op, beschikbaar gesteld door 'extends Controller'
        $em = $this->getDoctrine()->getManager();
        // Haal repository op die bij het entiteit hoort
        $user = $em->getRepository(Klantaccount::class)
            // Zoek op primaire sleutel (id) dat als route parameter meegestuurd wordt naar de controller
            ->find($id);
        // Zet isActive op false
        $user->setIsActive(false);
        // Zet gebruiker tijdelijk in opslag om later databasebewerkingen uit te voeren
        $em->persist($user);
        // Voer alle database bewerkingen die persist zijn door naar de database
        $em->flush();
        // Laat Klanten overzicht pagina zien met de zichtbare verandering
        return $this->redirectToRoute('admin_overview_user');
    }

    public function editDiscount()
    {
        // Todo
    }

    // Controller voor aanpassingen van objecten
    public function editObject($id, Request $request)
    {
        $object = $this->getDoctrine()->getRepository(ObjectProduct::class)->find($id);
        $form = $this->createForm(EditObjectType::class, $object);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $files = $object->getFotos();
            $fotosArr = array();
            for ($i = 0; $i <= (count($files) - 1); $i++) {
                // Genereer een unieke bestandsnaam en voed toe aan array
                $fileName = md5(uniqid()) . '.' . $files[$i]->guessExtension();
                array_push(
                    $fotosArr,
                    $fileName
                );
                // Maak een bestand aan op het product_images_location parameter aangegeven in congig/services.yaml met de unieke bestandsnaam
                $files[$i]->move(
                    $this->getParameter('product_images_location'),
                    $fileName
                );
            }

            $em = $this->getDoctrine()->getManager();
            $object->setFotos($fotosArr);
            $em->persist($object);
            $em->flush();
            return $this->redirectToRoute('admin_overview_object');
        }
        return $this->render('admin/edit-object.html.twig', array(
            'form' => $form->createView()
        ));
    }

    // Controller voor aanpassingen van opties
    public function editOption($id, Request $request)
    {
        // Haal entity manager op, beschikbaar gesteld door 'extends Controller'
        $em = $this->getDoctrine()->getManager();
        // Haal repository op die bij het entiteit hoort
        $option = $em->getRepository(OptieProduct::class)
            // Zoek op primaire sleutel (id) dat als route parameter meegestuurd wordt naar de controller
            ->find($id);
        // Stel de back-end van het formulier dynamish op
        $form = $this->createForm(OptieType::class, $option);
        // Laat het formulier zich dynamisch afhandelen (doordat alle relaties in OptieType aangegeven zijn)
        $form->handleRequest($request);
        // Check of formulier correct is ingevult en een POST request wordt gedaan
        if ($form->isSubmitted() && $form->isValid()) {

            $files = $option->getFotos();
            $fotosArr = array();
            for ($i = 0; $i <= (count($files) - 1); $i++) {
                // Genereer een unieke bestandsnaam en voed toe aan array
                $fileName = md5(uniqid()) . '.' . $files[$i]->guessExtension();
                array_push(
                    $fotosArr,
                    $fileName
                );
                // Maak een bestand aan op het product_images_location parameter aangegeven in congig/services.yaml met de unieke bestandsnaam
                $files[$i]->move(
                    $this->getParameter('product_images_location'),
                    $fileName
                );
            }
            $option->setFotos($fotosArr);
            // Sla het bewerkte entiteit op in tijdelijke opslag
            $em->persist($option);
            // Voor databasebewerkingen uit
            $em->flush();
            // Stuur gebruiker terug naar overzicht om veranderingen zichtbaar te maken
            return $this->redirectToRoute('admin_overview_option');
        }
        // Stuur het formulier standaard weg als het type request geen POST is (en gegevens valide zijn)
        return $this->render('admin/edit-option.html.twig', array(
            'form' => $form->createView()
        ));
    }

    // Controller voor aanpassingen aan gebruikers
    public function editUser()
    {

    }

    // Controlleer voor het deactiveren van gebruikers
    public function enableUser($id)
    {
        // Haal entity manager op, beschikbaar gesteld door 'extends Controller'
        $em = $this->getDoctrine()->getManager();
        // Haal repository op die bij het entiteit hoort
        $user = $em->getRepository(Klantaccount::class)
            // Zoek op primaire sleutel (id) dat als route parameter meegestuurd wordt naar de controller
            ->find($id);
        // Zet isActive op true
        $user->setIsActive(true);
        // Zet gebruiker tijdelijk in opslag om later databasebewerkingen uit te voeren
        $em->persist($user);
        // Voer alle database bewerkingen die persist zijn door naar de database
        $em->flush();
        // Laat Klanten overzicht pagina zien met de zichtbare verandering, beschikbaar gesteld door 'extends Controller'
        return $this->redirectToRoute('admin_overview_user');
    }

    // Controller voor overzicht van huidige objecten
    public function overviewObject()
    {
        // Haal entiteit manager op beschikbaar gesteld door 'extends Controller'. En koppel de repository klasse die bij de entiteit hoort
        $repository = $this->getDoctrine()->getRepository(ObjectProduct::class);
        // Haal alle huidige objecten op
        $objects = $repository->findAll();
        // Laat de Objecten zien op de overzichtpagina

//        $objArr = array();
//        foreach($objects as $key => $value){
//            $objStart = new $value->getObjDatumUit();
//            $objEnd = new $value->getObjDatumTerug();
//            $difference = $objStart->diff($objEnd)->days;
//            var_dump($difference);
//            exit();
//        }

        return $this->render('admin/overview-object.html.twig', array(
            'objects' => $objects,
            'date' => new \DateTime('now')
        ));
    }

    // Controller voor overzicht van huidige opties
    public function overviewOption()
    {
        // Haal entity manager op, wordt beschikbaar gesteld door 'extends Controller'
        $em = $this->getDoctrine()->getManager();
        // Geeft aan welke Repository als basis gebruikt wordt
        $options = $em->getRepository(OptieProduct::class)
            // Haalt alle records op
            ->findAll();
        // Stuur view terug, beschikbaar gesteld door de templating engine door 'extends Controller'
        return $this->render('admin/overview-option.html.twig', array(
            'options' => $options
        ));
    }

    // Controller voor overzicht van klanten/gebruikers
    public function overviewUser()
    {
        // Haal entity manager op, wordt beschikbaar gesteld door 'extends Controller'
        $users = $this->getDoctrine()->getManager()
            // Geeft aan welke Repository als basis gebruikt wordt en haalt alle records uit die entity en haalt maximaal 10 gebruikers op
            ->getRepository(Klantaccount::class)->findBy(array(), array(), 10);
//        $userOrders = $this->getDoctrine()->getRepository(Klantaccount::class)->findBy(array(
//            'bestellings' >= 1
//        ));
//        var_dump($userOrders);
//        exit();
        $userOrderArr = array();
        foreach ($users as $key => $user) {
            if ($user->getBestellings()) {
                $userOrderArr[$user->getId()] = true;
            } else {
                $userOrderArr[$user->getId()] = false;
            }
        }
        // Stuur view terug, beschikbaar gesteld door de templating engine door 'extends Controller'
        return $this->render('admin/overview-user.html.twig', array(
            'users' => $users,
            'searchTerm' => '',
            'orders' => $userOrderArr
        ));
    }
//    // Controller voor het zoeken naar gebruikers binnen de users overview
//    public function searchUser(Request $request)
//    {
//        // Haal het gebruikersnaam uit de POST-variabelen
//        $needle = trim($request->request->get('needle'));
//        // Haal entiteit manager op, beschikbaar gesteld door 'extends Controller'
//        $em = $this->getDoctrine()->getManager();
//        // Geef de enititeit-manager een repository en zoek op criteria
//        $user = $em->getRepository(Klantaccount::class)
//            ->findOneBy(['username' => $needle]);
//        // Initialiseer gebruikersnaam variabel als deze gevult moet worden
//        $username = '';
//        if($user){
//            // Vul het variabel met de gebruikersnaam
//            $username = $user->getUsername();
//            // Redirect terug naar de overview pagina met als users variabel met de enkele gezochte user /  geen resultaten
//            return $this->render('admin/overview-user.html.twig', array('users' => $user, 'searchTerm' => $username));
//        }
//        else{
//            // Redirect terug naar de overview pagina met een error over de zoekresultaten
//            return $this->render('admin/overview-user.html.twig' , array('error' => 'Zoekterm heeft 0 resultaten'));
//        }
//    }

    public function settingsAdmin()
    {
        $repository = $this->getDoctrine()->getRepository(ActiePeriode::class);
        $discounts = $repository->findAll();

        return $this->render('admin/settings-global.html.twig', array(
            'discounts' => $discounts
        ));
    }

    public function addDiscount(Request $request)
    {
        $actiePeriode = new ActiePeriode();
        $form = $this->createForm(ActiePeriodeType::class, $actiePeriode);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($actiePeriode);
            $em->flush();
            return $this->render('admin/settings-global.html.twig', array(
                'discounts' => $em->getRepository(ActiePeriode::class)->findAll()
            ));
        }

        return $this->render('admin/settings-add-discount.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function Optionstest()
    {
        $em = $this->getDoctrine()->getManager();

        $optie0 = new OptieProduct();
        $optie0->setOptieTitel('Voortent');
        $optie0->setOptieOmschrijving('Mooie voortent');
        $optie0->setOptiePrijs('100');


        $optie1 = new OptieProduct();
        $optie1->setOptieTitel('Bijzettent');
        $optie1->setOptieOmschrijving('Mooie bijzettent');
        $optie1->setOptiePrijs('75');


        $optie2 = new OptieProduct();
        $optie2->setOptieTitel('Windscherm');
        $optie2->setOptieOmschrijving('Mooie windscherm');
        $optie2->setOptiePrijs('20');


        $optie3 = new OptieProduct();
        $optie3->setOptieTitel('Barbecue');
        $optie3->setOptieOmschrijving('Mooie barbecue');
        $optie3->setOptiePrijs('30');


        $optie4 = new OptieProduct();
        $optie4->setOptieTitel('Skottelbraai');
        $optie4->setOptieOmschrijving('Mooie skottelbraai');
        $optie4->setOptiePrijs('50');


        $optie5 = new OptieProduct();
        $optie5->setOptieTitel('Televiesietoestel, schotelantenne en abonnement');
        $optie5->setOptieOmschrijving('Mooie televisietoestel, schotelantenne en abonnement');
        $optie5->setOptiePrijs('90');


        $optie6 = new OptieProduct();
        $optie6->setOptieTitel('Uitbreidingsset servies en bestek');
        $optie6->setOptieOmschrijving('Mooie uitbreidingsset servies en bestek');
        $optie6->setOptiePrijs('5');


        $optie7 = new OptieProduct();
        $optie7->setOptieTitel('Annuleringsverzekering');
        $optie7->setOptieOmschrijving('Een top annuleringsverzekering');
        $optie7->setOptiePrijs('50');


        $optie8 = new OptieProduct();
        $optie8->setOptieTitel('Inboedelverzekering');
        $optie8->setOptieOmschrijving('Een top inboedelverzekering');
        $optie8->setOptiePrijs('40');


        $optie9 = new OptieProduct();
        $optie9->setOptieTitel('Verzekerng hagelschade');
        $optie9->setOptieOmschrijving('Een top verzekering hagelschade');
        $optie9->setOptiePrijs('50');


        $em->persist($optie0);
        $em->persist($optie1);
        $em->persist($optie2);
        $em->persist($optie3);
        $em->persist($optie4);
        $em->persist($optie5);
        $em->persist($optie6);
        $em->persist($optie7);
        $em->persist($optie8);
        $em->persist($optie9);

        $em->flush();
    }
}
