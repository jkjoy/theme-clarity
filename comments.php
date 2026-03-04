<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
if (!function_exists('threadedComments')) {
    function threadedComments($comments, $options)
    {
        $uaInfo = clarity_parse_user_agent((string) $comments->agent);
        $commentClass = 'comment-item';
        if ($comments->levels > 0) {
            $commentClass .= ' comment-child';
        }
        if ($comments->authorId) {
            if ($comments->authorId == $comments->ownerId) {
                $commentClass .= ' comment-by-author';
            } else {
                $commentClass .= ' comment-by-user';
            }
        }
        $replyToName = '';
        if ($comments->levels > 0 && !empty($comments->parent)) {
            $parentId = (int) $comments->parent;
            if ($parentId > 0) {
                static $parentAuthorCache = [];
                if (array_key_exists($parentId, $parentAuthorCache)) {
                    $replyToName = (string) $parentAuthorCache[$parentId];
                } else {
                    try {
                        $db = \Typecho\Db::get();
                        $row = $db->fetchRow(
                            $db->select('author')
                                ->from('table.comments')
                                ->where('coid = ?', $parentId)
                                ->limit(1)
                        );
                        $replyToName = isset($row['author']) ? (string) $row['author'] : '';
                    } catch (\Throwable $e) {
                        $replyToName = '';
                    }
                    $parentAuthorCache[$parentId] = $replyToName;
                }
            }
        }
        ?>
        <li id="<?php $comments->theId(); ?>" class="<?php echo $commentClass; ?>">
            <div class="comment-avatar">
                <?php $comments->gravatar(48, ''); ?>
            </div>
            <div class="comment-body">
                <div class="comment-header">
                    <span class="comment-author"><?php $comments->author(); ?></span>
                    <?php if (!empty($uaInfo['os']) || !empty($uaInfo['browser'])): ?>
                      <span class="comment-meta">
                        <?php if (!empty($uaInfo['os'])): ?>
                          <span class="comment-badge">
                            <?php if (!empty($uaInfo['os_icon'])): ?>
                              <span class="<?php echo $uaInfo['os_icon']; ?>"></span>
                            <?php endif; ?>
                            <span><?php echo htmlspecialchars($uaInfo['os'], ENT_QUOTES, 'UTF-8'); ?></span>
                          </span>
                        <?php endif; ?>
                        <?php if (!empty($uaInfo['browser'])): ?>
                          <span class="comment-badge">
                            <?php if (!empty($uaInfo['browser_icon'])): ?>
                              <span class="<?php echo $uaInfo['browser_icon']; ?>"></span>
                            <?php endif; ?>
                            <span><?php echo htmlspecialchars($uaInfo['browser'], ENT_QUOTES, 'UTF-8'); ?></span>
                          </span>
                        <?php endif; ?>
                      </span>
                    <?php endif; ?>
                    <time class="comment-time" datetime="<?php $comments->date('c'); ?>" title="<?php $comments->date(); ?>">
                      <?php echo htmlspecialchars($comments->dateWord, ENT_QUOTES, 'UTF-8'); ?>
                    </time>
                </div>
                <div class="comment-content">
                    <?php
                    $commentContent = (string) $comments->content;
                    if ($replyToName !== '') {
                        $mention = '<span class="comment-mention">@' . htmlspecialchars($replyToName, ENT_QUOTES, 'UTF-8') . '</span> ';
                        if (strpos($commentContent, '<p>') === 0) {
                            $commentContent = '<p>' . $mention . substr($commentContent, 3);
                        } else {
                            $commentContent = $mention . $commentContent;
                        }
                    }
                    echo $commentContent;
                    ?>
                </div>
                <div class="comment-footer">
                    <span class="comment-action">
                      <span class="icon-[ph--chat-circle-dots]"></span>
                      <?php $comments->reply(_t('回复')); ?>
                    </span>
                </div>
            </div>
            <?php if ($comments->children): ?>
              <div class="comment-children">
                <?php $comments->threadedComments($options); ?>
              </div>
            <?php endif; ?>
        </li>
        <?php
    }
}
?>

<?php $this->comments()->to($comments); ?>
<?php
$commentFormPosition = (string) clarity_opt('comment_form_position', 'before');
if ($commentFormPosition !== 'before' && $commentFormPosition !== 'after') {
    $commentFormPosition = 'before';
}

ob_start();
?>

<?php if ($this->allow('comment')): ?>
  <div class="comment-respond" id="<?php echo htmlspecialchars($this->respondId, ENT_QUOTES, 'UTF-8'); ?>">
    <div class="respond-title" id="comments">
      <?php _e('发表评论'); ?>
      <a
        rel="nofollow"
        id="cancel-comment-reply-link"
        href="#comment"
        style="display:none"
        onclick="return TypechoComment.cancelReply();"
      ><?php _e('取消回复'); ?></a>
    </div>
    <form method="post" action="<?php $this->commentUrl(); ?>" class="comment-form">
      <?php if ($this->user->hasLogin()): ?>
        <p class="logged-in">
          <?php _e('登录身份: '); ?><a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>
          <a href="<?php $this->options->logoutUrl(); ?>"><?php _e('退出'); ?></a>
        </p>
      <?php else: ?>
        <div class="comment-fields">
          <input type="text" name="author" class="comment-input" placeholder="<?php _e('昵称'); ?>" value="<?php $this->remember('author'); ?>" required />
          <input type="email" name="mail" class="comment-input" placeholder="<?php _e('邮箱'); ?>" value="<?php $this->remember('mail'); ?>" required />
          <input type="url" name="url" class="comment-input" placeholder="<?php _e('网址'); ?>" value="<?php $this->remember('url'); ?>" />
        </div>
      <?php endif; ?>
      <textarea name="text" class="comment-textarea" placeholder="<?php _e('写下你的评论...'); ?>" required></textarea>
      <div class="comment-actions">
        <button type="submit" class="z-btn primary">
          <span class="icon-[ph--paper-plane-right-bold]"></span>
          <span><?php _e('提交评论'); ?></span>
        </button>
      </div>
    </form>
  </div>
<?php else: ?>
  <p class="comments-closed"><?php _e('评论已关闭'); ?></p>
<?php endif; ?>

<?php $commentFormHtml = (string) ob_get_clean(); ?>

<?php if ($commentFormPosition === 'before'): ?>
  <?php echo $commentFormHtml; ?>
<?php endif; ?>

<?php if ($comments->have()): ?>
  <?php $comments->listComments(); ?>
  <?php $comments->pageNav('&laquo;', '&raquo;'); ?>
<?php endif; ?>

<?php if ($commentFormPosition === 'after'): ?>
  <?php echo $commentFormHtml; ?>
<?php endif; ?>
