<?php

defined('_JEXEC') or die('Restricted access');
define('COINGATE_VIRTUEMART_EXTENSION_VERSION', '1.0.2');

require_once('lib/CoinGate/init.php');

if (!class_exists('vmPSPlugin'))
  require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');

class plgVmPaymentCoingate extends vmPSPlugin
{
  function __construct(&$subject, $config)
  {
    parent::__construct($subject, $config);

    $this->_loggable   = true;
    $this->tableFields = array_keys($this->getTableSQLFields());
    $varsToPush        = $this->getVarsToPush();

    $this->setConfigParameterable($this->_configTableFieldName, $varsToPush);
  }

  public function getVmPluginCreateTableSQL()
  {
    return $this->createTableSQL('Payment CoinGate Table');
  }

  function getTableSQLFields()
  {
    $SQLfields = array(
      'id'                          => 'int(1) UNSIGNED NOT NULL AUTO_INCREMENT',
      'virtuemart_order_id'         => 'int(1) UNSIGNED',
      'order_number'                => 'char(64)',
      'virtuemart_paymentmethod_id' => 'mediumint(1) UNSIGNED',
      'payment_name'                => 'varchar(5000)',
      'payment_order_total'         => 'decimal(15,5) NOT NULL DEFAULT \'0.00000\'',
      'payment_currency'            => 'char(3)',
      'logo'                        => 'varchar(5000)'
    );

    return $SQLfields;
  }

  function getCosts(VirtueMartCart $cart, $method, $cart_prices)
  {
    return 0;
  }

  protected function checkConditions($cart, $method, $cart_prices)
  {
    return true;
  }

  function plgVmOnStoreInstallPaymentPluginTable($jplugin_id)
  {
    return $this->onStoreInstallPluginTable($jplugin_id);
  }

  public function plgVmonSelectedCalculatePricePayment(VirtueMartCart $cart, array &$cart_prices, &$cart_prices_name)
  {
    return $this->onSelectedCalculatePrice($cart, $cart_prices, $cart_prices_name);
  }

  function plgVmgetPaymentCurrency($virtuemart_paymentmethod_id, &$paymentCurrencyId)
  {
    if (!($method = $this->getVmPluginMethod($virtuemart_paymentmethod_id)))
      return NULL;

    if (!$this->selectedThisElement($method->payment_element))
      return false;

    $this->getPaymentCurrency($method);

    $paymentCurrencyId = $method->payment_currency;

    return;
  }

  function plgVmOnCheckAutomaticSelectedPayment(VirtueMartCart $cart, array $cart_prices = array(), &$paymentCounter)
  {
      return $this->onCheckAutomaticSelected($cart, $cart_prices, $paymentCounter);
  }

  public function plgVmOnShowOrderFEPayment($virtuemart_order_id, $virtuemart_paymentmethod_id, &$payment_name)
  {
      $this->onShowOrderFE($virtuemart_order_id, $virtuemart_paymentmethod_id, $payment_name);
  }

  function plgVmonShowOrderPrintPayment($order_number, $method_id)
  {
      return $this->onShowOrderPrint($order_number, $method_id);
  }

  function plgVmDeclarePluginParamsPayment($name, $id, &$data)
  {
      return $this->declarePluginParams('payment', $name, $id, $data);
  }

  function plgVmDeclarePluginParamsPaymentVM3( &$data) {
      return $this->declarePluginParams('payment', $data);
  }

  function plgVmSetOnTablePluginParamsPayment($name, $id, &$table)
  {
      return $this->setOnTablePluginParams($name, $id, $table);
  }

  function plgVmOnPaymentNotification()
  {
    try {
      $callbackData = JRequest::get('post');

      if (!isset($callbackData['order_id']))
        throw new Exception('order_id was not found in callback');

      $virtuemartOrderId = $callbackData['order_id'];

      $modelOrder = VmModel::getModel('orders');
      $order      = $modelOrder->getOrder($virtuemartOrderId);

      if (!$order)
        throw new Exception('Order #' . $callbackData['order_id'] . ' does not exists');
      $method = $this->getVmPluginMethod($order['details']['BT']->virtuemart_paymentmethod_id);

      if (!$this->selectedThisElement($method->payment_element))
        return false;


     $authentication = [
        'auth_token' => $method->auth_token,
        'environment' => $method->environment,
        'user_agent' => 'CoinGate - Joomla VirtueMart Extension v' . COINGATE_VIRTUEMART_EXTENSION_VERSION
     ];

      $cgOrder = \CoinGate\Merchant\Order::findOrFail($callbackData['id'], array(), $authentication);

      switch ($cgOrder->status) {
        case 'paid':
          $cgOrderStatus = $method->paid_status;
          $orderComment = 'CoinGate invoice was paid successfully.';
          break;
        case 'canceled':
          $cgOrderStatus = $method->canceled_status;
          $orderComment = 'CoinGate invoice was canceled by the user.';
          break;
        case 'expired':
          $cgOrderStatus = $method->expired_status;
          $orderComment = 'CoinGate invoice expired.';
          break;
        case 'invalid':
          $cgOrderStatus = $method->invalid_status;
          $orderComment = 'CoinGate invoice marked as invalid.';
          break;
        case 'refunded':
          $cgOrderStatus = $method->refunded_status;
          $orderComment = 'Payment refunded.';
          break;
        default:
          $cgOrderStatus = NULL;
          $orderComment = NULL;
      }

      if (!is_null($cgOrderStatus)) {
        $modelOrder                   = new VirtueMartModelOrders();
        $order['order_status']        = $cgOrderStatus;
        $order['virtuemart_order_id'] = $virtuemartOrderId;
        $order['customer_notified']   = 1;
        $order['comments']            = $cgOrderStatus;

        $modelOrder->updateStatusForOneOrder($virtuemartOrderId, $order, true);

      }
    } catch (Exception $e) {
      exit(get_class($e) . ': ' . $e->getMessage());
    }
  }

  function plgVmOnPaymentResponseReceived(&$html)
  {
    if (!class_exists ('VirtueMartCart'))
      require(JPATH_VM_SITE . DS . 'helpers' . DS . 'cart.php');

    if (!class_exists ('shopFunctionsF'))
      require(JPATH_VM_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');

    if (!class_exists ('VirtueMartModelOrders'))
      require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php');

    $virtuemart_paymentmethod_id = JRequest::getInt('pm', 0);
    $order_number                = JRequest::getString('on', 0);
    $vendorId                    = 0;

    if (!($method = $this->getVmPluginMethod($virtuemart_paymentmethod_id)))
      return NULL;

    if (!$this->selectedThisElement($method->payment_element))
      return NULL;

    if (!($virtuemart_order_id = VirtueMartModelOrders::getOrderIdByOrderNumber($order_number)))
      return NULL;

    if (!($paymentTable = $this->getDataByOrderId($virtuemart_order_id)))
      return '';

    $payment_name = $this->renderPluginName($method);
    $html         = $this->_getPaymentResponseHtml($paymentTable, $payment_name);

    return TRUE;
  }

  function plgVmDisplayListFEPayment (VirtueMartCart $cart, $selected = 0, &$htmlIn)
  {
    $session = JFactory::getSession();
    $errors  = $session->get('errorMessages', 0, 'vm');

    if($errors != "") {
      $errors = unserialize($errors);
      $session->set('errorMessages', "", 'vm');
    }
    else
      $errors = array();

    return $this->displayListFE($cart, $selected, $htmlIn);
  }

  public function getGMTTimeStamp()
  {
    $tz_minutes = date('Z') / 60;

    if ($tz_minutes >= 0)
      $tz_minutes = '+' . sprintf("%03d",$tz_minutes);

    $stamp = date('YdmHis000000') . $tz_minutes;

    return $stamp;
  }

  function plgVmConfirmedOrder($cart, $order)
  {
    if (!($method = $this->getVmPluginMethod($order['details']['BT']->virtuemart_paymentmethod_id)))
        return NULL;

    if (!$this->selectedThisElement($method->payment_element))
        return false;

    if (!class_exists('VirtueMartModelOrders'))
      require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php');

    if (!class_exists('VirtueMartModelCurrency'))
      require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'currency.php');

    VmConfig::loadJLang('com_virtuemart', true);
    VmConfig::loadJLang('com_virtuemart_orders', true);

    $orderID = $order['details']['BT']->virtuemart_order_id;
    $paymentMethodID = $order['details']['BT']->virtuemart_paymentmethod_id;

    $currency_code_3 = shopFunctions::getCurrencyByID($method->currency_id, 'currency_code_3');

    $paymentCurrency = CurrencyDisplay::getInstance($method->currency_id);
    $totalInCurrency = round($paymentCurrency->convertCurrencyTo($method->currency_id, $order['details']['BT']->order_total, false), 2);

    $description = array();
    foreach ($order['items'] as $item) {
      $description[] = $item->product_quantity . ' × ' . $item->order_item_name;
    }

     $plugin = JPluginHelper::getPlugin('vmpayment', 'coingate');
    $pluginParams = new JRegistry();
    $pluginParams->loadString($plugin->params);

    $authentication = [
      'auth_token' => $method->auth_token,
      'environment' => $method->environment,
      'user_agent' => 'CoinGate - Joomla VirtueMart Extension v' . COINGATE_VIRTUEMART_EXTENSION_VERSION
    ];

    $cgOrder = \CoinGate\Merchant\Order::createOrFail(array(
      'order_id'         => $orderID,
      'price_amount'     => $totalInCurrency,
      'price_currency'   => $currency_code_3,
      'receive_currency' => $method->receive_currency,
      'cancel_url'       => (JROUTE::_(JURI::root() . 'index.php?option=com_virtuemart&view=cart')),
      'callback_url'     => (JROUTE::_(JURI::root() . 'index.php?option=com_virtuemart&view=pluginresponse&task=pluginnotification&tmpl=component')),
      'success_url'      => (JROUTE::_(JURI::root() . 'index.php?option=com_virtuemart&view=pluginresponse&task=pluginresponsereceived&pm=' . $paymentMethodID)),
      'title'            => JFactory::getApplication()->getCfg('sitename'),
      'description'      => join($description, ', ')
    ), array(), $authentication);

    $cart->emptyCart();
    header('Location: ' . $cgOrder->payment_url);
    exit;
  }
}

defined('_JEXEC') or die('Restricted access');

if (!class_exists( 'VmConfig' ))
  require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');

if (!class_exists('ShopFunctions'))
  require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'shopfunctions.php');

defined('JPATH_BASE') or die();
