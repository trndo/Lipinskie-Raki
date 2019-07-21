<?php


namespace App\Service\CartHandler;


use App\Entity\Receipt;
use App\Repository\ProductRepository;
use App\Repository\ReceiptRepository;
use App\Service\EntityService\SupplyService\SupplyServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartHandler implements CartHandlerInterface
{
    /**
     * @var ReceiptRepository
     */
    private $receiptRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var SupplyServiceInterface
     */
    private $supplyService;

    public function __construct(ReceiptRepository $receiptRepository, ProductRepository $productRepository)
    {
        $this->receiptRepository = $receiptRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $id
     * @return \App\Entity\Product|\App\Entity\Receipt
     */
    public function getItem(string $id)
    {
        $item = $this->explodeId($id);
        if ($item[0] == 'product') {
            return $this->productRepository->find((int)$item[1]);
        }

        if ($item[0] == 'receipt') {
            return $this->receiptRepository->find((int)$item[1]);
        }
    }

    /**
     * Add receipt or product to cart
     *
     * @param Request $request
     */
    public function addItemToCart(Request $request): void
    {
        $session = $request->getSession();
        $post = $request->request;
        $shoppingCart = [];
        $id = $post->get('id');
        $productWithSize = $post->get('size');

        if (!$session->get('cart')) {
            $session->set('cart', $shoppingCart);
        }

        $shoppingCart = $session->get('cart');
        if ($this->checkIsValidId($id)){
            if($productWithSize && $this->checkReceiptRelation($id,$productWithSize)) {
                $shoppingCart[$id] = [
                    $post->get('size') => $post->get('quantity',1)
                ];
            }
            else
                $shoppingCart[$id] = $post->get('quantity',1);
        }
        $session->set('cart',$shoppingCart);
        $this->countTotalSum($session);
    }

    public function removeFromCart(Request $request): void
    {
        $session = $request->getSession();
        $cart = $session->get('cart');
        $key = $request->request->get('id');

        if (isset($cart[$key])) {
            unset($cart[$key]);
            $session->set('cart',$cart);
        }
        $this->countTotalSum($session);
    }

    /**
     * Add item's quantity by key
     *
     * @param Request $request
     * @return array
     */
    public function changeItemQuantity(Request $request): array
    {
        $session = $request->getSession();
        $post = $request->request;
        $id = $post->get('id');
        $quantity = $post->get('quantity');
        $product = $this->getItem($id);
        $productQuantity = $product->getSupply()->getQuantity();

        if(!$session->get('cart'))
            $cart = $session->set('cart',[]);
        else
            $cart = $session->get('cart');

        if ($quantity > $productQuantity ) {
            return [
                'status'=> false,
                'rest' => $productQuantity,
                'unit' => $product->getUnit(),
            ];
        }

        if (isset($cart[$id])) {
            if(is_array($cart[$id])) {
                $keys = array_keys($cart[$id]);
                $cart[$id][$keys[0]] = $quantity;
            }
            else
            $cart[$id] = $quantity ;
            $session->set('cart',$cart);
            $this->countTotalSum($session);
        }
        return [
            'status' => true,
            'totalSum' => $session->get('totalSum')
        ];

    }

    private function countTotalSum(SessionInterface $session)
    {
        $total = 0;
        $cart = $session->get('cart');
        foreach ($cart as $id) {
            $item = $this->getItem($id);
            if (null !== $item) {
                if (is_array($id)) {
                    $keys = array_keys($id);
                    $relatedProduct = $this->getItem($keys[0]);
                    $total += ceil(($relatedProduct->getPrice() + $item->getPrice()) * $id[$keys[0]]);
                } else
                    $total += ceil($item->getPrice() * $cart[$id]);
                $session->set('totalSum', $total);
            }
        }
    }

    private function explodeId(string $id)
    {
        return explode('-',$id);
    }

    private function checkIsValidId(string $id): bool
    {
        if(!preg_match('/^(product|receipt)\-\d+$/',$id))
            return false;

        if(null === $this->getItem($id))
            return false;

        return true;
    }

    private function checkReceiptRelation(string $receiptId,string $productId)
    {
        if(!preg_match('/^(product|receipt)\-\d+$/',$receiptId) || !preg_match('/^(product|receipt)\-\d+$/',$productId))
            return false;
        $receipt = $this->getItem($receiptId);
        $product = $this->getItem($productId);
        if(null === $receipt || null === $product)
            return false;

        if(!$receipt->getProducts()->contains($product))
            return false;

        return true;
    }

    /**
     * Return cart items
     *
     * @param Request $request
     * @return array
     */
    public function getItems(Request $request): array
    {
        $session = $request->getSession();
        $cart = $session->get('cart');
        $result = [];
        foreach ($cart as $key => $value) {
            $item = $this->getItem($key);
            if(is_array($value)) {
                $productId = array_keys($value)[0];
                $relatedProduct = $this->getItem($productId);
                $item = [
                    'receipt' => $item,
                    'product' => $relatedProduct
                ];
            }
            $result[] = $item;
        }

        return $result;
    }
}