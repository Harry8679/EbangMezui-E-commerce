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
        // Appeler la session de symfony
        $session = $this->requestStack->getSession();

        // Ajouter une quantité +1 à mon produit
        $cart[$product->getId()] = [
            'object' => $product,
            'quantity' => 1
        ];
        
        // Créer ma session Cart
        $this->requestStack->getSession()->set('cart', $cart);
    }
}