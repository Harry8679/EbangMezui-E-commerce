<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
        
    }
    
    public function add($product)
    {
        // Appeler la session de Symfony
        $cart = $this->requestStack->getSession()->get('cart', []); // Initialiser avec un tableau vide si la session est vide

        // Ajouter une quantité +1 à mon produit
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => $cart[$product->getId()]['quantity'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'quantity' => 1
            ];
        }
        
        // Créer ma session Cart
        $this->requestStack->getSession()->set('cart', $cart);
    }


    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
}