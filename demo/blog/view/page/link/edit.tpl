<?php self::link_third_script('jquery'); ?>
<?php self::link_third_script('jquery.form'); ?>

<div id="page">
    <form id="edit_form" action="<?php self::php_url('link/edit?id=' . $link->id); ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <p id="edit_form_tips"></p>
        <?php self::csrf_field('member'); ?>
        <p><label for="edit_form_name">名称</label><input type="text" id="edit_form_name" name="name" value="<?php echo $link->name; ?>" /></p>
        <p><label for="edit_form_url">地址</label><input type="text" id="edit_form_url" name="url" value="<?php echo $link->url; ?>" /></p>
        <p><input type="submit" id="edit_form_submit" name="submit" value="修改" /></p>
    </form>
</div>
