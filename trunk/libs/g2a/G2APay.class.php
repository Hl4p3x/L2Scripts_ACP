<?php
/**
 * @author    Sergey Golubev (ekifox.me)
 * @copyright Copyright (c) 2016 Sergey Golubev
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace G2APay;
require_once('PayItem.class.php');
require_once('IPNHandler.class.php');
use G2APay\PayItem;
use G2APay\IPNHandler;
class G2APay{
  private $ApiUrl = "https://checkout.pay.g2a.com";
  private $Items = [];
  public $ApiSecret;
  public $ApiHash;
  public $Currency;
  public $URLFail;
  public $URLSuccess;
  public $OrderID;
  public function __construct($ApiHash, $ApiSecret, $URLSuccess, $URLFail, $Currency = "USD"){
    $this->ApiHash    = $ApiHash;
    $this->ApiSecret  = $ApiSecret;
    $this->Currency   = $Currency;
    $this->URLSuccess = $URLSuccess;
    $this->URLFail    = $URLFail;
  }
  public function AddItem(PayItem $item){
    $this->Items[] = $item;
  }
  public function setOrderId($id){
    $this->OrderID = $id;
  }
  public function CreateQuote(){
    $amount = 0;
    $items = [];
    foreach($this->Items as $item){
      $items[] = [
        'sku'     => $item->sku,
        'name'    => $item->name,
        'amount'  => $item->amount,
        'qty'     => $item->quantity,
        'id'      => $item->id,
        'price'   => $item->price,
        'url'     => $item->url
      ];
      $amount += $item->amount;
    }
    $fields = [
      'api_hash'    => $this->ApiHash,
      'hash'        => $this->CalculatingHash($this->OrderID, $amount),
      'order_id'    => $this->OrderID,
      'amount'      => $amount,
      'currency'    => $this->Currency,
      'url_failure' => $this->URLFail,
      'url_ok'      => $this->URLSuccess,
      'items'       => $items
    ];
    $post = $this->PostQuery('/index/createQuote', $fields);
    if(isset($post->token)) return $post->token;
    return false;
  }
  private function CalculatingHash($orderid, $amount){
    return hash('sha512', $orderid.$amount.$this->Currency.$this->ApiSecret);
  }
  private function PostQuery($method, $fields){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->ApiUrl.$method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result);
  }
}
?>
