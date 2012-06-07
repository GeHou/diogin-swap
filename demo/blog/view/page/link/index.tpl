<?php self::link_third_script('jquery'); ?>
<?php self::link_third_script('jquery.form'); ?>

<div id="page">
    <form id="new_form" action="<?php self::php_url('link/new'); ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <p id="new_form_tips"></p>
        <?php self::csrf_field('member'); ?>
        <p><label for="new_form_name">名称</label><input type="text" id="new_form_name" name="name" /></p>
        <p><label for="new_form_url">地址</label><input type="text" id="new_form_url" name="url" /></p>
        <p><input type="submit" id="new_form_submit" name="submit" value="创建" /></p>
    </form>
    <?php if ($links === null): ?>
    
        暂无链接
        
    <?php else: ?>
    
        <ul>
            <?php foreach ($links as $link): ?>
                <li><?php echo $link->name; ?> | <?php echo $link->url; ?> | <a href="<?php self::php_url('link/edit?id=' . $link->id); ?>">修改</a> | <a href="<?php self::csrf_url('member', 'link/delete?id=' . $link->id); ?>" onclick="return delete_link(this);">删除</a></li>
            <?php endforeach; ?>
        </ul>
        
    <?php endif; ?>
</div>
