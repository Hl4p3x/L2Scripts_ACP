<?php
/**
 * @author    Sergey Golubev (ekifox.me)
 * @copyright Copyright (c) 2016 Sergey Golubev
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace G2APay;
class PayItem{
  public $id;
  public $sku;
  public $name;
  public $quantity;
  public $price;
  public $url;
  public $amount;
  public function __construct($id, $sku, $name, $quantity, $price, $url){
    $this->id = (string) $id;
    $this->sku = (string) $sku;
    $this->name = (string) $name;
    $this->quantity = (integer) $quantity;
    $this->price = (float) $price;
    $this->url = (string) $url;
    $this->amount = (float) ($this->price * $this->quantity);
  }
}
