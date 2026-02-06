<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 基于 halo主题 Clarity 修改的 Typecho 主题
 * @package Clarity
 * @author jkjoy
 * @version 1.1.0
 * @link https://jkjoy.de
 */
clarity_set('showAside', true);
clarity_set('pageTitle', null);
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>
<?php
$logoFallback = \Typecho\Common::url('assets/images/logo.svg', $this->options->themeUrl);
$logo = clarity_site_logo($logoFallback);
$showTitle = clarity_bool(clarity_opt('show_title', '1'));
$subtitle = trim((string) clarity_opt('subtitle', $this->options->description));
$emojiTail = trim((string) clarity_opt('emoji_tail', ''));
$featuredPosts = clarity_featured_posts();
$featuredOnlyHome = clarity_bool(clarity_opt('featured_posts_page', '1'));
$showFeatured = !empty($featuredPosts) && (!$featuredOnlyHome || $this->getCurrentPage() == 1);
$showDefaultCover = clarity_bool(clarity_opt('default_cover', '1'));
$showAuthor = clarity_bool(clarity_opt('show_post_author', '1'));
$isHomePage = $this->getCurrentPage() == 1;
$stickyCids = clarity_get_sticky_cids();
$stickyMap = !empty($stickyCids) ? array_fill_keys($stickyCids, true) : [];
$stickyPosts = ($isHomePage && !empty($stickyCids)) ? clarity_get_sticky_posts($stickyCids) : [];
?>

<div class="clarity-header mobile-only">
  <?php if ($emojiTail !== ''): ?>
    <div class="emoji-tail">
      <?php
      $emojis = array_filter(array_map('trim', explode(',', $emojiTail)));
      foreach ($emojis as $index => $emoji):
      ?>
        <span class="split-char" style="--delay: <?php echo ($index * 0.6 - 3); ?>s"><?php echo $emoji; ?></span>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <a href="<?php echo $this->options->siteUrl; ?>">
    <?php if ($logo): ?>
      <img src="<?php echo htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" class="clarity-logo<?php echo $showTitle ? ' circle' : ''; ?>" alt="<?php echo htmlspecialchars($this->options->title, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php endif; ?>
  </a>

  <?php if ($showTitle): ?>
    <div class="clarity-text">
      <div class="header-title">
        <?php
        $chars = preg_split('//u', $this->options->title, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($chars as $idx => $char):
        ?>
          <span class="split-char" style="--delay: <?php echo (($idx + 1) * 0.1); ?>s"><?php echo htmlspecialchars($char, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php endforeach; ?>
      </div>
      <div class="header-subtitle"><?php echo htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8'); ?></div>
    </div>
  <?php endif; ?>
</div>

<?php if ($showFeatured): ?>
  <?php clarity_render_featured_posts($featuredPosts); ?>
<?php endif; ?>

<div class="post-list">
  <div class="toolbar">
    <div></div>
    <div class="order-toggle">
      <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="dropdown-trigger">
          <span class="icon-[ph--folder-bold]"></span>
          <span>全部分类</span>
          <span class="icon-[ph--caret-down-bold]" style="font-size:0.75em;"></span>
        </div>
        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[9999] w-48 p-2 shadow-lg">
          <li><a href="<?php echo $this->options->siteUrl; ?>" class="active"><span class="icon-[ph--squares-four-bold]"></span>全部分类</a></li>
          <?php $this->widget('Widget_Metas_Category_List')->to($categories); ?>
          <?php while ($categories->next()): ?>
            <li class="cat-level level-<?php echo (int) $categories->levels; ?>">
              <a href="<?php echo $categories->permalink; ?>">
                <?php if ($categories->levels > 0): ?>
                  <span class="cat-indent">
                    <?php for ($i = 0; $i < $categories->levels; $i++): ?>
                      <span class="cat-indent-line"></span>
                    <?php endfor; ?>
                    <span class="cat-indent-corner">└</span>
                  </span>
                <?php endif; ?>
                <span class="icon-[ph--folder]"></span>
                <span><?php echo htmlspecialchars($categories->name, ENT_QUOTES, 'UTF-8'); ?></span>
              </a>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  </div>

  <menu class="posts-container proper-height">
    <?php
    $renderCard = function ($post, bool $isSticky) use ($showDefaultCover, $showAuthor) {
        $cover = clarity_get_cover($post);
        $excerpt = clarity_get_excerpt($post, 120);
        $views = clarity_get_views($post);
        ob_start();
    ?>
      <article class="article-card card" style="--delay: __CLARITY_DELAY__s">
        <a href="<?php $post->permalink(); ?>">
          <?php if ($cover !== ''): ?>
            <img src="<?php echo htmlspecialchars($cover, ENT_QUOTES, 'UTF-8'); ?>" class="article-cover" loading="lazy" alt="<?php $post->title(); ?>" />
          <?php elseif ($showDefaultCover): ?>
            <div class="article-cover default-cover">
              <span class="default-cover-title"><?php $post->title(); ?></span>
            </div>
          <?php endif; ?>
        </a>

        <div class="article-body">
          <h2 class="article-title text-creative">
            <a href="<?php $post->permalink(); ?>"><?php $post->title(); ?></a>
            <?php if ($isSticky): ?>
              <span class="pinned-post">
                <svg
                  t="1765477590308"
                  class="icon"
                  viewBox="0 0 1024 1024"
                  version="1.1"
                  xmlns="http://www.w3.org/2000/svg"
                  p-id="9679"
                  width="200"
                  height="200"
                >
                  <path
                    d="M450 230c37.16-33.787 94.508-31.666 129 5l319 344c8.957 9.352 14 22.087 14 35 0 29.03-23.404 52.012-52 52H733a8 8 0 0 0-8 8v150c0 70.692-57.308 128-128 128H427c-70.692 0-128-57.308-128-128V674a8 8 0 0 0-8-8H164c-13.106 0.012-25.431-4.704-35-13v-1c-21.566-19.037-22.505-51.627-3-73l318-343c1.757-1.842 3.36-3.467 5-5z m78.351 57.65c-9.674-9.061-24.861-8.565-33.923 1.109L213.618 588.53A8 8 0 0 0 219.455 602H311c28.418 0 51.483 22.81 52 51v171c0 35.346 28.654 64 64 64h170c35.346 0 64-28.654 64-64V654c0.01-28.709 23.253-51.982 52-52h89.625a8 8 0 0 0 5.856-13.45l-278.97-299.735a24 24 0 0 0-1.16-1.164zM838 81c17.673 0 32 14.327 32 32 0 17.673-14.327 32-32 32H182c-17.673 0-32-14.327-32-32 0-17.673 14.327-32 32-32h656z"
                    fill="currentColor"
                    p-id="9680"
                  ></path>
                </svg>
              </span>
            <?php endif; ?>
          </h2>

          <?php if ($excerpt !== ''): ?>
            <p class="article-description"><?php echo htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>

          <div class="article-info">
            <span class="info-item">
              <span class="icon-[ph--calendar-dots-bold]"></span>
              <time><?php $post->date('Y-m-d'); ?></time>
            </span>

            <?php if ($post->categories): ?>
              <span class="info-item article-category">
                <span class="icon-[ph--folder-bold]"></span>
                <?php $post->category(','); ?>
              </span>
            <?php endif; ?>

            <span class="info-item">
              <span class="icon-[ph--eye-bold]"></span>
              <span><?php echo $views !== null ? $views : 0; ?></span>
            </span>

            <?php if ($showAuthor): ?>
              <?php clarity_render_author_capsule($post); ?>
            <?php endif; ?>
          </div>
        </div>
      </article>
    <?php
        return ob_get_clean();
    };

    $stickyHtml = [];
    $normalHtml = [];
    if ($isHomePage && !empty($stickyPosts)) {
        foreach ($stickyPosts as $stickyPost) {
            $stickyHtml[] = $renderCard($stickyPost, true);
        }
    }

    while ($this->next()):
        if (isset($stickyMap[(int) $this->cid])) {
            continue;
        }
        $isSticky = isset($this->fields->sticky) && clarity_bool($this->fields->sticky);
        $normalHtml[] = $renderCard($this, $isSticky);
    endwhile;

    $delayIndex = 0;
    foreach (array_merge($stickyHtml, $normalHtml) as $cardHtml) {
        $delay = $delayIndex * 0.05;
        echo str_replace('__CLARITY_DELAY__', (string) $delay, $cardHtml);
        $delayIndex++;
    }
    ?>
  </menu>

  <?php clarity_render_pagination($this, 'index'); ?>
</div>

<?php $this->need('footer.php'); ?>
