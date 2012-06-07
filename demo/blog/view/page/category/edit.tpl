<?php self::link_third_script('jquery'); ?>
<?php self::link_third_script('jquery.form'); ?>

<div id="page">
    <form id="edit_form" action="<?php self::php_url('category/edit?id=' . $category->id); ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <p id="edit_form_tips"></p>
        <?php self::csrf_field('member'); ?>
        <p><label for="edit_form_name">分类名</label><input type="text" id="edit_form_name" name="name" value="<?php echo $category->name; ?>" /></p>
        <p><input type="submit" id="edit_form_submit" name="submit" value="提交" /></p>
    </form>
</div>
