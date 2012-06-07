<?php self::use_tpllet('text'); ?>

<div id="page">
    <p><a href="<?php self::php_url('site/index'); ?>">论坛首页</a> &gt;
       <a href="<?php self::php_url('board/show?id=' . $board->id); ?>"><?php echo $board->name; ?></a> &gt;
       <a href="<?php self::php_url('topic/show?id=' . $topic->id); ?>"><?php echo $topic->title; ?></a></p>
    <p><?php echo $topic->title; ?></p>
    <ul class="replies">
        <?php foreach ($replies as $reply): ?>
            <li class="post">
                <div class="info">
                    <h4><a href="<?php self::php_url('user/show?id=' . $reply->user->id); ?>"><?php echo $reply->user->name; ?></a></h4>
                    <a href="<?php self::php_url('user/show?id=' . $reply->user->id); ?>"><img src="<?php $reply->user->avatar === '' ? self::media_url('avatar.gif') : self::upload_url($reply->user->avatar); ?>" /></a>
                    <ul>
                        <li>注册时间：<?php echo date('Y-m-d', $reply->user->register_time); ?></li>
                        <li>主题总数：<?php echo $reply->user->topic_count; ?></li>
                        <li>回复总数：<?php echo $reply->user->reply_count; ?></li>
                    </ul>
                </div>
                <div class="content">
                    <h5>发表时间：<?php echo date('Y-m-d H:i:s', $reply->pub_time); ?></h5>
                    <div><?php show_text($reply->content); ?></div>
                </div>
                <div class="clearfix"></div>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php self::include_block('pager'); ?>
    <?php if ($logined): ?>
        <?php self::link_third_script('jquery'); ?>
        <?php self::link_third_script('jquery.form'); ?>
        <form id="new_form" action="<?php self::php_url('reply/new?topic_id=' . $topic->id); ?>" method="POST" enctype="application/x-www-form-urlencoded">
            <p id="new_form_tips"></p>
            <?php self::csrf_field('user'); ?>
            <p><label for="new_form_content">内容</label><textarea id="new_form_content" name="content"></textarea></p>
            <p><input type="submit" id="new_form_submit" name="submit" value="回复" /></p>
        </form>
    <?php endif; ?>
</div>
