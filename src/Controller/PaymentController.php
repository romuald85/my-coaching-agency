<?php

namespace App\Controller;

use App\Repository\BillRepository;
use App\Services\Cart\CartServices;
use App\Controller\CommandsController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    private $cartServices;

    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }

    /**
     * @Route("/payment", name="app_payment")
     */
    public function index()
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     *  @Route("/checkout-success", name="app_checkout_success")
     * @IsGranted("ROLE_USER")
     */
    public function checkoutSuccess(CommandsController $commandsController)
    {
        if(!$this->getUser()){
            $this->addFlash("warning", "Vous n'êtes pas le bon utilisateur");
            return $this->redirectToRoute('app_pricing');
        }

        $commandsController->prepareCommandAction();// valider la commande préparée de la page panier une fois arrivé sur la page succès après le paiement et vide le panier en bdd

        header("Refresh:3;url=/profile");

        return $this->render('payment/checkout_success.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     *  @Route("/checkout-error", name="app_checkout_error")
     */
    public function checkoutError(EntityManagerInterface $em, BillRepository $billRepository)
    {
        // Supprime la dernière facture mise en bdd
        /*$bill = $billRepository->findOneBy([], ['id' => 'DESC']);
        $em->remove($bill);
        $em->flush();*/

        header("Refresh:3;url=/command");

        return $this->render('payment/checkout_error.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     *  @Route("/create-checkout-session", name="checkout")
     */
    public function checkout()
    {
        $amount = number_format(ceil($this->cartServices->getTotal($this->getUser())[1]));

        \Stripe\Stripe::setApiKey('sk_test_51HkbkUJ8dOcVTGaBuQyumQqHAOphnYwqKAgx5A5xXuelh5rWCwCirV9ssZr7gEduJiSSIe3ffyPMMv6KqwLJYccX006KhsHZU7');

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
              'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                  'name' => 'Programme sportif',
                ],
                'unit_amount' =>  $amount * 100,
              ],
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_checkout_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_checkout_error', [], UrlGeneratorInterface::ABSOLUTE_URL),
          ]);

          return new JsonResponse([ 'id' => $session->id ]);
    }
}
