<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" class="container"><?php echo $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
	<h2><?php echo $heading_title; ?></h2>
	<p><?php echo $text_message ?></p>
  <div class="buttons">
    <div class="right"><a id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" href="<?php echo $continue; ?>"><?php echo $button_continue; ?></a></div>
  </div>
  <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>