<?php
// Heading
$_['heading_title']         = '«Экспресс Платежи: Банковская карта»';

// Text
$_['text_extension']        = 'Расширения';
$_['text_success']          = 'Вы успешно изменили настройки модуля';
$_['text_card_expresspay'] 	= '<a target="_blank" href="https://express-pay.by/"><img src="view/image/payment/card_expresspay.png" alt="ExpressPay" title="ExpressPay" style="border: 1px solid #eeeeee; padding: 10px 10px 6px 10px;" /></a>';
$_['text_edit']             = 'Изменить настройки';
$_['text_about']            = '«Экспресс Платежи: Банковская карта» - плагин для интеграции с сервисом «Экспресс Платежи» (express-pay.by) через API. 
<br/>Плагин позволяет выставить счет для оплаты банковской картой, получить и обработать уведомление о платеже.
<br/>Описание плагина доступно по адресу: <a target="blank" href="https://express-pay.by/cms-extensions/opencart#2_2_x">https://express-pay.by/cms-extensions/opencart#2_2_x</a>';

$_['text_payment']          = 'Оплаты';
$_['settings_module_label'] = 'Настройки модуля';
$_['text_version']          = 'Версия ';
$_['button_save']           = 'Сохранить';
$_['button_cancel']         = 'Отменить';

// Setting field
$_['namePaymentMethodLabel']            = 'Название метода оплаты';
$_['namePaymentMethodTooltip']          = 'Название выводимое при выборе способа оплаты';
$_['namePaymentMethodDefault']          = 'Экспресс платежи: Банковская карта';
$_['tokenLabel']                        = 'Токен';
$_['tokenTooltip']                      = 'API-ключ производителя услуг';
$_['serviceIdLabel']                    = 'Номер услуги';
$_['serviceIdTooltip']                  = 'Номер услуги в системе express-pay.by';
$_['secretWordLabel']                   = 'Секретное слово для подписи счетов';
$_['secretWordTooltip']                 = 'Секретное слово для формирования цифровой подписи для подписи счетов';
$_['secretWordNotificationLabel']       = 'Секретное слово для уведомлений';
$_['secretWordNotificationTooltip']     = 'Секретное слово для формирования цифровой подписи для уведомлений';
$_['useSignatureForNotificationLabel']  = 'Использовать цифровую подпись для уведомлений';
$_['useTestModeLabel']                  = 'Использовать тестовый режим';
$_['urlApiLabel']                       = 'Адрес API';
$_['urlApiTooltip']                     = 'Адрес для работы с API';
$_['urlSandboxLabel']                   = 'Адрес тестового API';
$_['urlSandboxTooltip']                 = 'Адрес для работы с тестовым API';
$_['infoLabel']                         = 'Описание заказа';
$_['infoTooltip']                       = 'Описание заказа будет отображаться при оплате клиенту';
$_['infoDefault']                       = 'Заказ номер ##order_id## в магазине '. $_SERVER['HTTP_HOST'];
$_['urlForNotificationLabel']           = 'Адрес для получения уведомлений';
$_['urlForNotificationTooltip']         = 'Адрес для получения уведомлений о статусе заказа на сайт, задается в личном кабинете';
$_['messageSuccessLabel']               = 'Сообщение при успешном создании счёта';
$_['messageSuccessTooltip']             = 'Текст сообщения при успешном создании счёта для клиента';
$_['messageSuccessDefault']             = 'Номер вашего заказа: ##order_id##';
$_['entryStatus']                       = 'Статус';
$_['entrySortOrder']                    = 'Порядок сортировки';

$_['processedOrderStatusLabel']     = 'Статус нового заказа';
$_['processedOrderStatusTooltip']   = 'Устанавливаемый статус заказу поступившего в обработку';
$_['failOrderStatusLabel']          = 'Статус заказа при ошибке';
$_['failOrderStatusTooltip']        = 'Устанавливаемый статус заказу при возникновении ошибки';
$_['successOrderStatusLabel']       = 'Статус оплаченного заказа';
$_['successOrderStatusTooltip']     = 'Устанавливаемый статус заказу на который поступила оплата';

// Error
$_['errorPermission']           = 'Внимание: У вас нет прав для изменения настроек модуля оплаты!';
$_['errorNamePaymentMethod']    = 'Название метода оплаты является обязательным';
$_['errorToken']                = 'Токен является обязательным';
$_['errorServiceId']            = 'Номер услуги является обязательным';
$_['errorAPIUrl']               = 'Адрес API является обязательным';
$_['errorSandboxUrl']           = 'Адрес тестового API является обязательным';
?>