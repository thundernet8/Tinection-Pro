<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/10/09 19:39
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://www.webapproach.net/tint.html
 */
?>
<?php
/**
 * 评论/更新/删除文章时添加评论时间字段
 *
 * @since   2.0.0
 * @param   $comment_ID
 * @param   $comment_approved
 * @param   $commentdata
 * @return  void
 */
function tt_update_post_latest_reviewed_meta($comment_ID, $comment_approved, $commentdata){
    if(!$comment_approved) return;
    //$comment = get_comment($comment_ID);
    //$post_id = $comment->comment_post_ID;
    $post_id = (int)$commentdata['comment_post_ID'];
    update_post_meta($post_id,'tt_latest_reviewed',time());
}
add_action('comment_post','tt_update_post_latest_reviewed_meta');


/**
 * 评论列表输出callback
 *
 * @since   2.0.0
 * @param   $comment
 * @param   $args
 * @param   $depth
 */
function tt_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>" data-current-comment-id="<?php echo $comment->comment_ID; ?>" data-parent-comment-id="<?php echo $comment->comment_parent; ?>" data-member-id="<?php echo $comment->user_id; ?>">

    <div class="comment-left pull-left">
        <?php if($comment->user_id > 0) { ?>
        <a rel="nofollow" href="<?php echo get_author_posts_url($comment->user_id); ?>">
            <img class="avatar" src="<?php echo tt_get_avatar( $comment->user_id, 50 ); ?>">
        </a>
        <?php }else{ ?>
        <a rel="nofollow" href="javascript: void(0)">
            <img class="avatar" src="<?php echo tt_get_avatar( $comment->comment_author, 50 ); ?>">
        </a>
        <?php } ?>
    </div>

    <div class="comment-body">
        <div class="comment-content">
            <?php if($comment->user_id != 0) { ?>
                <a rel="nofollow" href="<?php echo get_author_posts_url($comment->user_id); ?>" class="name replyName"><?php echo $comment->comment_author; ?></a>
            <?php }else{ ?>
                <a rel="nofollow" href="javascript: void(0)" class="name replyName"><?php echo $comment->comment_author; ?></a>
            <?php } ?>
            <!--a class="xb type" href="http://fuli.leiphone.com/guide#module3" target="_blank"></a--><!-- //TODO vip/ip mark -->
            <!--                    --><?php //if(tt_get_option('comment_vip')=='on') get_author_class($comment->comment_author_email,$comment->user_id); ?>
            <!--                    --><?php //if(tt_get_option('comment_ua')=='on') echo outputbrowser($comment->comment_agent); ?>
            <!--                    --><?php //if(tt_get_option('comment_ip')=='on') { ?><!--<span class="comment_author_ip tooltip-trigger" title="--><?php //echo sprintf(__('来自%1$s','tt'), convertip(get_comment_author_ip())); ?><!--"><img class="ip_img" src="--><?php //echo THEME_URI.'/images/ua/ip.png'; ?><!--"></span>--><?php //} ?>
            <?php if ( $comment->comment_approved == '0' ) : ?>
                <span class="pending-comment;"><?php $parent = $comment->comment_parent; if($parent != 0)echo '@'; comment_author_link($parent) ?><?php _e('Your comment is under review...','tt'); ?></span>
                <br />
            <?php endif; ?>
            <?php if ( $comment->comment_approved == '1' ) : ?>
                <?php comment_text() ?>
            <?php endif; ?>
        </div>

        <span class="comment-time"><?php echo Utils::getTimeDiffString(get_comment_time('Y-m-d G:i:s', true)); ?></span>
        <div class="comment-meta">
            <a href="javascript:;" class="respond-coin mr5" title="<?php _e('Reply', 'tt'); ?>"><i class="msg"></i><em><?php _e('Reply', 'tt'); ?></em></a>
            <span class="like"><i class="zan"></i><em class="like-count">(<?php echo (int)get_comment_meta($comment->comment_ID,'tt_comment_likes',true); ?>)</em></span>
        </div>

        <ul class="csl-respond">
        </ul>

        <div class="respond-submit">
            <div class="text"><input type="text"><div class="tip"><?php _e('Reply', 'tt'); ?><a><?php echo $comment->comment_author; ?></a>：</div></div>
            <div class="submit-box clearfix">
                <span class="emotion-ico transition" data-emotion="0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="tico tico-smile-o"></i><?php _e('Emotion', 'tt'); ?></span>
                <button class="btn btn-danger pull-right" id="reply-submit" type="submit" title="<?php _e('Submit', 'tt'); ?>"><?php _e('Submit', 'tt'); ?></button>
                <div class="qqFace  dropdown-menu">
                </div>
            </div>
        </div>
    </div>
    <?php
}

function tt_end_comment() {
    echo '</li>';
}