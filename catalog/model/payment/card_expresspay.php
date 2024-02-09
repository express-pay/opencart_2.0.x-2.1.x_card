<?php
class ModelPaymentCardExpressPay extends Model {
    const NAME_PAYMENT_METHOD                       = 'card_expresspay_name_payment_method';
    const SORT_ORDER_PARAM_NAME                     = 'card_expresspay_sort_order';

	public function getMethod($address, $total) {
		$this->load->language('payment/card_expresspay');
		
		$status = true;

        if ($total > 0) {
            $status = true;
        }

		$method_data = array();

        $code = 'card_expresspay';

        // Название метода оплаты
        $textTitle = $this->language->get('heading_title');
        if($this->config->get(self::NAME_PAYMENT_METHOD) !== null){
            $textTitle = $this->config->get(self::NAME_PAYMENT_METHOD);
        }

        $sortOrder = $this->config->get(self::SORT_ORDER_PARAM_NAME);
		
		if ($status) {
			$method_data = array(
				'code'       => $code,
				'title'      => $textTitle,
				'terms'      => '',
				'sort_order' => $sortOrder
			);
		}
		
		return $method_data;
	}
}
?>