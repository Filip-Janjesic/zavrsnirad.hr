<?php

namespace Controller;

use Model\OrderProduct;
use Storage\OrderStorage;

class OrderController extends BaseController
{
    private $viewDir = DIRECTORY_SEPARATOR . 'order' . DIRECTORY_SEPARATOR;

    public function index()
    {
        $orders = OrderStorage::findAll();

        echo $this->view->render('base', [
            'content' => $this->view->render($this->viewDir . 'index', [
                'orders' => $orders
            ])
        ]);
    }

    public function cart()
    {
        $orderProducts = OrderStorage::findAllFromLoggedInUser();

        $orderId = 0;
        if(count($orderProducts) !== 0) {
            foreach ($orderProducts as $orderProduct)
            {
                $orderId = $orderProduct->order_id;
                break;
            }
        }

        $totalItems = $totalPrice = 0;
        if(isset($orderId)) {
            $totalItems = OrderStorage::getTotalItemsOfOrder($orderId);
            $totalPrice = OrderStorage::getTotalPriceOfOrder($orderId);
        }

        echo $this->view->render('base', [
            'content' => $this->view->render($this->viewDir . '/cart', [
                'totalItems' => $totalItems,
                'totalPrice' => $totalPrice,
                'orderProducts' => $orderProducts,
                'orderId' => $orderId
            ])
        ]);
    }

    public function add()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['submit_product'])) {
                $user = $_SESSION['user'];
                $userOrder = OrderStorage::checkOrder($user->id);
                if (!$userOrder) {
                    OrderStorage::create($user);
                    $userOrder = OrderStorage::checkOrder($user->id);
                }
                $orderProduct = new OrderProduct();
                $orderProduct->setOrder($userOrder->id);
                $orderProduct->setProduct($_POST['product_id']);
                $orderProduct->setAmount($_POST['amount']);
                OrderStorage::addToCart($orderProduct);
                header('Location: /order/cart');
            }
        }
    }

    public function delete()
    {
        $orderProductId = $_GET['id'];
        OrderStorage::removefromcart($orderProductId);
        header('Location: /order/cart');
    }

    public function finish()
    {
        $order = OrderStorage::findOneById($_GET['id']);

        OrderStorage::finishOrder($order->id);

        header('Location: /user/history');
    }
}
