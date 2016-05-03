<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\CoffeeShop;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\CoffeeShopType;

class CoffeeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle:CoffeeController:index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/admin/add/coffee_shop", name="add_coffee_shop")
     */
    public function AddCoffeeShopAction(Request $request)
    {
        $coffeeshop = new CoffeeShop();

        $form = $this->createForm(CoffeeShopType::class, $coffeeshop);
        $form->handleRequest($request);
        
        $submitted = '';
        
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $coffeeshop->setName($data->getName());
            $coffeeshop->getLocation($data->getLocation());
            $coffeeshop->setDescription($data->getDescription());

            $em = $this->getDoctrine()->getManager();
            $em->persist($coffeeshop);
            $em->flush();

            $submitted = 'Saved new coffeeshop with id '.$coffeeshop->getId();

        }

        return $this->render('AppBundle:CoffeeController:add_coffee.html.twig', array(
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'form' => $form->createView(),
            'submitted' => $submitted,
        ));
    }

    /**
     * @Route("/admin/edit/{coffee_shop_id}")
     */
    public function editCoffeeShopAction(Request $request, $coffee_shop_id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:CoffeeShop');

        $coffeeshop = $repository->find($coffee_shop_id);

        $form = $this->createForm(CoffeeShopType::class, $coffeeshop);
        $form->handleRequest($request);

        $submitted = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $update_coffeeshop = $repository->find($coffee_shop_id);

            if (!$update_coffeeshop) {
                throw $this->createNotFoundException(
                    'No coffeeshop found for id '.$coffee_shop_id
                );
            }

            $data = $form->getData();

            $update_coffeeshop->setName($data->getName());
            $update_coffeeshop->getLocation($data->getLocation());
            $update_coffeeshop->setDescription($data->getDescription());

            $em->flush();

            $submitted = 'Updated coffeeshop with id ' . $coffeeshop->getId();
        }

        return $this->render('AppBundle:CoffeeController:edit_coffee_shop.html.twig', array(
            'form' => $form->createView(),
            'submitted' => $submitted,
        ));
    }

    /**
     * @Route("/view/{coffee_shop_id}", name="view_coffee_shops")
     */
    public function viewCoffeeShopAction($coffee_shop_id, $coffee_shop_id = 'all')
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:CoffeeShop');

        if ($coffee_shop_id == 'all') {

            $coffeeshops = $repository->findAll();

            return $this->render('AppBundle:CoffeeController:view_coffee_shops_all.html.twig', array(
                'coffeeshops' => $coffeeshops,
            ));
        } else {

            $coffeeshop = $repository->find($coffee_shop_id);

            return $this->render('AppBundle:CoffeeController:view_coffee_shop.html.twig', array(
                'coffeeshop' => $coffeeshop,
            ));
        }
    }
}
