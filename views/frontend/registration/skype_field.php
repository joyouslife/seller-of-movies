<?php if (!defined('ABSPATH')) {exit;} ?>

<p class="form-row form-row-wide">
    <label for="reg_<?php echo $name; ?>">
        <?php _e('Skype', $this->app->textDomain); ?>
    </label>
    <input type="text" class="input-text" name="<?php echo $name; ?>" id="reg_<?php echo $name; ?>" value="<?php echo $value; ?>" />
</p>
