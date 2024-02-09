<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" class="container"><?php echo $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
	<h2><?php echo $heading_title; ?></h2>
	<p><?php echo $text_message; ?></p>
  <div class="buttons">
    <div class="pull-right"><a id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>

    <?php if($test_mode) : ?>
      <div class="pull-left">
        <?php echo $test_mode_label; ?><input style="margin: 0 4px;" type="button" id="send_notify_success" class="btn btn-primary" value="<?php echo $text_send_notify_success; ?>" />
        <input type="button" id="send_notify_cancel" class="btn btn-primary" value="<?php echo $text_send_notify_cancel; ?>" />
      </div>

      <script type="text/javascript">
        $(document).ready(function() {
          $('#send_notify_success').click(function() {
            send_notify(1, '<?php echo $signature_success; ?>');
          });

          $('#send_notify_cancel').click(function() {
            send_notify(2, '<?php echo $signature_cancel; ?>');
          });

          function send_notify(type, signature) {
            $.post('<?php echo HTTPS_SERVER . "index.php?route=payment/card_expresspay/notify" ?>', 'Data={"CmdType": ' + type + ', "AccountNo": <?php echo $order_id; ?>}&Signature=' + signature, function(data) {alert(data);})
            .fail(function(data) {
              alert(data.responseText);
            });
          }
        });
      </script>
    <?php endif; ?>
  </div>
  <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>
