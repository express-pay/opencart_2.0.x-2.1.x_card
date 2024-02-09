<?php
class ControllerPaymentCardExpressPay extends Controller {
	const NAME_PAYMENT_METHOD                       = 'card_expresspay_name_payment_method';
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
    const STATUS_PARAM_NAME                         = 'card_expresspay_status';
    const SORT_ORDER_PARAM_NAME                     = 'card_expresspay_sort_order';
    const PROCESSED_STATUS_ID_PARAM_NAME            = 'card_expresspay_processed_status_id';
    const SUCCESS_STATUS_ID_PARAM_NAME              = 'card_expresspay_success_status_id';
    const FAIL_STATUS_ID_PARAM_NAME                 = 'card_expresspay_fail_status_id';

    private $error = array();

    public function index() {
		define("CARD_EXPRESSPAY_VERSION", "3.1");

		$this->load->language('payment/card_expresspay');
		$this->load->model('setting/setting');
		
		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('card_expresspay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
		}

		$data = array();

		$data['heading_title']      = $this->language->get('heading_title');

		$data['text_edit']      	= $this->language->get('text_edit');
		$data['button_save']        = $this->language->get('button_save');
		$data['button_cancel']      = $this->language->get('button_cancel');
		$data['error_warning']      = $this->language->get('error_warning');

		$data['namePaymentMethodLabel']				= $this->language->get('namePaymentMethodLabel');
		$data['namePaymentMethodTooltip']			= $this->language->get('namePaymentMethodTooltip');
		$data['namePaymentMethodDefault']			= $this->language->get('namePaymentMethodDefault');
		$data['tokenLabel']							= $this->language->get('tokenLabel');
		$data['tokenTooltip']						= $this->language->get('tokenTooltip');
		$data['serviceIdLabel']						= $this->language->get('serviceIdLabel');
		$data['serviceIdTooltip']					= $this->language->get('serviceIdTooltip');
		$data['secretWordLabel']					= $this->language->get('secretWordLabel');
		$data['secretWordTooltip']					= $this->language->get('secretWordTooltip');
		$data['secretWordNotificationLabel']		= $this->language->get('secretWordNotificationLabel');
		$data['secretWordNotificationTooltip']		= $this->language->get('secretWordNotificationTooltip');
		$data['useSignatureForNotificationLabel']	= $this->language->get('useSignatureForNotificationLabel');
		$data['useTestModeLabel']					= $this->language->get('useTestModeLabel');
		$data['urlApiLabel']						= $this->language->get('urlApiLabel');
		$data['urlApiTooltip']						= $this->language->get('urlApiTooltip');
		$data['urlSandboxLabel']					= $this->language->get('urlSandboxLabel');
		$data['urlSandboxTooltip']					= $this->language->get('urlSandboxTooltip');
		$data['urlForNotificationLabel']			= $this->language->get('urlForNotificationLabel');
		$data['urlForNotificationTooltip']			= $this->language->get('urlForNotificationTooltip');
		$data['infoLabel']							= $this->language->get('infoLabel');
		$data['infoTooltip']						= $this->language->get('infoTooltip');
		$data['infoDefault']						= $this->language->get('infoDefault');
		$data['messageSuccessLabel']				= $this->language->get('messageSuccessLabel');
		$data['messageSuccessTooltip']				= $this->language->get('messageSuccessTooltip');
		$data['messageSuccessDefault']				= $this->language->get('messageSuccessDefault');
		$data['entryStatus']						= $this->language->get('entryStatus');
		$data['entrySortOrder']						= $this->language->get('entrySortOrder');

		$data['processedOrderStatusLabel']			= $this->language->get('processedOrderStatusLabel');
		$data['processedOrderStatusTooltip']		= $this->language->get('processedOrderStatusTooltip');
		$data['failOrderStatusLabel']				= $this->language->get('failOrderStatusLabel');
		$data['failOrderStatusTooltip']				= $this->language->get('failOrderStatusTooltip');
		$data['successOrderStatusLabel']			= $this->language->get('successOrderStatusLabel');
		$data['successOrderStatusTooltip']			= $this->language->get('successOrderStatusTooltip');

		$data['text_enabled']			= $this->language->get('text_enabled');
		$data['text_disabled']			= $this->language->get('text_disabled');
		$data['settings_module_label']	= $this->language->get('settings_module_label');
		$data['text_version']			= $this->language->get('text_version');
		$data['text_about']				= $this->language->get('text_about');

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error[self::NAME_PAYMENT_METHOD])) {
			$data['error_name_payment_method'] = $this->error[self::NAME_PAYMENT_METHOD];
		} else {
			$data['error_name_payment_method'] = '';
		}
		if (isset($this->error[self::TOKEN_PARAM_NAME])) {
			$data['error_token'] = $this->error[self::TOKEN_PARAM_NAME];
		} else {
			$data['error_token'] = '';
		}
		if (isset($this->error[self::SERVICE_ID_PARAM_NAME])) {
			$data['error_service_id'] = $this->error[self::SERVICE_ID_PARAM_NAME];
		} else {
			$data['error_service_id'] = '';
		}
		if (isset($this->error[self::API_URL_PARAM_NAME])) {
			$data['error_api_url'] = $this->error[self::API_URL_PARAM_NAME];
		} else {
			$data['error_api_url'] = '';
		}
		if (isset($this->error[self::SANDBOX_URL_PARAM_NAME])) {
			$data['error_sandbox_url'] = $this->error[self::SANDBOX_URL_PARAM_NAME];
		} else {
			$data['error_sandbox_url'] = '';
		}

		$data[self::NOTIFICATION_URL_PARAM_NAME]  	  = str_replace('/admin', '', HTTPS_SERVER . 'index.php?route=payment/card_expresspay/notify');

        // Название метода оплаты
        if (isset($this->request->post[self::NAME_PAYMENT_METHOD])) {
            $data[self::NAME_PAYMENT_METHOD] = $this->request->post[self::NAME_PAYMENT_METHOD];
        } else if($this->config->get(self::NAME_PAYMENT_METHOD) !== null){
            $data[self::NAME_PAYMENT_METHOD] = $this->config->get(self::NAME_PAYMENT_METHOD);
        } else {
            $data[self::NAME_PAYMENT_METHOD] = $this->language->get('namePaymentMethodDefault');
        }

        // ТОКЕН
        if (isset($this->request->post[self::TOKEN_PARAM_NAME])) {
            $data[self::TOKEN_PARAM_NAME] = $this->request->post[self::TOKEN_PARAM_NAME];
        } else {
            $data[self::TOKEN_PARAM_NAME] = $this->config->get(self::TOKEN_PARAM_NAME);
        }

        // Номер услуги
        if (isset($this->request->post[self::SERVICE_ID_PARAM_NAME])) {
            $data[self::SERVICE_ID_PARAM_NAME] = $this->request->post[self::SERVICE_ID_PARAM_NAME];
        } else {
            $data[self::SERVICE_ID_PARAM_NAME] = $this->config->get(self::SERVICE_ID_PARAM_NAME);
        }

        // Секретное слово
        if (isset($this->request->post[self::SECRET_WORD_PARAM_NAME])) {
            $data[self::SECRET_WORD_PARAM_NAME] = $this->request->post[self::SECRET_WORD_PARAM_NAME];
        } else {
            $data[self::SECRET_WORD_PARAM_NAME] = $this->config->get(self::SECRET_WORD_PARAM_NAME);
        }

        // Использовать цифровую подпись для уведомлений
        if (isset($this->request->post[self::USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME])) {
            $data[self::USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME] = $this->request->post[self::USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME];
        } else {
            $data[self::USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME] = $this->config->get(self::USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME);
        }

        // Секретное слово для уведомлений
        if (isset($this->request->post[self::SECRET_WORD_NOTIFICATION_PARAM_NAME])) {
            $data[self::SECRET_WORD_NOTIFICATION_PARAM_NAME] = $this->request->post[self::SECRET_WORD_NOTIFICATION_PARAM_NAME];
        } else {
            $data[self::SECRET_WORD_NOTIFICATION_PARAM_NAME] = $this->config->get(self::SECRET_WORD_NOTIFICATION_PARAM_NAME);
        }

        // Использовать тестовый режим
        if (isset($this->request->post[self::IS_TEST_MODE_PARAM_NAME])) {
            $data[self::IS_TEST_MODE_PARAM_NAME] = $this->request->post[self::IS_TEST_MODE_PARAM_NAME];
        } else {
            $data[self::IS_TEST_MODE_PARAM_NAME] = $this->config->get(self::IS_TEST_MODE_PARAM_NAME);
        }

        // Адрес API
        if (isset($this->request->post[self::API_URL_PARAM_NAME])) {
            $data[self::API_URL_PARAM_NAME] = $this->request->post[self::API_URL_PARAM_NAME];
		} else if($this->config->get(self::API_URL_PARAM_NAME) !== null){
            $data[self::API_URL_PARAM_NAME] = $this->config->get(self::API_URL_PARAM_NAME);
		} else {
			$data[self::API_URL_PARAM_NAME] = 'https://api.express-pay.by/';
		}

        // Адрес тестового API 
        if (isset($this->request->post[self::SANDBOX_URL_PARAM_NAME])) {
            $data[self::SANDBOX_URL_PARAM_NAME] = $this->request->post[self::SANDBOX_URL_PARAM_NAME];
		} else if($this->config->get(self::SANDBOX_URL_PARAM_NAME) !== null){
            $data[self::SANDBOX_URL_PARAM_NAME] = $this->config->get(self::SANDBOX_URL_PARAM_NAME);
		} else {
			$data[self::SANDBOX_URL_PARAM_NAME] = 'https://sandbox-api.express-pay.by/';
		}

        // Информация о платеже
        if (isset($this->request->post[self::INFO_PARAM_NAME])) {
            $data[self::INFO_PARAM_NAME] = $this->request->post[self::INFO_PARAM_NAME];
		} else if($this->config->get(self::INFO_PARAM_NAME) !== null){
            $data[self::INFO_PARAM_NAME] = $this->config->get(self::INFO_PARAM_NAME);
		} else {
			$data[self::INFO_PARAM_NAME] = $this->language->get('infoDefault');
		}
		
        // Сообщение при успешном создании счёта
        if (isset($this->request->post[self::MESSAGE_SUCCESS_PARAM_NAME])) {
            $data[self::MESSAGE_SUCCESS_PARAM_NAME] = $this->request->post[self::MESSAGE_SUCCESS_PARAM_NAME];
		} else if($this->config->get(self::MESSAGE_SUCCESS_PARAM_NAME) !== null){
            $data[self::MESSAGE_SUCCESS_PARAM_NAME] = $this->config->get(self::MESSAGE_SUCCESS_PARAM_NAME);
		} else {
			$data[self::MESSAGE_SUCCESS_PARAM_NAME] = $this->language->get('messageSuccessDefault');
		}
		
        // Статус
        if (isset($this->request->post[self::STATUS_PARAM_NAME])) {
            $data[self::STATUS_PARAM_NAME] = $this->request->post[self::STATUS_PARAM_NAME];
        } else {
            $data[self::STATUS_PARAM_NAME] = $this->config->get(self::STATUS_PARAM_NAME);
        }
		
        // Порядок сортировки
        if (isset($this->request->post[self::SORT_ORDER_PARAM_NAME])) {
            $data[self::SORT_ORDER_PARAM_NAME] = $this->request->post[self::SORT_ORDER_PARAM_NAME];
        } else {
            $data[self::SORT_ORDER_PARAM_NAME] = $this->config->get(self::SORT_ORDER_PARAM_NAME);
        }
		
        // Статус нового заказа
        if (isset($this->request->post[self::PROCESSED_STATUS_ID_PARAM_NAME])) {
            $data[self::PROCESSED_STATUS_ID_PARAM_NAME] = $this->request->post[self::PROCESSED_STATUS_ID_PARAM_NAME];
		} else if($this->config->get(self::PROCESSED_STATUS_ID_PARAM_NAME) !== null){
            $data[self::PROCESSED_STATUS_ID_PARAM_NAME] = $this->config->get(self::PROCESSED_STATUS_ID_PARAM_NAME);
		} else {
			$data[self::PROCESSED_STATUS_ID_PARAM_NAME] = '15';
		}
		
        // Статус оплаченого заказа
        if (isset($this->request->post[self::SUCCESS_STATUS_ID_PARAM_NAME])) {
            $data[self::SUCCESS_STATUS_ID_PARAM_NAME] = $this->request->post[self::SUCCESS_STATUS_ID_PARAM_NAME];
		} else if($this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME) !== null){
            $data[self::SUCCESS_STATUS_ID_PARAM_NAME] = $this->config->get(self::SUCCESS_STATUS_ID_PARAM_NAME);
		} else {
			$data[self::SUCCESS_STATUS_ID_PARAM_NAME] = '2';
		}
		
        // Статус ошибки при заказе
        if (isset($this->request->post[self::FAIL_STATUS_ID_PARAM_NAME])) {
            $data[self::FAIL_STATUS_ID_PARAM_NAME] = $this->request->post[self::FAIL_STATUS_ID_PARAM_NAME];
		} else if($this->config->get(self::FAIL_STATUS_ID_PARAM_NAME) !== null){
            $data[self::FAIL_STATUS_ID_PARAM_NAME] = $this->config->get(self::FAIL_STATUS_ID_PARAM_NAME);
		} else {
			$data[self::FAIL_STATUS_ID_PARAM_NAME] = '10';
		}

		$data = $this->setBreadcrumbs($data);
		$data = $this->setButtons($data);
		$data = $this->setController($data);

		$this->response->setOutput($this->load->view('payment/card_expresspay.tpl', $data));
    }

	private function validate() {
		$this->error = false;

		if (!$this->user->hasPermission('modify', 'payment/epos_expresspay')) {
			$this->error['warning'] = $this->language->get('errorPermission');
		}

		// Empty Название метода оплаты
		if(!$this->request->post[self::NAME_PAYMENT_METHOD]) {
		  	$this->error[self::NAME_PAYMENT_METHOD] = $this->language->get('errorNamePaymentMethod');
		}
		// Empty Token
		if(!$this->request->post[self::TOKEN_PARAM_NAME]) {
		  	$this->error[self::TOKEN_PARAM_NAME] = $this->language->get('errorToken');
		}
		// Empty Номер услуги
		if(!$this->request->post[self::SERVICE_ID_PARAM_NAME]) {
		  	$this->error[self::SERVICE_ID_PARAM_NAME] = $this->language->get('errorServiceId');
		}
		// Empty Адрес API
		if(!$this->request->post[self::API_URL_PARAM_NAME]) {
		  	$this->error[self::API_URL_PARAM_NAME] = $this->language->get('errorAPIUrl');
		}
		// Empty Адрес тестового API
		if(!$this->request->post[self::SANDBOX_URL_PARAM_NAME]) {
		  	$this->error[self::SANDBOX_URL_PARAM_NAME] = $this->language->get('errorSandboxUrl');
		}
		
		return !$this->error;
	}

	private function setBreadcrumbs($data)
	{
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/card_expresspay', 'token=' . $this->session->data['token'], true)
		);

	  	return $data;
	}

	private function setButtons($data)
	{
		$data['action'] = $this->url->link('payment/card_expresspay', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

	  	return $data;
	}

	private function setController($data)
	{
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
  
	  	return $data;
	}
}
?>