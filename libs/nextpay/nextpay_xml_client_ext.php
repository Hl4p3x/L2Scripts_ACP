<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

/**
 * Cкрипт создания заказа через XML API системы nextpay.ru
 *
 */

//----------------------------------- Настройки скрипта ----------------------------

//Секретный ключ продавца. Смотри настройки продавца в nextpay
define('NEXTPAY_XML_CLIENT_SELLER_SECRET_KEY', '');

//ID продукта. Смотри настройки продукта в nextpay
define('NEXTPAY_XML_CLIENT_PRODUCT_ID', '');

//Таймаут запроса в секундах. Если нужен бесконечный таймаут, то используйте значение <= 0
define('NEXTPAY_XML_CLIENT_SERVER_TIMEOUT', 60);

//Кодировка запроса
define('NEXTPAY_XML_CLIENT_REQUEST_CHARACTER_SET', 'windows-1251');

//Не использовать javascript?
define('NEXTPAY_XML_CLIENT_NO_JS', false);

//Запоминать ранее введенные данные в cookie?
define('NEXTPAY_XML_CLIENT_REMEMBER_ME', true);

//В какой домен писать куку с сохраненными данными
define('NEXTPAY_XML_CLIENT_COOKIE_DOMAIN', null);

//Использовать дизайн для мобильных телефонов?
define('NEXTPAY_XML_CLIENT_MOBILE_LOOK', false);

//Вставляем скрипт в страницу сайта(NEXTPAY_XML_CLIENT_EMBEDDED == true) или
//отображаем, как отдельную страницу (NEXTPAY_XML_CLIENT_EMBEDDED == false)
define('NEXTPAY_XML_CLIENT_EMBEDDED', true);

//Выводить содержимое в строку
define('NEXTPAY_XML_CLIENT_ECHO_IN_STRING', false);

//Платежная система по умолчанию
define('NEXTPAY_XML_CLIENT_DEFAULT_CURRENCY', 2);//WMR


//Как помечать обязательные к заполнению поля
define('NEXTPAY_XML_CLIENT_REQ_FIELD_MARK', '*');

//----------------------------------- Конец настроек скрипта ----------------------------

//URL сервера nextpay
define('NEXTPAY_XML_CLIENT_SERVER_URL', 'http://nextpay.ru/shop/shop/index.php');

//Кодировка ЭТОГО файла
define('NEXTPAY_XML_CLIENT_SOURCE_SCRIPT_CHARACTER_SET', 'windows-1251');


//IDы действий
define('NEXTPAY_XML_CLIENT_ACTION_BUY', 1);
define('NEXTPAY_XML_CLIENT_ACTION_RECALC', 2);

//ID платежных систем
define('NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY', 22);
define('NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY_2', 55);
define('NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY_3', 78);
define('NEXTPAY_XML_CLIENT_TERMUA_CURRENCY', 53);
define('NEXTPAY_XML_CLIENT_MC_CURRENCY', 31);
define('NEXTPAY_XML_CLIENT_MC_2_CURRENCY', 61);
define('NEXTPAY_XML_CLIENT_MC_3_CURRENCY', 70);
define('NEXTPAY_XML_CLIENT_MC_4_CURRENCY', 71);
define('NEXTPAY_XML_CLIENT_MC_5_CURRENCY', 79);
define('NEXTPAY_XML_CLIENT_MC_6_CURRENCY', 81);
define('NEXTPAY_XML_CLIENT_MC_84_CURRENCY', 84);
define('NEXTPAY_XML_CLIENT_MC_86_CURRENCY', 86);

//Коды возвратов сервера nextpay
define('NEXTPAY_XML_API_ERROR_SUCCESS', 0);


//ID формы "Оплата счета"
define('NEXTPAY_XML_API_BILL_FORM', '12');

//ID формы "Пополнение баланса"
define('NEXTPAY_XML_API_REPLENISH_BALANCE_FORM', '14');

define('NEXTPAY_XML_API_ATTR_TYPE_ARRAY', 3);

define('NEXTPAY_XML_API_MULTI_OPTIONS_SEPATATOR', ";");


$nextpayXMLClientResponse = "";

//Вызываемая функция скрипта
function nextpayXMLClient_Main()
{
	$charset = NEXTPAY_XML_CLIENT_REQUEST_CHARACTER_SET;
	if(!NEXTPAY_XML_CLIENT_EMBEDDED && !NEXTPAY_XML_CLIENT_ECHO_IN_STRING)
	{
		header("Content-type: text/html;charset=$charset");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");

	}

	if(!NEXTPAY_XML_CLIENT_EMBEDDED)
	{
		nextpayXMLClient_echoContent("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">");
		nextpayXMLClient_echoContent("<html><head>");
		nextpayXMLClient_echoContent("<meta name=\"viewport\" content=\"height=device-height,width=device-width\">");
		nextpayXMLClient_echoContent("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">");
		nextpayXMLClient_css();
		nextpayXMLClient_echoContent("<title>Оплата заказа</title></head><body>");
	}
	else
	{
		nextpayXMLClient_css();
	}

	nextpayXMLClient_echoContent("
	<div style='margin-bottom:10px;font-size:12px'>
		Заказ оплачивается с помощью системы <a href='http://nextpay.ru'>nextpay.ru</a>
	</div>");


	$action = null;
	nextpayXMLClient_getRequestIntegerValue("action", "", $action, false);
	switch ($action)
	{
		case NEXTPAY_XML_CLIENT_ACTION_BUY:
			{
				if(isset($_REQUEST['recalcbutton']))
				{
					nextpayXMLClient_showForm();
				}
				else
				{
					if(!nextpayXMLClient_buyRequest())
					{
						nextpayXMLClient_showForm();
					}
				}
				break;
			}
		case NEXTPAY_XML_CLIENT_ACTION_RECALC:
			{
				nextpayXMLClient_showForm();
				break;
			}
		default:
			{
				nextpayXMLClient_showForm();
				break;
			}
	}
	if(!NEXTPAY_XML_CLIENT_EMBEDDED)
	{
		nextpayXMLClient_echoContent("</body></html>");
	}
}


function nextpayXMLClient_css()
{
	if(NEXTPAY_XML_CLIENT_MOBILE_LOOK)
	{

		nextpayXMLClient_echoContent(
		"<style type=\"text/css\">
			.nxc_error
			{
				color:red
			}
			.nxc_container
			{
				display: table;
			}
			.nxc_row
			{
				display: table-row;
			}
			.nxc_key
			{
				display: table-cell;
				padding-top:10px;
				padding-right:2px
			}
			.nxc_value
			{
				display: table-cell;padding-top:10px
			}
			.nxc_input
			{
				width:80px
			}
		</style>");
	}
	else
	{
		nextpayXMLClient_echoContent(
		"<style type=\"text/css\">
			.nxc_error
			{
				color:red
			}
			.nxc_container
			{
				display: table;
			}
			.nxc_row
			{
				display: table-row;
			}
			.nxc_key
			{
				display: table-cell;
				padding-right:15px;
				padding-top:10px
			}
			.nxc_value
			{
				display: table-cell;
				padding-top:10px
			}
			.nxc_input
			{
			}
		</style>");
	}

}

/**
 * Отсылка запроса на формирование заказа на сервер nextpay.ru
 *
 */
function nextpayXMLClient_buyRequest()
{
	$errorMessage = null;
	$productData = nextpayXMLClient_getProductData($errorMessage);
	if($productData == null)
	{
		if($errorMessage == null)
		{
			$errorMessage = "Не удалось получить данные от сервера";
		}
		$errorMessage = nextpayXMLClient_htmlEncode($errorMessage);
		nextpayXMLClient_errorMessage($errorMessage);
		return false;
	}
	$key  = "error";
	if(array_key_exists($key, $productData))
	{
		$errorMessage = $productData['error'];
		nextpayXMLClient_errorMessage($errorMessage);
		return false;
	}

	$currencyId = null;
	if(!nextpayXMLClient_getRequestIntegerValue("currency", "Оплата", $currencyId, true))
	{
		return false;
	}

	$errorMessage = null;
	$rates = nextpayXMLClient_getRates($errorMessage);
	if($rates == null)
	{
		if($errorMessage == null)
		{
			$errorMessage = "Не удалось получить данные от сервера";
		}
		$errorMessage = nextpayXMLClient_htmlEncode($errorMessage);
		nextpayXMLClient_errorMessage($errorMessage);
		return false;
	}
	if(!array_key_exists($currencyId, $rates))
	{
		$errorMessage = "Неверный ID платежной системы: $currencyId";
		nextpayXMLClient_errorMessage($errorMessage);
		return false;
	}

	$index = "rate";
	$currencyData = $rates[$currencyId];
	if(!array_key_exists($index, $currencyData))
	{
		$errorMessage = "Неизвестен обменный курс для платежной системы. ID платежной системы: $currencyId";
		nextpayXMLClient_errorMessage($errorMessage);
		return false;
	}

	$exchangeRate = $currencyData[$index];
	if($exchangeRate <= 0)
	{
		$errorMessage = "Неверное значение для обменного курса платежной системы. ID платежной системы: $currencyId";
		nextpayXMLClient_errorMessage($errorMessage);
		return false;
	}

	$label = null;
	$costFieldIsReadonly = null;
	nextpayXMLClient_getFormSpecificLogic($productData, $label, $costFieldIsReadonly);
	$cost = nextpayXMLClient_getRequestStringValue("cost", $label);
	if($cost == null)
	{
		return false;
	}
	$cost = str_replace(",", ".", $cost);
	$cost = floatval($cost);
	$cost = nextpayXMLClient_formatCostValue($cost);
	if($cost <= 0)
	{
		$errorMessage = "Неверное значение поля '$label'";
		nextpayXMLClient_errorMessage($errorMessage);
		return false;
	}

	nextpayXMLClient_setSavedParam("payment_system", $currencyId);

	$url = NEXTPAY_XML_CLIENT_SERVER_URL."?command=xml_api_ext";

	$hash = NEXTPAY_XML_CLIENT_PRODUCT_ID.$cost.NEXTPAY_XML_CLIENT_SELLER_SECRET_KEY;
	$hash = sha1($hash);
	$url .= "&redirect_to_payment_system&hash=$hash&ext_order_cost=$cost&product_id=".NEXTPAY_XML_CLIENT_PRODUCT_ID."&volute=$currencyId";
	switch ($currencyId)
	{
		case NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY:
		case NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY_2:
		case NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY_3:
			{
				$toAccount = nextpayXMLClient_getRequestValue("kiwi_bill_to_account");
				nextpayXMLClient_setSavedParam("kiwi_account", $toAccount);
				$toAccount = nextpayXMLClient_prepareURLParam($toAccount);
				$url .= "&kiwi_bill_to_account=$toAccount";
				break;
			}
		case NEXTPAY_XML_CLIENT_TERMUA_CURRENCY:
			{
				$toAccount = nextpayXMLClient_getRequestValue("termua_bill_to_account");
				nextpayXMLClient_setSavedParam("termua_account", $toAccount);
				$toAccount = nextpayXMLClient_prepareURLParam($toAccount);
				$url .= "&termua_bill_to_account=$toAccount";
				break;
			}
		case NEXTPAY_XML_CLIENT_MC_CURRENCY:
			{
				$toAccount = nextpayXMLClient_getRequestValue("mc_bill_to_account");
				nextpayXMLClient_setSavedParam("mk_account", $toAccount);
				$toAccount = nextpayXMLClient_prepareURLParam($toAccount);
				$url .= "&mc_bill_to_account=$toAccount";
				break;
			}
		case NEXTPAY_XML_CLIENT_MC_2_CURRENCY:
		case NEXTPAY_XML_CLIENT_MC_3_CURRENCY:
			{
				$toAccount = nextpayXMLClient_getRequestValue("mc_2_bill_to_account");
				nextpayXMLClient_setSavedParam("mk_account", $toAccount);
				$toAccount = nextpayXMLClient_prepareURLParam($toAccount);
				$url .= "&mc_bill_to_account=$toAccount";
				break;
			}
		case NEXTPAY_XML_CLIENT_MC_4_CURRENCY:
		case NEXTPAY_XML_CLIENT_MC_84_CURRENCY:
			{
				$toAccount = nextpayXMLClient_getRequestValue("mc_bill_to_account_71");
				nextpayXMLClient_setSavedParam("mk_account_10", $toAccount);
				$toAccount = nextpayXMLClient_prepareURLParam($toAccount);
				$url .= "&mc_bill_to_account=$toAccount";
				break;
			}
		case NEXTPAY_XML_CLIENT_MC_5_CURRENCY:
		case NEXTPAY_XML_CLIENT_MC_86_CURRENCY:
			{
				$toAccount = nextpayXMLClient_getRequestValue("mc_bill_to_account_79");
				nextpayXMLClient_setSavedParam("mk_account", $toAccount);
				$toAccount = nextpayXMLClient_prepareURLParam($toAccount);
				$url .= "&mc_bill_to_account=$toAccount";
				break;
			}
		case NEXTPAY_XML_CLIENT_MC_6_CURRENCY:
			{
				$toAccount = nextpayXMLClient_getRequestValue("mc_bill_to_account_81");
				nextpayXMLClient_setSavedParam("mk_account", $toAccount);
				$toAccount = nextpayXMLClient_prepareURLParam($toAccount);
				$url .= "&mc_bill_to_account=$toAccount";
				break;
			}
	}
	nextpayXMLClient_getFormAttributes($productData, $url);
	$response = nextpayXMLClient_getServerResponse($url);
	if($response == null)
	{
		return false;
	}
	else
	{
		$result = $response['result'];
		$data = $response['data'];
		$comment = $response['comment'];
		switch ($result)
		{
			case NEXTPAY_XML_API_ERROR_SUCCESS:
				{
					switch($currencyId)
					{
						case NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY:
						case NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY_2:
						case NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY_3:
							{
								$data = nextpayXMLClient_convFromUTF($data);
								nextpayXMLClient_printKIWIBill($data);
								break;
							}
						case NEXTPAY_XML_CLIENT_TERMUA_CURRENCY:
							{
								$data = nextpayXMLClient_convFromUTF($data);
								nextpayXMLClient_printTERMUABill($data);
								break;
							}
						case NEXTPAY_XML_CLIENT_MC_CURRENCY:
						case NEXTPAY_XML_CLIENT_MC_2_CURRENCY:
						case NEXTPAY_XML_CLIENT_MC_3_CURRENCY:
						case NEXTPAY_XML_CLIENT_MC_4_CURRENCY:
						case NEXTPAY_XML_CLIENT_MC_84_CURRENCY:
						case NEXTPAY_XML_CLIENT_MC_5_CURRENCY:
						case NEXTPAY_XML_CLIENT_MC_6_CURRENCY:
						case NEXTPAY_XML_CLIENT_MC_86_CURRENCY:
							{
								$data = nextpayXMLClient_convFromUTF($data);
								nextpayXMLClient_printMCBill($data);
								break;
							}
						default:
							{
								if(NEXTPAY_XML_CLIENT_NO_JS)
								{
									if(NEXTPAY_XML_CLIENT_ECHO_IN_STRING)
									{
										nextpayXMLClient_echoContent("<a href=\"$data\">Перейти к оплате</a>");
									}
									else
									{
										if(headers_sent())
										{
											nextpayXMLClient_echoContent("<a href=\"$data\">", false);
											nextpayXMLClient_echoContent("Перейти к оплате</a>");
										}
										else
										{
											header("Location: $data");
											exit;
										}
									}
								}
								else
								{
									nextpayXMLClient_echoContent("<script type=\"text/javascript\">location.href=\"$data\";</script>", false);
								}
								break;
							}
					}
					return true;
				}
			default:
				{
					$errorMessage = $comment == null ? "Код ошибки: $result" : $comment;
					$errorMessage = nextpayXMLClient_htmlEncode($errorMessage);
					nextpayXMLClient_errorMessage($errorMessage);
					return false;
				}
		}
	}
}

//Форма заказа
function nextpayXMLClient_showForm()
{
	$errorMessage = null;
	$productData = nextpayXMLClient_getProductData($errorMessage);
	if($productData == null)
	{
		if($errorMessage == null)
		{
			$errorMessage = "Не удалось получить данные от сервера";
		}
		$errorMessage = nextpayXMLClient_htmlEncode($errorMessage);
		nextpayXMLClient_errorMessage($errorMessage);
		return;
	}
	$key = 'error';
	if(array_key_exists($key, $productData))
	{
		$errorMessage = $productData['error'];
		nextpayXMLClient_errorMessage($errorMessage);
		return;
	}
	$productName = $productData['name'];
	$productName = nextpayXMLClient_htmlEncode($productName);

	$action = null;
	nextpayXMLClient_getRequestIntegerValue("action", "", $action, false);
	$initAction = nextpayXMLClient_isInitAction();

	$currencyId = null;
	if(!nextpayXMLClient_getRequestIntegerValue("currency", "Валюта", $currencyId, false))
	{
		if($initAction)
		{
			$currencyId = nextpayXMLClient_getSavedParam("payment_system", NEXTPAY_XML_CLIENT_DEFAULT_CURRENCY);
		}
		else
		{
			$currencyId = NEXTPAY_XML_CLIENT_DEFAULT_CURRENCY;
		}
		$currencyId = intval($currencyId);
	}

	$errorMessage = null;
	$rates = nextpayXMLClient_getRates($errorMessage);
	if($rates == null)
	{
		if($errorMessage == null)
		{
			$errorMessage = "Не удалось получить данные от сервера";
		}
		$errorMessage = nextpayXMLClient_htmlEncode($errorMessage);
		nextpayXMLClient_errorMessage($errorMessage);
		return;
	}

	if(!array_key_exists($currencyId, $rates))
	{
		$currencyId = key($rates);
	}

	$currencyData = $rates[$currencyId];
	$index = "rate";
	if(!array_key_exists($index, $currencyData))
	{
		$errorMessage = "Неизвестен обменный курс для платежной системы. ID системы: $currencyId";
		nextpayXMLClient_errorMessage($errorMessage);
		return;
	}
	$exchangeRate = $currencyData[$index];
	if($exchangeRate <= 0)
	{
		$errorMessage = "Неверное значение для обменного курса платежной системы. ID системы: $currencyId";
		nextpayXMLClient_errorMessage($errorMessage);
		return;
	}


	if(!NEXTPAY_XML_CLIENT_NO_JS)
	{
		nextpayXMLClient_echoContent("<script type=\"text/javascript\">
		var ps_rates = new Array();
		var ps_commissionTypes = new Array();
		var ps_description = new Array();
		");
		foreach ($rates as $key => $value)
		{
			$c_rate = $value['rate'];
			$c_commissionType = $value['commission_type'];
			$c_description = $value['description'];
			$c_description = nextpayXMLClient_htmlEncode($c_description);
			nextpayXMLClient_echoContent("ps_rates[$key]= $c_rate;");
			nextpayXMLClient_echoContent("ps_commissionTypes[$key]= $c_commissionType;");
			nextpayXMLClient_echoContent("ps_description[$key]= \"$c_description\";");
		}
		nextpayXMLClient_echoContent("</script>");
	}


	$index = "description";
	$currencyDescr = $currencyData[$index];
	$currencyDescr = nextpayXMLClient_htmlEncode($currencyDescr);
	nextpayXMLClient_onCurrencyChangeJS();

	$label = null;
	$costFieldIsReadonly = null;
	nextpayXMLClient_getFormSpecificLogic($productData, $label, $costFieldIsReadonly);

	$costGeneral = nextpayXMLClient_getRequestValue("cost");
	$costGeneral = str_replace(",", ".", $costGeneral);
	$costGeneral = floatval($costGeneral);
	$costGeneral = nextpayXMLClient_formatCostValue($costGeneral);

	$commissionType = $currencyData['commission_type'];

	$cost = null;
	if($costGeneral != null)
	{
		$amount = $exchangeRate * $costGeneral;
		if($commissionType == 2)//Брать все с покупателя
		{
			$amount = nextpayXMLClient_addOnePercent($amount);
		}
		else
		{
			$amount = nextpayXMLClient_formatCostValue($amount);
		}
		$cost = $amount." ".$currencyDescr;
	}

	$actionParam = NEXTPAY_XML_CLIENT_ACTION_BUY;
	$reqFieldMark = NEXTPAY_XML_CLIENT_REQ_FIELD_MARK;
	nextpayXMLClient_echoContent("
	<form action='' name='nextpayXMLClient_buy_form' method='post'><div>
	<input type='hidden' name='action' value='$actionParam'/>");

	nextpayXMLClient_kiwiBillForm($currencyId);

	nextpayXMLClient_TERMUABillForm($currencyId);

	$mcInfo = $productData['mc_info'];
	nextpayXMLClient_mcBillForm($currencyId, $mcInfo);
	nextpayXMLClient_mc2BillForm($currencyId);
	nextpayXMLClient_mc4BillForm($currencyId);
	nextpayXMLClient_mc5BillForm($currencyId);
	nextpayXMLClient_mc6BillForm($currencyId);


	nextpayXMLClient_echoContent("<div class='nxc_container'>
	<div class='nxc_row'>
		<div class='nxc_key'>
			Продукт
		</div>
		<div class='nxc_value'>
			$productName
		</div>
	</div>");
	nextpayXMLClient_showFormAttributes($productData);
	nextpayXMLClient_echoContent("<div class='nxc_row'>
		<div class='nxc_key'>
			Оплата
		</div>
		<div class='nxc_value'>");
	nextpayXMLClient_showPaymentSystems($rates, $currencyId);
	nextpayXMLClient_echoContent("</div>
	</div>");
	if(!$costFieldIsReadonly)
	{
		$label .= " $reqFieldMark";
	}
	nextpayXMLClient_echoContent("
	<div class='nxc_row'>
		<div class='nxc_key'>
			$label
		</div>
		<div class='nxc_value'>");
	nextpayXMLClient_onCostChangeJS();
	if ($costFieldIsReadonly)
	{
		nextpayXMLClient_echoContent("
		<input type='hidden' id='cost' name='cost' value=\"$costGeneral\"/>
		$costGeneral");
		nextpayXMLClient_echoContent(" руб.");
	}
	else
	{
		$onkeyup = NEXTPAY_XML_CLIENT_NO_JS ? null : "onkeyup='nextpayXMLClient_onCostChange()'";
		nextpayXMLClient_echoContent("
		<input
		class='nxc_input' 
		type='text'
		$onkeyup
		id='cost'
		name='cost' 
		value=\"$costGeneral\"/>");
		nextpayXMLClient_echoContent(" руб.");
	}
	nextpayXMLClient_echoContent("</div>
	</div>	
	<div class='nxc_row'>	
		<div class='nxc_key'>
			К оплате $reqFieldMark$reqFieldMark
		</div>
		<div class='nxc_value'>
			<div id='nextpayXMLClient_order_cost'>$cost</div>");
	nextpayXMLClient_echoContent("</div>
		</div>
		</div>
		<div style='margin-top:10px'>
			<input type='submit' value='Продолжить'/>");
	if(NEXTPAY_XML_CLIENT_NO_JS)
	{
		nextpayXMLClient_echoContent(" <input type='submit' name='recalcbutton' value='Пересчитать'/>");
	}
	nextpayXMLClient_echoContent("
		</div>
	</div></form><div>
	<hr/>
	$reqFieldMark Поля обязательные для заполнения
	<br/>
	$reqFieldMark$reqFieldMark Расчет стоимости заказа производится в зависимости от поля \"Оплата\" по курсу 
	<br/>$reqFieldMark$reqFieldMark$reqFieldMark При оплате данной валютой взимается дополнительная комиссия</div>");
}


function nextpayXMLClient_getFormSpecificLogic($productData, &$label, &$costFieldIsReadonly)
{
	$formId = $productData['form_id'];
	$label = $productData['amount_text'];
	$label = nextpayXMLClient_htmlEncode($label);
	switch ($formId)
	{
		case NEXTPAY_XML_API_BILL_FORM:
			{
				$costFieldIsReadonly = true;
				break;
			}
		case NEXTPAY_XML_API_REPLENISH_BALANCE_FORM:
			{
				$costFieldIsReadonly = false;
				break;
			}
		default:
			{
				$costFieldIsReadonly = false;
				break;
			}
	}
}

function nextpayXMLClient_onCostChangeJS()
{
	if(!NEXTPAY_XML_CLIENT_NO_JS)
	{
		nextpayXMLClient_echoContent("
	<script type=\"text/javascript\">
	function nextpayXMLClient_onCostChange()
	{
		var obj = document.getElementById('cost');

		var paymentSysId = document.getElementById('currency').value;
		
		var rate = ps_rates[paymentSysId];
		
		var commissionType = ps_commissionTypes[paymentSysId];
		commissionType = parseInt(commissionType);
		
		var currencyDescr = ps_description[paymentSysId];

		var form = document.forms['nextpayXMLClient_buy_form'];
		var formElements = form.elements;
		var costElement = document.getElementById('nextpayXMLClient_order_cost');
		var value = obj.value;
		value = value.replace(',', '.');
		if(value == '')
		{
			costElement.innerHTML = '&nbsp;';
		}
		else if(isNaN(value))
		{
			costElement.innerHTML = '&nbsp;';
		}
		else if(value <= 0)
		{
			costElement.innerHTML = '&nbsp;';
		}
		else
		{			
			var x =  value * rate;
			y = null;
			if(commissionType == 2)
			{
				var y = parseInt(x * 100) / 100;
				if(y != x)
				{
					y += 0.01;
				}
				y = y.toFixed(2);
			}
			else
			{
				y = x.toFixed(2);
			}
			costElement.innerHTML = y + \" \" + currencyDescr;
		}
	}
	</script>");
	}
}

/**
 * Закачивает содержимое файла по URL в строку
 *
 * @param string $url URL файла
 * @param int $maxlength Макс длина файла
*  @param int $timeout Таймаут ожидания запроса. Должен быть > 0 или -1, чтобы действовать.
 * Если == -1, то используется значение NEXTPAY_XML_CLIENT_SERVER_TIMEOUT
 * @param bool $disableWarnings Не выводить сообщения об ошибках
 * @return string Содержимое файла или FALSE в случае неудачи
 */
function nextpayXMLClient_getFileContentByURL($url, $maxlength = null, $timeout = -1, $disableWarnings = true)
{
	$ret = null;
	if($timeout == -1)
	{
		$timeout = NEXTPAY_XML_CLIENT_SERVER_TIMEOUT;
	}

	if($timeout > 0)
	{
		ini_set('default_socket_timeout', $timeout);
	}
	$errorLevel = null;
	if($disableWarnings)
	{
		$errorLevel = error_reporting(E_ERROR);
	}

	$context = null;
	if($maxlength == null)
	{
		$ret = file_get_contents($url, False, $context);
	}
	else
	{
		$ret = file_get_contents($url, False, $context, 0, $maxlength);
	}
	if($disableWarnings)
	{
		error_reporting($errorLevel);
	}
	if($ret !== FALSE)
	{
		if($ret != null)
		{
			$ret = trim($ret);
		}
	}
	return $ret;
}


function nextpayXMLClient_getRates(&$errorMessage)
{
	$url = NEXTPAY_XML_CLIENT_SERVER_URL."?command=rates_product_xml&product_id=".NEXTPAY_XML_CLIENT_PRODUCT_ID;
	$xml = nextpayXMLClient_getFileContentByURL($url);
	if($xml === FALSE)
	{
		$errorMessage = "Не удалось установить соединение с сервером[1]";
		return null;
	}
	$vals = null;
	$index = null;
	$p = xml_parser_create();
	xml_parse_into_struct($p, $xml, $vals, $index);
	xml_parser_free($p);
	$ret = array();
	$currencyId = null;
	$currencyName = null;
	$currencyRate = null;
	$currencyDesc = null;
	$currency_min_order_amount = null;
	$commissionType = null;
	foreach ($vals as $value)
	{
		$array = $value;
		$tagName = $array['tag'];
		$tagName = strtolower($tagName);
		switch($tagName)
		{
			case "id":
				{
					$currencyId = $array['value'];
					$currencyId = intval($currencyId);
					$currencyData = array();
					$currencyData['name'] = $currencyName;
					$currencyData['rate'] = $currencyRate;
					$currencyData['description'] = $currencyDesc;
					$currencyData['min_order_amount'] = $currency_min_order_amount;
					$currencyData['commission_type'] = $commissionType;
					$ret[$currencyId] = $currencyData;
					break;
				}
			case "name":
				{
					$currencyName = $array['value'];
					$currencyName = nextpayXMLClient_convFromUTF($currencyName);
					break;
				}
			case "rate":
				{
					$currencyRate = $array['value'];
					break;
				}
			case "description":
				{
					$currencyDesc = $array['value'];
					$currencyDesc = nextpayXMLClient_convFromUTF($currencyDesc);
					break;
				}
			case "min_order_amount":
				{
					$currency_min_order_amount = @$array['value'];
					$currency_min_order_amount = floatval($currency_min_order_amount);
					break;
				}
			case "error":
				{
					$errorMessage = $array['value'];
					$errorMessage = nextpayXMLClient_convFromUTF($errorMessage);
					return null;
				}
			case "commission_type":
				{
					$commissionType = $array['value'];
					$commissionType = intval($commissionType);
					break;
				}
			default:
				{
					break;
				}
		}
	}
	if(count($ret) == 0)
	{
		$errorMessage = "Для продукта не задан список платежных систем";
		return null;
	}
	else
	{
		return $ret;
	}
}

function nextpayXMLClient_getProductData(&$errorMessage)
{
	$url = NEXTPAY_XML_CLIENT_SERVER_URL."?command=product_xml&product_id=".NEXTPAY_XML_CLIENT_PRODUCT_ID;
	$xml = nextpayXMLClient_getFileContentByURL($url);
	if($xml === FALSE)
	{
		$errorMessage = "Не удалось установить соединение с сервером[2]";
		return null;
	}
	$vals = null;
	$index = null;
	$p = xml_parser_create();
	xml_parse_into_struct($p, $xml, $vals, $index);
	xml_parser_free($p);
	$ret = array();
	$formAttributes = array();
	$formAttribuiteId = null;
	foreach ($vals as $key => $value)
	{
		$array = $value;
		$tagName = $array['tag'];
		$tagName = strtolower($tagName);
		switch($tagName)
		{
			case "countable":
			case "mc_info":
			case "error":
			case "form_id":
			case "name":
			case "amount_text":
				{
					$value = $array['value'];
					$value = nextpayXMLClient_convFromUTF($value);
					$ret[$tagName] = $value;
					break;
				}
			case "form-attribute":
				{
					$key = 'attributes';
					if(array_key_exists($key, $array))
					{
						$formAttribuiteId = $array[$key]['ID'];
						$attributeData = array();
						$attributeData['id'] =  $formAttribuiteId;
						$attributeData['type'] =  $array[$key]['TYPE'];
						$attributeData['required'] =  $array[$key]['REQUIRED'];
						$attributeData['hidden'] =  $array[$key]['HIDDEN'];
						$attributeData['name'] =  $array[$key]['NAME'];
						$formAttributes[$formAttribuiteId] = $attributeData;
					}
					break;
				}
			case "form-attribute-label":
			case "form-attribute-default-value":
			case "form-attribute-values-array":
			case "form-attribute-labels-array":
				{
					if(array_key_exists($formAttribuiteId, $formAttributes))
					{
						$attributeData = $formAttributes[$formAttribuiteId];
						$val = @$array['value'];
						$val = nextpayXMLClient_convFromUTF($val);
						$attributeData[$tagName] = $val;
						$formAttributes[$formAttribuiteId] = $attributeData;
					}
					break;
				}
			default:
				{
					break;
				}
		}
	}
	$ret['form_attributes'] = $formAttributes;
	return $ret;
}

function nextpayXMLClient_getFormAttributes($productData, &$url)
{
	$formAttributes = $productData['form_attributes'];
	foreach ($formAttributes as $row)
	{
		$attrName = $row["name"];
		$attrNameenc = urlencode($attrName);
		$attrValue = nextpayXMLClient_getRequestValue($attrName);
		$attrValueenc = nextpayXMLClient_prepareURLParam($attrValue);
		$url .= "&$attrNameenc=$attrValueenc";
		nextpayXMLClient_setSavedParam("order_param_$attrName", $attrValue);
	}
}

function nextpayXMLClient_showFormAttributes($productData)
{
	$initAction = nextpayXMLClient_isInitAction();

	$formAttributes = $productData['form_attributes'];
	$reqField = NEXTPAY_XML_CLIENT_REQ_FIELD_MARK;
	foreach ($formAttributes as $key => $row)
	{
		$attrId = $key;
		$attrId = intval($attrId);

		$attrType = $row["type"];
		$attrType = intval($attrType);

		$attrRequired = $row["required"];
		$attrRequired = intval($attrRequired);

		$attrHidden = $row["hidden"];
		$attrHidden = intval($attrHidden);

		$attrName = $row["name"];
		$attrName = nextpayXMLClient_htmlEncode($attrName);

		$attrLabel = $row["form-attribute-label"];
		$attrLabel = nextpayXMLClient_htmlEncode($attrLabel);

		$attrDefaultValue = $row["form-attribute-default-value"];
		$attrDefaultValue = nextpayXMLClient_htmlEncode($attrDefaultValue);

		$attrValues = $row["form-attribute-values-array"];

		$attrLabels = $row["form-attribute-labels-array"];

		$attrValue = nextpayXMLClient_getRequestValue($attrName, false);
		if($attrValue == null)
		{
			if($initAction && !$attrHidden)
			{
				$attrValue = nextpayXMLClient_getSavedParam("order_param_$attrName", $attrDefaultValue);
			}
			else
			{
				$attrValue = $attrDefaultValue;
			}
		}
		$attrValueEnc = nextpayXMLClient_htmlEncode($attrValue);

		if($attrHidden)
		{
			nextpayXMLClient_echoContent("<div><div>
				<input type=\"hidden\" id=\"$attrName\" name=\"$attrName\" value=\"$attrValueEnc\"/>
			</div></div>");
		}
		else
		{
			nextpayXMLClient_echoContent("<div class='nxc_row'><div class='nxc_key'>$attrLabel");
			if($attrRequired)
			{
				nextpayXMLClient_echoContent(" ");
				nextpayXMLClient_echoContent($reqField);
			}
			nextpayXMLClient_echoContent("</div><div class='nxc_value'>");
			switch ($attrType)
			{
				case NEXTPAY_XML_API_ATTR_TYPE_ARRAY:
					{
						nextpayXMLClient_showSelectOptions($attrName, $attrValue, $attrValues, $attrLabels, $attrLabel);
						break;
					}
				default:
					{
						nextpayXMLClient_echoContent(
						"<input
				class='nxc_input'
				title=\"$attrLabel\"  
				id=\"$attrName\" 
				name=\"$attrName\" 
				value=\"$attrValueEnc\"/>");
						break;
					}
			}
			nextpayXMLClient_echoContent("</div></div>");
		}
	}
}

function nextpayXMLClient_getMultiValues($string)
{
	$ret = explode(NEXTPAY_XML_API_MULTI_OPTIONS_SEPATATOR, $string);
	return $ret;
}

function nextpayXMLClient_showSelectOptions($attrName, $selectedValue, $valuesString, $labelsString, $attrLabel)
{
	$values = nextpayXMLClient_getMultiValues($valuesString);
	$labels = nextpayXMLClient_getMultiValues($labelsString);
	if(count($values) == count($labels))
	{
		$attrName = nextpayXMLClient_htmlEncode($attrName);
		$selectedValueEnc = nextpayXMLClient_htmlEncode($selectedValue);
		nextpayXMLClient_echoContent(
		"<select class='nxc_input' title=\"$attrLabel\" id=\"$attrName\" name=\"$attrName\" value=\"$selectedValueEnc\">");
		for($i = 0; $i < count($values); $i++)
		{
			$label = $labels[$i];
			$label = nextpayXMLClient_htmlEncode($label);
			$value = $values[$i];
			$valueEnc = nextpayXMLClient_htmlEncode($value);
			nextpayXMLClient_echoContent("<option value=\"$valueEnc\"");
			if($selectedValue == $value)
			{
				nextpayXMLClient_echoContent(" selected ");
			}
			nextpayXMLClient_echoContent(">$label</option>");
		}
		nextpayXMLClient_echoContent("</select>");
	}
	else
	{
		nextpayXMLClient_echoContent("nextpayXMLClient_showSelectOptions error.
		labels count != values count");
	}
}

function nextpayXMLClient_getServerResponse($url)
{
	$xml = nextpayXMLClient_getFileContentByURL($url);
	if($xml === FALSE)
	{
		nextpayXMLClient_errorMessage("Не удалось установить соединение с сервером[3]");
		return null;
	}
	$vals = null;
	$index = null;
	$p = xml_parser_create();
	xml_parse_into_struct($p, $xml, $vals, $index);
	xml_parser_free($p);
	$ret = array();
	foreach ($vals as $value)
	{
		$array = $value;
		$tagName = $array['tag'];
		$tagName = strtolower($tagName);
		switch($tagName)
		{
			case "result":
				{
					$result = $array['value'];
					$result = intval($result);
					$ret['result'] = $result;
					break;
				}
			case "data":
				{
					$data = @$array['value'];
					$ret['data'] = $data;
					break;
				}
			case "comment":
				{
					$comment = $array['value'];
					$comment = nextpayXMLClient_convFromUTF($comment);
					$ret['comment'] = $comment;
					break;
				}
			default:
				{
					break;
				}
		}
	}
	return $ret;
}





function nextpayXMLClient_convFromUTF($text)
{
	$text = iconv("UTF-8", NEXTPAY_XML_CLIENT_SOURCE_SCRIPT_CHARACTER_SET, $text);
	return $text;
}

function nextpayXMLClient_showPaymentSystems($rates, $selectedCurrencyId)
{
	$onchange = NEXTPAY_XML_CLIENT_NO_JS ? null : "onchange='nextpayXMLClient_onCurrencyChange()'";
	nextpayXMLClient_echoContent("<select class='nxc_input' id='currency' name='currency' $onchange>");
	foreach ($rates as $key => $value)
	{
		$array = $value;
		$currencyName = $array['name'];
		$currencyName = nextpayXMLClient_htmlEncode($currencyName);
		$currencyRate = $array['rate'];
		$currencyRate = 1 / $currencyRate;
		$currencyRate = nextpayXMLClient_formatCostValue($currencyRate);
		if($selectedCurrencyId == $key)
		{
			nextpayXMLClient_echoContent("<option value='$key' selected>$currencyName</option>");
		}
		else
		{
			nextpayXMLClient_echoContent("<option value='$key'>$currencyName</option>");
		}
	}
	nextpayXMLClient_echoContent("</select>");
}


/**
 * Считывает значение целочисленного параметра в запросе
 * 
 * @param string $name Имя параметра в запросе
 * @param string $fieldName Имя параметра для отображения ошибки
 * @param boolean $mySQLEscape экранировать или нет значение параметра,
 * c помощью mysql_escape_string при считывании
 * @param Значение параметра, если параметр передан
 * и удалось привести его к целому числу
 * @return bool true в случае успеха, false в случае неудачи
 */
function nextpayXMLClient_getRequestIntegerValue($name, $fieldName, &$value, $printErrorMessage = true)
{
	$value = nextpayXMLClient_getRequestStringValue($name, $fieldName, true, $printErrorMessage);
	if($value == null)
	{
		return false;
	}
	else
	{
		if($value == "0")
		{
			$value = 0;
			return true;
		}
		else
		{
			$value = intval($value);
			if($value == 0)
			{
				if($printErrorMessage)
				{
					nextpayXMLClient_errorMessage("Поле '".$fieldName."' не заполнено!");
				}
				return false;
			}
			else
			{
				return true;
			}
		}
	}
}

function nextpayXMLClient_getRequestValue($paramName, $trimInput = true)
{
	if(isset($_REQUEST[$paramName]))
	{
		$ret =  $_REQUEST[$paramName];
		if($ret != NULL)
		{
			if($trimInput)
			{
				$ret = trim($ret);
			}
		}
		$ret = iconv(NEXTPAY_XML_CLIENT_REQUEST_CHARACTER_SET, NEXTPAY_XML_CLIENT_SOURCE_SCRIPT_CHARACTER_SET, $ret);
		return $ret;
	}
	else
	{
		return NULL;
	}
}

function nextpayXMLClient_getRequestStringValue($name, $fieldName, $trimInput = true, $printErrorMessage = true)
{
	$value = nextpayXMLClient_getRequestValue($name, $trimInput);
	$ret = nextpayXMLClient_checkStringValue($value, $fieldName, $printErrorMessage);
	if($ret)
	{
		return $value;
	}
	else
	{
		return null;
	}
}

function nextpayXMLClient_checkStringValue($value, $fieldName, $printErrorMessage = true)
{
	if($value == "" || $value == null)
	{
		if($printErrorMessage)
		{
			nextpayXMLClient_errorMessage ("Поле '".$fieldName."' не заполнено!");
		}
		return false;
	}
	else
	{
		return true;
	}
}

function nextpayXMLClient_htmlEncode($string)
{
	return htmlspecialchars($string, ENT_COMPAT, NEXTPAY_XML_CLIENT_SOURCE_SCRIPT_CHARACTER_SET);
}


function nextpayXMLClient_errorMessage($message)
{
	nextpayXMLClient_echoContent("<div class='nxc_error'>$message</div>");
}

function nextpayXMLClient_formatCostValue($value)
{
	return number_format($value, 2, '.', '');
}


function nextpayXMLClient_onCurrencyChangeJS()
{
	if(!NEXTPAY_XML_CLIENT_NO_JS)
	{
		nextpayXMLClient_echoContent("
		<script type=\"text/javascript\">
		function nextpayXMLClient_onCurrencyChange()
		{
			nextpayXMLClient_onCostChange();
			var paymentSysId = document.getElementById('currency').value;
			paymentSysId = parseInt(paymentSysId);
			var ids = ['mc_bill_form', 'mc_2_bill_form', 'mc_4_bill_form', 'mc_5_bill_form', 'mc_6_bill_form', 'termua_form', 'qiwi_form'];
			for(var i = 0; i < ids.length; i++)
			{
				document.getElementById(ids[i]).style.display = 'none';
			}
			var id = null;
			switch(paymentSysId)
			{
				case ".NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY.":
				case ".NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY_2.":
				case ".NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY_3.":	
				{
					id = 'qiwi_form';
					break;
				}
				case ".NEXTPAY_XML_CLIENT_MC_CURRENCY.":
				{
					id = 'mc_bill_form';
					break;
				}
				case ".NEXTPAY_XML_CLIENT_MC_2_CURRENCY.":
				{
					id = 'mc_2_bill_form';
					break;
				}
				case ".NEXTPAY_XML_CLIENT_MC_3_CURRENCY.":
				{
					id = 'mc_2_bill_form';
					break;
				}
				case ".NEXTPAY_XML_CLIENT_MC_4_CURRENCY.":
				case ".NEXTPAY_XML_CLIENT_MC_84_CURRENCY.":				
				{
					id = 'mc_4_bill_form';
					break;
				}
				case ".NEXTPAY_XML_CLIENT_MC_5_CURRENCY.":
				case ".NEXTPAY_XML_CLIENT_MC_86_CURRENCY.":
				{
					id = 'mc_5_bill_form';
					break;
				}
				case ".NEXTPAY_XML_CLIENT_MC_6_CURRENCY.":
				{
					id = 'mc_6_bill_form';
					break;
				}
				case ".NEXTPAY_XML_CLIENT_TERMUA_CURRENCY.":
				{
					id = 'termua_form';
					break;
				}
			}
			if(id != null)
			{
				document.getElementById(id).style.display = '';
			}
		}
		</script>");
	}
}


function nextpayXMLClient_printKIWIBill($data)
{
	nextpayXMLClient_echoContent($data);
}

function nextpayXMLClient_printTERMUABill($data)
{
	nextpayXMLClient_echoContent($data);
}


function nextpayXMLClient_kiwiBillForm($currencyId)
{
	$initAction = nextpayXMLClient_isInitAction();
	$inputName =  "kiwi_bill_to_account";
	$toAccount = nextpayXMLClient_getRequestValue($inputName);
	if($initAction && $toAccount == null)
	{
		$toAccount = nextpayXMLClient_getSavedParam("kiwi_account", null);
	}
	$toAccount = nextpayXMLClient_htmlEncode($toAccount);
	$reqSymbol = NEXTPAY_XML_CLIENT_REQ_FIELD_MARK;
	$cssStyle = "style='display:none'";
	switch ($currencyId)
	{
		case NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY:
		case NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY_2:
		case NEXTPAY_XML_CLIENT_KIWI_MOBILE_CURRENCY_3:
			{
				$cssStyle = null;
				break;
			}
	}
	nextpayXMLClient_echoContent(
	"<div $cssStyle id='qiwi_form'><div class='nxc_container'>
		<div class='nxc_row'>
			<div class='nxc_key'>
				Номер телефона $reqSymbol
			</div>
			<div class='nxc_value'>
				+<input 
					class='nxc_input'
				 	type=\"text\" 
				 	name=\"$inputName\" 
				 	value=\"$toAccount\"/>					
			</div>
		</div>	
	</div>
	<div style='margin-top:5px'>
	Введите номер абонента в международном формате без знака + Например 79101234567 или 380123456789
	(Ваш логин в личном кабинете QIWI. Если Вы не зарегистрированны в личном кабинете, 
	то можете зарегистрироваться в самом терминале и Вам придёт SMS сообщение с паролем 
	для входа в личный кабинет).
	</div><br/></div>");
}

function nextpayXMLClient_TERMUABillForm($currencyId)
{
	$cssStyle = "style='display:none'";
	switch ($currencyId)
	{
		case NEXTPAY_XML_CLIENT_TERMUA_CURRENCY:
			{
				$cssStyle = null;
				break;
			}
	}

	$initAction = nextpayXMLClient_isInitAction();
	$inputName =  "termua_bill_to_account";
	$toAccountLen = 12;
	$toAccount = nextpayXMLClient_getRequestValue($inputName);
	if($initAction && $toAccount == null)
	{
		$toAccount = nextpayXMLClient_getSavedParam("termua_account", null);
	}
	$toAccount = nextpayXMLClient_htmlEncode($toAccount);
	$reqSymbol = NEXTPAY_XML_CLIENT_REQ_FIELD_MARK;
	nextpayXMLClient_echoContent(
	"<div $cssStyle id='termua_form'><div class='nxc_container'>
		<div class='nxc_row'>
			<div class='nxc_key'>
				Номер телефона $reqSymbol
			</div>
			<div class='nxc_value'>
			 	<input
			 	class='nxc_input' 
			 	size='$toAccountLen'
			 	maxlength='$toAccountLen' 
			 	type=\"text\" 
			 	name=\"$inputName\" 
			 	value=\"$toAccount\"/>						
			</div>
		</div>
	</div>
	<div style='margin-top:5px'>Введите номер Вашего мобильного телефона в международном формате, например, 380501234567, $toAccountLen цифр, с кодом страны и без пробелов</div>
	<br/></div>");
}

function nextpayXMLClient_mcBillForm($currencyId, $info)
{
	$cssStyle = "style='display:none'";
	switch ($currencyId)
	{
		case NEXTPAY_XML_CLIENT_MC_CURRENCY:
			{
				$cssStyle = null;
				break;
			}
	}

	$initAction = nextpayXMLClient_isInitAction();
	$inputName =  "mc_bill_to_account";
	$toAccountLen = 11;
	$toAccount = nextpayXMLClient_getRequestValue($inputName);
	if($initAction && $toAccount == null)
	{
		$toAccount = nextpayXMLClient_getSavedParam("mk_account", null);
	}
	$toAccount = nextpayXMLClient_htmlEncode($toAccount);
	$reqSymbol = NEXTPAY_XML_CLIENT_REQ_FIELD_MARK;
	nextpayXMLClient_echoContent(
	"<div $cssStyle id='mc_bill_form'><div class='nxc_container'>
		<div class='nxc_row'>
			<div class='nxc_key'>
				Номер телефона $reqSymbol
			</div>
			<div class='nxc_value'>
			 	<input 
			 	class='nxc_input'
			 	size='$toAccountLen'
			 	maxlength='$toAccountLen' 
			 	type=\"text\" 
			 	name=\"$inputName\" 
			 	value=\"$toAccount\"/>						
			</div>
		</div>
	</div>
	<div style='margin-top:5px;'>
		<div>Введите Номер абонента в международном формате, 11 цифр, первая цифра 7, например 79150000000</div>
		<div style='margin-top:5px'>$info</div>
	</div><br/></div>");
}


function nextpayXMLClient_mc4BillForm($currencyId)
{
	$cssStyle = "style='display:none'";
	switch ($currencyId)
	{
		case NEXTPAY_XML_CLIENT_MC_4_CURRENCY:
		case NEXTPAY_XML_CLIENT_MC_84_CURRENCY:
			{
				$cssStyle = null;
				break;
			}
	}

	$initAction = nextpayXMLClient_isInitAction();
	$inputName =  "mc_bill_to_account_71";
	$toAccount = nextpayXMLClient_getRequestValue($inputName);
	if($initAction && $toAccount == null)
	{
		$toAccount = nextpayXMLClient_getSavedParam("mk_account_10", null);
	}
	$toAccount = nextpayXMLClient_htmlEncode($toAccount);
	$reqSymbol = NEXTPAY_XML_CLIENT_REQ_FIELD_MARK;
	nextpayXMLClient_echoContent(
	"<div $cssStyle id='mc_4_bill_form'><div class='nxc_container'>
		<div class='nxc_row'>
			<div class='nxc_key'>
				Номер телефона $reqSymbol
			</div>
			<div class='nxc_value'>
			 	<input 
			 	class='nxc_input'
			 	type=\"text\" 
			 	name=\"$inputName\" 
			 	value=\"$toAccount\"/>						
			</div>
		</div>
	</div>
	<div style='margin-top:5px;'>
		<div>Введите Номер абонента в формате 10 цифр, без кода страны и 8-ки, например 9150000000</div>
	</div><br/></div>");
}


function nextpayXMLClient_mc5BillForm($currencyId)
{
	$cssStyle = "style='display:none'";
	switch ($currencyId)
	{
		case NEXTPAY_XML_CLIENT_MC_5_CURRENCY:
		case NEXTPAY_XML_CLIENT_MC_86_CURRENCY:
			{
				$cssStyle = null;
				break;
			}
	}

	$initAction = nextpayXMLClient_isInitAction();
	$inputName =  "mc_bill_to_account_79";
	$toAccount = nextpayXMLClient_getRequestValue($inputName);
	if($initAction && $toAccount == null)
	{
		$toAccount = nextpayXMLClient_getSavedParam("mk_account", null);
	}
	$toAccount = nextpayXMLClient_htmlEncode($toAccount);
	$reqSymbol = NEXTPAY_XML_CLIENT_REQ_FIELD_MARK;
	nextpayXMLClient_echoContent(
	"<div $cssStyle id='mc_5_bill_form'><div class='nxc_container'>
		<div class='nxc_row'>
			<div class='nxc_key'>Номер телефона $reqSymbol</div>
			<div class='nxc_value'>
			 	<input 
			 	class='nxc_input'
			 	type=\"text\" 
			 	name=\"$inputName\" 
			 	value=\"$toAccount\"/>						
			</div>
		</div>
	</div>
	<div style='margin-top:5px;'>
		<div>Введите Номер абонента в международном формате, 11 цифр, первая цифра 7, например 79150000000</div>
	</div><br/></div>");
}

function nextpayXMLClient_mc6BillForm($currencyId)
{
	$cssStyle = "style='display:none'";
	switch ($currencyId)
	{
		case NEXTPAY_XML_CLIENT_MC_6_CURRENCY:
			{
				$cssStyle = null;
				break;
			}
	}

	$initAction = nextpayXMLClient_isInitAction();
	$inputName =  "mc_bill_to_account_81";
	$toAccount = nextpayXMLClient_getRequestValue($inputName);
	if($initAction && $toAccount == null)
	{
		$toAccount = nextpayXMLClient_getSavedParam("mk_account", null);
	}
	$toAccount = nextpayXMLClient_htmlEncode($toAccount);
	$reqSymbol = NEXTPAY_XML_CLIENT_REQ_FIELD_MARK;
	nextpayXMLClient_echoContent(
	"<div $cssStyle id='mc_6_bill_form'><div class='nxc_container'>
		<div class='nxc_row'>
			<div class='nxc_key'>Номер телефона $reqSymbol</div>
			<div class='nxc_value'>
			 	<input 
			 	class='nxc_input'
			 	type=\"text\" 
			 	name=\"$inputName\" 
			 	value=\"$toAccount\"/>						
			</div>
		</div>
	</div>
	<div style='margin-top:5px;'>
		<div>Введите Номер абонента в международном формате, 11 цифр, первая цифра 7, например 79150000000</div>
	</div><br/></div>");
}



function nextpayXMLClient_mc2BillForm($currencyId)
{
	$cssStyle = "style='display:none'";
	switch ($currencyId)
	{
		case NEXTPAY_XML_CLIENT_MC_2_CURRENCY:
		case NEXTPAY_XML_CLIENT_MC_3_CURRENCY:
			{
				$cssStyle = null;
				break;
			}
	}

	$initAction = nextpayXMLClient_isInitAction();
	$inputName =  "mc_2_bill_to_account";
	$toAccountLen = 11;
	$toAccount = nextpayXMLClient_getRequestValue($inputName);
	if($initAction && $toAccount == null)
	{
		$toAccount = nextpayXMLClient_getSavedParam("mk_account", null);
	}
	$toAccount = nextpayXMLClient_htmlEncode($toAccount);
	$reqSymbol = NEXTPAY_XML_CLIENT_REQ_FIELD_MARK;
	nextpayXMLClient_echoContent(
	"<div $cssStyle id='mc_2_bill_form'><div class='nxc_container'>
		<div class='nxc_row'>
			<div class='nxc_key'>
				Номер телефона $reqSymbol
			</div>
			<div class='nxc_value'>
			 	<input 
			 	class='nxc_input'
			 	size='$toAccountLen'
			 	maxlength='$toAccountLen' 
			 	type=\"text\" 
			 	name=\"$inputName\" 
			 	value=\"$toAccount\"/>						
			</div>
		</div>
	</div>
	<div style='margin-top:5px;'>
		<div>Введите Номер абонента в международном формате, 11 цифр, первая цифра 7, например 79150000000</div>
	</div><br/></div>");
}


function nextpayXMLClient_printMCBill($data)
{
	nextpayXMLClient_echoContent($data);
}

function nextpayXMLClient_echoContent($string, $encode = true)
{
	if(NEXTPAY_XML_CLIENT_ECHO_IN_STRING)
	{
		global $nextpayXMLClientResponse;
		$nextpayXMLClientResponse .= $string;
	}
	else
	{
		if($encode)
		{
			$string = iconv(NEXTPAY_XML_CLIENT_SOURCE_SCRIPT_CHARACTER_SET, NEXTPAY_XML_CLIENT_REQUEST_CHARACTER_SET, $string);
		}
		echo $string;
	}
}

/**
 * Подготавливает параметр к передаче в запрос на сервер nextpay через URL
 *
 * nextpay всегда принимает данные в запросе в кодировке windows-1251! 
 * 
 * @param string $paramValue Параметр (имя или значение)
 * @return string Подготовленный параметр
 */
function nextpayXMLClient_prepareURLParam(&$paramValue)
{
	$paramValue = iconv(NEXTPAY_XML_CLIENT_SOURCE_SCRIPT_CHARACTER_SET, 'windows-1251', $paramValue);
	$paramValue = urlencode($paramValue);
	return $paramValue;
}

function nextpayXMLClient_getSavedParam($paramName, $paramValue)
{
	if(NEXTPAY_XML_CLIENT_REMEMBER_ME)
	{
		$cookieName = "nextpay_order_$paramName";
		$cookie = @$_COOKIE[$cookieName];
		$cookie = urldecode($cookie);
		if($cookie == null)
		{
			return $paramValue;
		}
		else
		{
			return $cookie;
		}
	}
	else
	{
		return $paramValue;
	}
}

function nextpayXMLClient_setSavedParam($paramName, $paramValue)
{
	if(NEXTPAY_XML_CLIENT_REMEMBER_ME)
	{
		$paramValue = rawurlencode($paramValue);
		$cookieName = "nextpay_order_$paramName";
		$time = time() + 60 * 60 * 24 * 365;//1  год
		$time = date("D, d-M-Y H:i:s GMT", $time);
		$domain = NEXTPAY_XML_CLIENT_COOKIE_DOMAIN == null ? null : "domain=".NEXTPAY_XML_CLIENT_COOKIE_DOMAIN;
		echo "<script type=\"text/javascript\">document.cookie = '$cookieName' + \"=\" + '$paramValue' + \";expires=$time;$domain;path=/\";</script>";
	}
}

function nextpayXMLClient_isInitAction()
{
	return !isset($_REQUEST['action']) && !isset($_REQUEST['recalcbutton']);
}

function nextpayXMLClient_addOnePercent($x)
{
	$y = intval($x * 100) / 100;
	if($x == $y)
	{
		return $x;
	}
	else
	{
		$y += 0.01;
		$y = number_format($y, 2, '.', '');
		return $y;
	}
}
//Вызов скрипта
nextpayXMLClient_Main();
?>