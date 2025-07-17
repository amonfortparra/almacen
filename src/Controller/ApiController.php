<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Picker;
use App\Entity\Product;
use App\Repository\PickerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }

    #[Route('/api/orders', name: 'api_post_orders', methods: "POST")]
    public function createOrder(EntityManagerInterface $interface, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $data_post = $request->request->all();
        $number_order = $data_post['number'];
        $list_products = array_map('intval', explode(',', $data_post['list_products']));
        
        $order = new Order();
        $order->setNumber($number_order);
        foreach($list_products as $id){
            $repositoryProduct = $interface->getRepository(Product::class);
            $product = $repositoryProduct->find($id);
            $order->addListProduct($product);
        }
        $order->setFinalDate(new \DateTime($data_post['date']));
        $repositoryPicker = $interface->getRepository(Picker::class);
        $picker = $repositoryPicker->find($repositoryPicker->getFreePicker()['id']);
        $order->setPicker($picker);
        $interface->persist($order);
        $errors = $validator->validate($order);
        if (count($errors) > 0) {
            return $this->json([
                'errors' => $errors,
            ]);
        }
        $interface->flush();
        return $this->json([
            'message' => 'Pedido asignado correctamente a ' . $picker->getName(),
        ]);
    }

    #[Route('/api/pickers/{id}', name: 'api_get_picker' , methods: ['GET'])]
    public function pickerInfo(EntityManagerInterface $interface, int $id): JsonResponse
    {
        $repositoryPicker = $interface->getRepository(Picker::class);
        $picker = $repositoryPicker->find($id);
        $repositoryOrder = $interface->getRepository(Order::class);
        $priority_order = $repositoryOrder->getPriorityOrderByPicker($picker);
        $list_product = [];
        foreach ($priority_order->getListProductsOrderByProximity() as $prod) {
            $list_product[] = [
                'name' => $prod->getName(),
                'rack' => $prod->getRack(),
                'line' => $prod->getLine(),
                'block' => $prod->getBlock(),
            ];
        }
        return $this->json([
            'picker' => [
                'name' => $picker->getName()
            ],
            'priority_order' => [
                'final_date' => $priority_order->getFinalDate()->format('d/m/Y H:i'),
                'list_product' => $list_product
            ],
        ]);
    }
}
