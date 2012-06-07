<?php self::link_third_script('jquery'); ?>
<?php self::link_third_script('jquery.form'); ?>

<div id="page">
    <form id="edit_form" action="<?php self::php_url('message/edit?id=' . $message->id); ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <p id="edit_form_tips"></p>
        <?php self::csrf_field('member'); ?>
        <p><label for="edit_form_author">昵称</label><input type="text" id="edit_form_author" name="author" value="<?php echo $message->author; ?>" /></p>
        <p><label for="edit_form_email">邮箱</label><input type="text" id="edit_form_email" name="email" value="<?php echo $message->email; ?>" /></p>
        <p><label for="edit_form_site">网站</label><input type="text" id="edit_form_site" name="site" value="<?php echo $message->site; ?>" /></p>
        <p><label for="edit_form_content">内容</label><textarea id="edit_form_content" name="content"><?php echo $message->content; ?></textarea></p>
        <p><input type="submit" id="edit_form_submit" name="submit" value="修改" /></p>
    </form>
</div>
