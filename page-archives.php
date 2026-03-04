<?php
/**
 * 文章归档
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

clarity_set('showAside', true);
clarity_set('pageTitle', '归档');
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>
<?php
$siteStart = trim((string) clarity_opt('site_start_time', ''));
$ownerBirthday = trim((string) clarity_opt('owner_birthday', ''));
$archivesYears = clarity_bool(clarity_opt('archives_years', '1')) ? 'true' : 'false';

$groups = [];
$pageSize = 0;
try {
    $stat = \Typecho\Widget::widget('Widget_Stat');
    $pageSize = max(0, (int) ($stat->publishedPostsNum ?? 0));
} catch (\Throwable $e) {
}

if ($pageSize <= 0) {
    try {
        $db = \Typecho\Db::get();
        $counter = $db->fetchObject(
            $db->select(['COUNT(cid)' => 'num'])
                ->from('table.contents')
                ->where('table.contents.type = ?', 'post')
                ->where('table.contents.status = ?', 'publish')
        );
        $pageSize = max(0, (int) ($counter->num ?? 0));
    } catch (\Throwable $e) {
        $pageSize = 0;
    }
}

try {
    if ($pageSize > 0) {
        \Typecho\Widget::widget('Widget_Contents_Post_Recent', 'pageSize=' . $pageSize)->to($archives);
        while ($archives->next()) {
            try {
                $created = (int) ($archives->created ?? 0);
                if ($created <= 0) {
                    continue;
                }

                $year = date('Y', $created);
                $month = date('n', $created);
                if (!isset($groups[$year])) {
                    $groups[$year] = ['year' => $year, 'months' => []];
                }
                if (!isset($groups[$year]['months'][$month])) {
                    $groups[$year]['months'][$month] = [];
                }
                $groups[$year]['months'][$month][] = [
                    'title' => $archives->title ?? '',
                    'permalink' => $archives->permalink ?? '',
                    'created' => $created,
                    'excerpt' => clarity_get_excerpt($archives, 120),
                    'cover' => clarity_get_cover($archives)
                ];
            } catch (\Throwable $e) {
                continue;
            }
        }
    }
} catch (\Throwable $e) {
}

if (!empty($groups)) {
    krsort($groups, SORT_NUMERIC);
    foreach ($groups as &$yearGroup) {
        if (isset($yearGroup['months']) && is_array($yearGroup['months'])) {
            krsort($yearGroup['months'], SORT_NUMERIC);
        }
    }
    unset($yearGroup);
}
?>

<div class="archive proper-height">
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
                <span><?php echo clarity_display_text((string) $categories->name); ?></span>
              </a>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  </div>

  <?php if (!empty($groups)): ?>
    <?php foreach ($groups as $year => $group): ?>
      <?php $monthsCount = isset($group['months']) ? count($group['months']) : 0; ?>
      <section class="archive-group">
        <div class="archive-title">
          <h2 class="archive-year">
            <?php echo htmlspecialchars((string) $year, ENT_QUOTES, 'UTF-8'); ?>
          </h2>

          <div class="archive-age" data-year="<?php echo htmlspecialchars((string) $year, ENT_QUOTES, 'UTF-8'); ?>" data-start-year="<?php echo htmlspecialchars((string) $siteStart, ENT_QUOTES, 'UTF-8'); ?>" data-owner-birthday="<?php echo htmlspecialchars((string) $ownerBirthday, ENT_QUOTES, 'UTF-8'); ?>" data-archives-years="<?php echo $archivesYears; ?>">
            <span class="age-num">-</span>
            <span class="age-label">岁</span>
          </div>

          <div class="archive-info">
            <span><?php echo $monthsCount; ?>月</span>
          </div>
        </div>

        <menu class="archive-list">
          <?php foreach ($group['months'] as $month => $posts): ?>
            <?php foreach ($posts as $index => $post): ?>
              <?php
              $delay = $index * 0.03;
              $created = $post['created'] ?? 0;
              $timeText = $created ? date('m/d', $created) : '';
              $cover = $post['cover'] ?? '';
              $excerpt = $post['excerpt'] ?? '';
              $postTitle = clarity_display_text((string) ($post['title'] ?? ''));
              ?>
              <li class="article-item" style="--delay: <?php echo $delay; ?>s">
                <time><?php echo htmlspecialchars((string) $timeText, ENT_QUOTES, 'UTF-8'); ?></time>
                <a href="<?php echo htmlspecialchars((string) $post['permalink'], ENT_QUOTES, 'UTF-8'); ?>" class="article-link gradient-card" title="<?php echo htmlspecialchars((string) $excerpt, ENT_QUOTES, 'UTF-8'); ?>">
                  <span class="article-title"><?php echo $postTitle; ?></span>
                  <?php if ($cover !== ''): ?>
                    <img src="<?php echo htmlspecialchars((string) $cover, ENT_QUOTES, 'UTF-8'); ?>" class="article-cover" loading="lazy" alt="<?php echo $postTitle; ?>" />
                  <?php endif; ?>
                </a>
              </li>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </menu>
      </section>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="empty-state">
      <span class="icon-[ph--calendar-blank-bold]"></span>
      <p>暂无归档</p>
    </div>
  <?php endif; ?>

  <script>
    (function () {
      document.querySelectorAll('.archive-age').forEach((el) => {
        const currentYear = parseInt(el.dataset.year);
        const useSiteStartTime = el.dataset.archivesYears === 'true';
        const startStr = useSiteStartTime ? el.dataset.startYear : el.dataset.ownerBirthday;

        if (startStr && startStr.length >= 4) {
          const startYear = parseInt(startStr.substring(0, 4));
          const age = currentYear - startYear;
          el.querySelector('.age-num').textContent = age < 0 ? 0 : age;
        } else {
          el.style.display = 'none';
        }
      });
    })();
  </script>
</div>

<?php $this->need('footer.php'); ?>
