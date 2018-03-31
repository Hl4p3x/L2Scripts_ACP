<?php
/**
 * @author    Sergey Golubev (ekifox.me)
 * @copyright Copyright (c) 2016 Sergey Golubev
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace G2APay;
use G2APay\G2APay;
class IPNHandler{
  public $postdata;
  public $status, $orderId, $amount, $orderCompleteAt, $orderCreatedAt;
  private $G2APay;
  const STATUS_CANCELED          = 'canceled';
  const STATUS_COMPLETE          = 'complete';
  const STATUS_REFUNDED          = 'refunded';
  const STATUS_PARTIALY_REFUNDED = 'partial_refunded';
  const ERROR_HASHCODE           = 150;
  public function __construct(G2APay $g2a){
    $this->selfPost();
    $this->G2APay = $g2a;
  }
  public function Check(){
    if(
      isset($this->postdata['type']) &&
      isset($this->postdata['hash']) &&
      isset($this->postdata['status']) &&
      isset($this->postdata['userOrderId']) &&
      $this->postdata['currency'] == $this->G2APay->Currency
    ){
      $chash = $this->CalculatingHash($this->postdata['transactionId'], $this->postdata['userOrderId'], $this->postdata['amount']);
      if($chash != $this->postdata['hash']){
        throw new \ErrorException('Hash Code', ERROR_HASHCODE);
      }
    }else return false;
    $this->makeVars();
  }
  private function makeVars(){
    $this->status          = $this->postdata['status'];
    $this->orderId         = $this->postdata['userOrderId'];
    $this->amount          = $this->postdata['amount'];
    $this->orderCreatedAt  = $this->postdata['orderCreatedAt'];
    $this->orderCompleteAt = $this->postdata['orderCompleteAt'];
  }
  private function CalculatingHash($transactionId, $userOrderId, $amount){
    return hash('sha256', $transactionId.$userOrderId.$amount.$this->G2APay->ApiSecret);
  }
  private function selfPost(){
    #TODO
    $this->postdata = $_POST;
  }
}
