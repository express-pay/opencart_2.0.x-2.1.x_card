<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <div class="form-group">
            <div class="col-sm-2" style="margin-left: -16px; margin-right: 16px;">
                 <a target="_blank" href="https://express-pay.by"><img src="/admin/view/image/payment/card_expresspay_big.png" width="270" height="91" alt="exspress-pay.by" title="express-pay.by"></a>
            </div>
            <div class="col-sm-10" style="margin-top: 11px;">
              <?php echo $text_about; ?>
            </div>
          </div>
          <!-- Название метода оплаты -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_name_payment_method"><span data-toggle="tooltip" title="" data-original-title="<?php echo $namePaymentMethodTooltip; ?>"><?php echo $namePaymentMethodLabel; ?></span></label>
            <div class="col-sm-10">
              <input required type="text" name="card_expresspay_name_payment_method" id="expresspay_name_payment_method" value="<?php echo $card_expresspay_name_payment_method; ?>" class="form-control" />
            </div>
          </div>

          <!-- Использовать тестовый режим -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_is_test_mode"><?php echo $useTestModeLabel; ?></label>
            <div class="col-sm-10">
              <input <?php echo ( $card_expresspay_is_test_mode == 'on') ? 'checked' : ''; ?> id="expresspay_is_test_mode" style="margin-top: 10px;" type="checkbox" name="card_expresspay_is_test_mode" class="form-control" />
            </div>
          </div>

          <!-- ТОКЕН -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_token"><span data-toggle="tooltip" title="" data-original-title="<?php echo $tokenTooltip; ?>"><?php echo $tokenLabel; ?></span></label>
            <div class="col-sm-10">
              <input required type="text" name="card_expresspay_token" id="expresspay_token" value="<?php echo $card_expresspay_token; ?>" class="form-control" />
            </div>
          </div>

          <!-- Номер услуги -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_service_id"><span data-toggle="tooltip" title="" data-original-title="<?php echo $serviceIdTooltip; ?>"><?php echo $serviceIdLabel; ?></span></label>
            <div class="col-sm-10">
              <input required type="text" name="card_expresspay_service_id" id="expresspay_service_id" value="<?php echo $card_expresspay_service_id; ?>" class="form-control" />
            </div>
          </div>

          <!-- Секретное слово -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_secret_word"><span data-toggle="tooltip" title="" data-original-title="<?php echo $secretWordTooltip; ?>"><?php echo $secretWordLabel; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="card_expresspay_secret_word" id="expresspay_secret_word" value="<?php echo $card_expresspay_secret_word; ?>" class="form-control" />
            </div>
          </div>

          <!-- Использовать цифровую подпись для уведомлений -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_is_use_signature_for_notification"><?php echo $useSignatureForNotificationLabel; ?></span></label>
            <div class="col-sm-10">
              <input <?php echo ( $card_expresspay_is_use_signature_for_notification == 'on') ? 'checked' : ''; ?> id="expresspay_is_use_signature_for_notification" style="margin-top: 10px;" type="checkbox" name="card_expresspay_is_use_signature_for_notification" class="form-control" />
            </div>
          </div>

          <!-- Секретное слово для уведомлений -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_secret_word_for_notification"><span data-toggle="tooltip" title="" data-original-title="<?php echo $secretWordNotificationTooltip; ?>"><?php echo $secretWordNotificationLabel; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="card_expresspay_secret_word_for_notification" id="expresspay_secret_word_for_notification" value="<?php echo $card_expresspay_secret_word_for_notification; ?>" class="form-control" />
            </div>
          </div>
          
          <!-- Адрес для уведомлений -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_url_notification"><span data-toggle="tooltip" title="" data-original-title="<?php echo $urlForNotificationTooltip; ?>"><?php echo $urlForNotificationLabel; ?></label>
            <div class="col-sm-10">
              <input readonly="readonly" type="text" name="card_expresspay_url_notification" id="expresspay_url_notification" value="<?php echo $card_expresspay_url_notification; ?>" class="form-control" />
            </div>
          </div>

          <!-- Адрес API -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_api_url"><?php echo $urlApiLabel; ?></label>
            <div class="col-sm-10">
              <input type="text" name="card_expresspay_api_url" id="expresspay_api_url" value="<?php echo ( !empty($card_expresspay_api_url) ) ? $card_expresspay_api_url : 'https://api.express-pay.by'; ?>" class="form-control" />
            </div>
          </div>
          <!-- Адрес тестового API -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_sandbox_url"><?php echo $urlSandboxLabel; ?></label>
            <div class="col-sm-10">
              <input type="text" name="card_expresspay_sandbox_url" id="expresspay_sandbox_url" value="<?php echo ( !empty($card_expresspay_sandbox_url) ) ? $card_expresspay_sandbox_url : 'https://sandbox-api.express-pay.by'; ?>" class="form-control" />
            </div>
          </div>

          <!-- Информация о платеже -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_info"><span data-toggle="tooltip" title="" data-original-title="<?php echo $infoTooltip; ?>"><?php echo $infoLabel; ?></label>
            <div class="col-sm-10">
              <textarea class="form-control" style="height: 120px; max-width: 100%;"  id="expresspay_info" name="card_expresspay_info"><?php echo $card_expresspay_info; ?></textarea>
            </div>
          </div>
          <!-- Сообщение при успешном создании счёта -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_message_success"><span data-toggle="tooltip" title="" data-original-title="<?php echo $messageSuccessTooltip; ?>"><?php echo $messageSuccessLabel; ?></label>
            <div class="col-sm-10">
              <textarea class="form-control" style="height: 120px; max-width: 100%;"  id="expresspay_message_success" name="card_expresspay_message_success"><?php echo $card_expresspay_message_success; ?></textarea>
            </div>
          </div>

          <div class="form-group">
            <label style="font-size: 20px;" class="col-sm-2 control-label"><?php echo $settings_module_label; ?></label>
          </div>

          <!-- Статус -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entryStatus; ?></label>
            <div class="col-sm-10">
              <select name="card_expresspay_status" id="input-status" class="form-control">
                <?php if ($card_expresspay_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <!-- Порядок сортировки -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entrySortOrder; ?></label>
            <div class="col-sm-10">
              <input type="text" name="card_expresspay_sort_order" id="input-sort-order" value="<?php echo $card_expresspay_sort_order; ?>" size="1" class="form-control" />
            </div>
          </div>

          <!-- Статус нового заказа -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_processed_status_id"><span data-toggle="tooltip" title="" data-original-title="<?php echo $processedOrderStatusTooltip; ?>"><?php echo $processedOrderStatusLabel; ?></label>
            <div class="col-sm-10">
              <select name="card_expresspay_processed_status_id" id="expresspay_processed_status_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $card_expresspay_processed_status_id ) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <!-- Статус оплаченого заказа -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_success_status_id"><span data-toggle="tooltip" title="" data-original-title="<?php echo $successOrderStatusTooltip; ?>"><?php echo $successOrderStatusLabel; ?></label>
            <div class="col-sm-10">
              <select name="card_expresspay_success_status_id" id="expresspay_success_status_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $card_expresspay_success_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <!-- Статус ошибки при заказе -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="expresspay_fail_status_id"><span data-toggle="tooltip" title="" data-original-title="<?php echo $failOrderStatusTooltip; ?>"><?php echo $failOrderStatusLabel; ?></label>
            <div class="col-sm-10">
              <select name="card_expresspay_fail_status_id" id="expresspay_fail_status_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $card_expresspay_fail_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="copyright" style="text-align: center;">
              &copy; Все права защищены | ООО «ТриИнком», 2013-<?php echo date('Y'); ?><br/>
              <?php echo $text_version . CARD_EXPRESSPAY_VERSION ?>
            </div>
          </div>
      </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 