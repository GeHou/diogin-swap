<?php self::link_third_script('jquery'); ?>
<?php self::link_third_script('jquery.form'); ?>

<div id="page">
    <form id="setting_form" action="<?php self::php_url('user/do_setting'); ?>" method="POST" enctype="multipart/form-data">
        <p id="setting_form_tips"></p>
        <?php self::csrf_field('user'); ?>
        <p><label for="setting_form_pass">Pass</label><input type="password" id="setting_form_pass" name="pass" /></p>
        <p><label for="setting_form_new_pass">New Pass</label><input type="password" id="setting_form_new_pass" name="new_pass" /></p>
        <p><label for="setting_form_re_pass">New Again</label><input type="password" id="setting_form_re_pass" name="re_pass" /></p>
        <p><label for="setting_form_avatar">Avatar</label><input type="file" id="setting_form_avatar" name="avatar" /></p>
        <p><input type="submit" id="setting_form_submit" name="save" value="Save" /></p>
    </form>
</div>
