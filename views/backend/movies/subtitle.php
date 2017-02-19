<?php if (!defined('ABSPATH')) {exit;} ?>
<div id="subtitle-wrap">
    <label class="" id="subtitle-label" for="subtitle">
        <?php echo __('Enter subtitle here', $this->app->textDomain); ?>
    </label>
    <input type="hidden" name="<?php echo $nonce['name']; ?>" id="<?php echo $nonce['name']; ?>" value="<?php echo $nonce['value']; ?>" />
    <input type="text" name="<?php echo $subtitle['name']; ?>" value="<?php echo $subtitle['value']; ?>" id="subtitle">
</div>
