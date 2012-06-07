<?php self::link_third_script('jquery'); ?>
<?php self::link_third_script('jquery.form'); ?>

<div id="page">
    <form id="login_form" action="<?php self::php_url('site/do_login'); ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <p id="login_form_tips"></p>
        <p><label for="login_form_name">用户</label><input type="text" id="login_form_name" name="name" /></p>
        <p><label for="login_form_pass">密码</label><input type="password" id="login_form_pass" name="pass" /></p>
        <p><input type="checkbox" id="login_form_remember" name="remember" value="on" /><label for="login_form_remember">记住登录状态</label></p>
        <p><input type="submit" id="login_form_submit" name="submit" value="登录" /></p>
    </form>
</div>
