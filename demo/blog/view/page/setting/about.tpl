<?php self::link_third_script('jquery'); ?>
<?php self::link_third_script('jquery.form'); ?>

<div id="page">
    <form id="about_form" action="<?php self::php_url('setting/about'); ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <p id="about_form_tips"></p>
        <?php self::csrf_field('member'); ?>
        <p><textarea id="about_form_content" name="content" cols="38" rows="10"><?php echo $content; ?></textarea></p>
        <p><input type="submit" id="about_form_submit" name="submit" value="提交" /></p>
    </form>
</div>
