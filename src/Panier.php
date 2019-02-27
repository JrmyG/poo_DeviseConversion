<?php

namespace Conversion;

class Panier
{

	private $produits;
	private $totalConverti;
	private  $devise_panier;


	public function __construct($devise_panier)
	{
		$this->devisePanier = $devise_panier;
	}

	public function getProduits()
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit)
    {
			$this->produits[] = $produit;
    }

    public function totalConverti()
    {
        $this->totalConverti = 0;

        foreach ($this->produits as $index => $produit) 
        {
			if ($produit->getDevise() != $this->devisePanier)
			{
				$devise =  json_decode(file_get_contents('https://api.exchangeratesapi.io/latest?base=' . $produit->getDevise() . '&symbols='. $this->devisePanier.''), true);
				$devisepanier = $devise['rates'][$this->devisePanier];
				$tarifs = floatval($devisepanier) * $produit->getPrix();
				$this->totalConverti = $this->totalConverti + ($produit->getQuantite())*($tarifs);
			}
			else {
				$this->totalConverti = $this->totalConverti + ($produit->getQuantite())*($produit->getPrix());
			}
        }
        return $this->totalConverti;
    }

}