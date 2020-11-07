<?php

namespace App\Controller;

use App\Services\Cart\CartServices;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     */
    public function checkoutSuccess()
    {
        header("Refresh:3;url=/profile");
        return $this->render('payment/checkout_success.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     *  @Route("/checkout-error", name="app_checkout_error")
     */
    public function checkoutError()
    {
        header("Refresh:3;url=/create-checkout-session");
        return $this->render('payment/checkout_error.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     *  @Route("/create-checkout-session", name="checkout")
     */
    public function checkout()
    {
        \Stripe\Stripe::setApiKey('sk_test_51HkbkUJ8dOcVTGaBuQyumQqHAOphnYwqKAgx5A5xXuelh5rWCwCirV9ssZr7gEduJiSSIe3ffyPMMv6KqwLJYccX006KhsHZU7');

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
              'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                  'name' => 'Programme',
                ],
                'unit_amount' => 2000,
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
