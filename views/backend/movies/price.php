<?php if (!defined('ABSPATH')) {exit;} ?>
<div id="price-wrap">
    <label class="" id="price-label" for="price">
        <?php echo __('Enter price here', $this->app->textDomain); ?>
    </label>
    <input type="hidden" name="<?php echo $nonce['name']; ?>" id="<?php echo $nonce['name']; ?>" value="<?php echo $nonce['value']; ?>" />
    <input placeholder="0.00" type="text" name="<?php echo $price['name']; ?>" value="<?php echo $price['value']; ?>" id="price"> <?php echo $symbol; ?>
</div>
