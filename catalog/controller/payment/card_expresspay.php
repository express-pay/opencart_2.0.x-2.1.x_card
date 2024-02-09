<?php
class ControllerPaymentCardExpressPay extends Controller {
	const TOKEN_PARAM_NAME                          = 'card_expresspay_token';
	const SERVICE_ID_PARAM_NAME                     = 'card_expresspay_service_id';
    const SECRET_WORD_PARAM_NAME                    = 'card_expresspay_secret_word';
    const USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME = 'card_expresspay_is_use_signature_for_notification';
    const SECRET_WORD_NOTIFICATION_PARAM_NAME       = 'card_expresspay_secret_word_for_notification';
    const NOTIFICATION_URL_PARAM_NAME               = 'card_expresspay_url_notification';
    const IS_TEST_MODE_PARAM_NAME                   = 'card_expresspay_is_test_mode';
	const API_URL_PARAM_NAME                        = 'card_expresspay_api_url';
	const SANDBOX_URL_PARAM_NAME                    = 'card_expresspay_sandbox_url';
    const INFO_PARAM_NAME                           = 'card_expresspay_info';
    const MESSAGE_SUCCESS_PARAM_NAME                = 'card_expresspay_message_success';
    const PROCESSED_STATUS_ID_PARAM_NAME            = 'card_expresspay_processed_status_id';
    const SUCCESS_STATUS_ID_PARAM_NAME              = 'card_expresspay_success_status_id';
    const FAIL_STATUS_ID_PARAM_NAME                 = 'card_expresspay_fail_status_id';

	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['redirect'] = $this->url->link('payment/card_expresspay/send');
		$data['text_loading'] = $this->language->get('text_loading');
        
		return $this->load->view('default/template/payment/card_expresspay.tpl', $data);
	}
	
	public function send() {
		$this->log_info('send', 'Initialization request for add invoice');
		$this->load->model('checkout/order');
		$orderId = $this->session->data['order_id'];
        $order_info = $this->model_checkout_order->getOrder($orderId);
        $amount = $this->currency->format($order_info['total'], $this->session->data['currency'], '', false);
        $amount = str_replace('.',',',$amount);
		
		$secret_word = $this->config->get(self::SECRET_WORD_PARAM_NAME);

        $signatureParams['token'] = $this->config->get(self::TOKEN_PARAM_NAME);
        $signatureParams['serviceId'] = $this->config->get(self::SERVICE_ID_PARAM_NAME);
        $signatureParams['accountNo'] = $orderId;
        $signatureParams['amount'] = $amount;
        $signatureParams['currency'] = 933;
        $signatureParams['info'] = str_replace('##order_id##', $orderId, $this->config->get(self::INFO_PARAM_NAME));
        $signatureParams['returnType'] = 'json';
        $signatureParams['returnUrl'] = $this->url->link('payment/card_expresspay/success');
        $signatureParams['failUrl'] = $this->url->link('payment/card_expresspay/fail');
        $signatureParams["returnInvoiceUrl"] = "0";

        $signatureParams['signature'] = self::computeSignature($signatureParams, $secret_word, 'add-webcard-invoice');
        unset($signatureParams['token']);
        $data = array_merge($data, $signatureParams);
		$url = ( $this->config->get(self::IS_TEST_MODE_PARAM_NAME) != 'on' ) ? $this->config->get(self::API_URL_PARAM_NAME) : $this->config->get(self::SANDBOX_URL_PARAM_NAME);
		$url = $url.'v1/web_cardinvoices';
		
		$this->load->model('checkout/order');
		$this->model_checkout_order->addOrderHistory($orderId, $this->config->get(self::PROCESSED_STATUS_ID_PARAM_NAME));

        try {
			$responseJSON = self::sendRequest($url, $signatureParams);
        	$response = json_decode($responseJSON);

			if (isset($response->ExpressPayInvoiceNo)) {
				if (isset($response->FormUrl)) {
					$this->response->redirect($response->FormUrl);
					return;
				}
				$this->response->redirect($response->InvoiceUrl);
				return;
			}
		} catch (Exception $e) {
			$this->log_error_exception('send', 'Get response; ORDER ID - ' . $orderId . '; RESPONSE - ' . $response, $e);
		}
		$this->response->redirect($this->url->link('payment/card_expresspay/fail'));
	}

	public function success() {
        $orderId = $this->session->data['order_id'];
		$this->log_info('success', 'Initialization render success page; ORDER ID - ' . $orderId);
		$this->cart->clear();
		$this->load->language('payment/card_expresspay');
		$headingTitle = $this->language->get('heading_title_success');
		$this->document->setTitle($headingTitle);
		$data['heading_title'] = $headingTitle;
        $textMessage = $this->config->get(self::MESSAGE_SUCCESS_PARAM_NAME);
        if (empty($textMessage)) {
            $textMessage = $this->language->get('text_message_success');
        }
        $data['text_message'] = nl2br(str_replace('##order_id##', $orderId, $textMessage));
		
		$data['test_mode_label'] = $this->language->get('test_mode_label');
		$data['text_send_notify_success'] = $this->language->get('text_send_notify_success');
		$data['text_send_notify_cancel'] = $this->language->get('text_send_notify_cancel');
		$data['test_mode'] = ( $this->config->get(self::IS_TEST_MODE_PARAM_NAME) == 'on' ) ? true : false;
		$data['order_id'] = $this->session->data['order_id'];
		$data['signature_success'] = $data['signature_cancel'] = "";

		$secret_word = $this->config->get(self::SECRET_WORD_PARAM_NAME);
		$data['signature_success'] = $this->computeSignature('{"CmdType": 1, "AccountNo": ' . $orderId . '}', $secret_word, 'notification');
		$data['signature_cancel'] = $this->computeSignature('{"CmdType": 2, "AccountNo": ' . $orderId . '}', $secret_word, 'notification');

        $data = $this->setBreadcrumbs($data);
        $data = $this->setButtons($data);
        $data = $this->setController($data);
        $data['continue'] = $this->url->link('common/home');

		$this->log_info('success', 'End render success page; ORDER ID - ' . $orderId);

		$this->response->setOutput($this->load->view('default/template/payment/card_expresspay_success.tpl', $data));
	}

	public function fail() {
        $orderId = $this->session->data['order_id'];
		$this->log_info('fail', 'Initialization render fail page; ORDER ID - ' . $orderId);
		$this->load->language('payment/card_expresspay');
		$headingTitle  = $this->language->get('heading_title_fail');
		$this->document->setTitle($headingTitle);
        $data['heading_title'] = $headingTitle;
		$data['text_message'] = nl2br(str_replace('##order_id##', $orderId, $this->language->get('text_message_fail')));

		$this->load->model('checkout/order');
		$this->model_checkout_order->addOrderHistory($orderId, $this->config->get(self::FAIL_STATUS_ID_PARAM_NAME));
		
        $data = $this->setBreadcrumbs($data);
        $data = $this->setButtons($data);
        $data = $this->setController($data);
        $data['continue'] = $this->url->link('checkout/checkout');

		$this->log_info('fail', 'End render fail page; ORDER ID - ' . $orderId);

		$this->response->setOutput($this->load->view('default/template/payment/card_expresspay_failure.tpl', $data));
	}

    private function setBreadcrumbs($data)
    {
		$data['breadcrumbs'] = array(); 

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/cart'),
			'text'      => $this->language->get('text_basket'),
			'separator' => $this->language->get('text_separator')
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
			'text'      => $this->language->get('text_checkout'),
			'separator' => $this->language->get('text_separator')
		);	

        return $data;
    }

    private function setButtons($data)
    {
        $data['button_continue'] = $this->language->get('button_continue');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['continue'] = $this->url->link('checkout/checkout');

        return $data;
    }

    private function setController($data)
    {
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        return $data;
    }
	
	public function notify() {
		$this->log_info('notify', 'Get notify from server; REQUEST METHOD - ' . $_SERVER['REQUEST_METHOD']);

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $useSignatureForNotification = ($this->config->get(self::USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME) == 'on') ? true : false;
            $dataJSON = (isset($this->request->post['Data'])) ? htmlspecialchars_decode($this->request->post['Data']) : '';
            $signature = (isset($this->request->post['Signature'])) ? $this->request->post['Signature'] : '';
		    
		    if($useSignatureForNotification) {
                $secretWordForNotification = $this->config->get(self::SECRET_WORD_NOTIFICATION_PARAM_NAME);

                $valid_signature = self::computeSignature(array("data" => $dataJSON), $secretWordForNotification, 'notification');
		    	if($valid_signature == $signature)
			        $this->notify_success($dataJSON);
			    else  {
					$this->log_error('notify_fail', "Fail to update status; RESPONSE - " . $dataJSON);

					header("HTTP/1.0 400 Bad Request");
					echo 'FAILED | Incorrect digital signature';
				}
		    } else 
		    	$this->notify_success($dataJSON);
		}
		$this->log_info('notify', 'End (Get notify from server); REQUEST METHOD - ' . $_SERVER['REQUEST_METHOD']);
	}

	private function notify_success($dataJSON) {
        // Преобразование из json в array
        $data = array();
		try {
        	$data = json_decode($dataJSON);
    	} catch(Exception $e) {
            header('HTTP/1.1 400 Bad Request');
            echo 'FAILED | Failed to decode data';
    		$this->log_error('notify_fail', "Fail to parse the server response; RESPONSE - " . $dataJSON);
			return;
    	}

		$this->load->model('checkout/order');

        if(isset($data->CmdType)) {
        	switch ($data->CmdType) {
        		case '1':
					if($this->model_checkout_order->getOrder($data->AccountNo)['order_status_id'] != $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME)){
        				$this->model_checkout_order->addOrderHistory($data->AccountNo, $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME));
        				$this->log_info('notify_success', 'Initialization to update status. STATUS ID - ' . $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME) . "; RESPONSE - " . $dataJSON);
					}
        			break;
        		case '2':
        			$this->model_checkout_order->addOrderHistory($data->AccountNo, $this->config->get(self::FAIL_STATUS_ID_PARAM_NAME));
					$this->log_info('notify_success', 'Initialization to update status. STATUS ID - ' . $this->config->get(self::FAIL_STATUS_ID_PARAM_NAME) . "; RESPONSE - " . $dataJSON);

        			break;
				case 3:
					if(isset($data->Status)){
						switch($data->Status){
							case 1: // Ожидает оплату
								if($this->model_checkout_order->getOrder($data->AccountNo)['order_status_id'] != $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME)){
									$this->model_checkout_order->addOrderHistory($data->AccountNo, $this->config->get(self::PROCESSED_STATUS_ID_PARAM_NAME));
									$this->log_info('notify_success', 'Initialization to update status. STATUS ID - ' . $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME) . "; RESPONSE - " . $dataJSON);
								}
								break;
							case 2: // Просрочен
								$this->model_checkout_order->addOrderHistory($data->AccountNo, $this->config->get(self::FAIL_STATUS_ID_PARAM_NAME));
								$this->log_info('notify_success', 'Initialization to update status. STATUS ID - ' . $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME) . "; RESPONSE - " . $dataJSON);
								break;
							case 3: // Оплачен
							case 6: // Оплачен с помощью банковской карты
								if($this->model_checkout_order->getOrder($data->AccountNo)['order_status_id'] != $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME)){
                                    $this->model_checkout_order->addOrderHistory($data->AccountNo, $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME));
                                    $this->log_info('notify_success', 'Initialization to update status. STATUS ID - ' . $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME) . "; RESPONSE - " . $dataJSON);
                                    
                                }
                                break;
							case 5: // Отменен
								$this->model_checkout_order->addOrderHistory($data->AccountNo, $this->config->get(self::FAIL_STATUS_ID_PARAM_NAME));
								$this->log_info('notify_success', 'Initialization to update status. STATUS ID - ' . $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME) . "; RESPONSE - " . $dataJSON);
								break;
						}
					}
					break;
        	}
			header("HTTP/1.1 200 OK");
			echo 'OK | the notice is processed';
			$this->log_info("notify_success", "the notice is processed");
			return;
        } 

        header('HTTP/1.1 400 Bad Request');
        echo 'FAILED | The notice is not processed';
		$this->log_error('notify_fail', "Fail to parse the server response; RESPONSE - " . $dataJSON);
	}

	// Отправка POST запроса
	function sendRequest($url, $params)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

    /**
     * 
     * Формирование цифровой подписи
     * 
     * @param array  $signatureParams Список передаваемых параметров
     * @param string $secretWord      Секретное слово
     * @param string $method          Метод формирования цифровой подписи
     * 
     * @return string $hash           Сформированная цифровая подпись
     * 
     */
    private static function computeSignature($signatureParams, $secretWord, $method)
    {
        $normalizedParams = array_change_key_case($signatureParams, CASE_LOWER);
        $mapping = array(
            "get-qr-code"          => array(
                "token",
                "invoiceid",
                "viewtype",
                "imagewidth",
                "imageheight"
            ),
            "add-web-invoice"      => array(
                "token",
                "serviceid",
                "accountno",
                "amount",
                "currency",
                "expiration",
                "info",
                "surname",
                "firstname",
                "patronymic",
                "city",
                "street",
                "house",
                "building",
                "apartment",
                "isnameeditable",
                "isaddresseditable",
                "isamounteditable",
                "emailnotification",
                "smsphone",
                "returntype",
                "returnurl",
                "failurl",
                "returninvoiceurl"
            ),
            "add-webcard-invoice" => array(
                "token",
                "serviceid",
                "accountno",
                "expiration",
                "amount",
                "currency",
                "info",
                "returnurl",
                "failurl",
                "language",
                "sessiontimeoutsecs",
                "expirationdate",
                "returntype",
                "returninvoiceurl"
			),
            "notification"         => array(
                "data"
            )
        );
        $apiMethod = $mapping[$method];
        $result = "";
        foreach ($apiMethod as $item) {
            $result .= (isset($normalizedParams[$item])) ? $normalizedParams[$item] : '';
        }
        $hash = strtoupper(hash_hmac('sha1', $result, $secretWord));
        return $hash;
    }

    private function log_error_exception($name, $message, $e) {
    	$this->log($name, "ERROR" , $message . '; EXCEPTION MESSAGE - ' . $e->getMessage() . '; EXCEPTION TRACE - ' . $e->getTraceAsString());
    }

    private function log_error($name, $message) {
    	$this->log($name, "ERROR" , $message);
    }

    private function log_info($name, $message) {
    	$this->log($name, "INFO" , $message);
    }

    private function log($name, $type, $message) {
    	$log = new Log('card_expresspay/express-pay-' . date('Y.m.d') . '.log');
    	$log->write($type . " - IP - " . $_SERVER['REMOTE_ADDR'] . "; USER AGENT - " . $_SERVER['HTTP_USER_AGENT'] . "; FUNCTION - " . $name . "; MESSAGE - " . $message . ';');
    }
}

?>