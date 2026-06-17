<?php
require_once __DIR__ . '/includes/auth.php';

$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare(
    'SELECT t.*, c.title AS chapter_title, c.slug AS chapter_slug, c.id AS cid
     FROM topics t JOIN chapters c ON c.id = t.chapter_id WHERE t.slug = ?'
);
$stmt->execute([$slug]);
$topic = $stmt->fetch();
if (!$topic) {
    http_response_code(404);
    require __DIR__ . '/partials/header.php';
    echo '<div class="alert alert-danger">Topic not found.</div>';
    require __DIR__ . '/partials/footer.php';
    exit;
}

$tid = (int) $topic['id'];
$cid = (int) $topic['cid'];
$uid = current_user_id();

// Subtopics & code snippets.
$subStmt = db()->prepare('SELECT * FROM subtopics WHERE topic_id = ? ORDER BY sort_order, id');
$subStmt->execute([$tid]);
$subtopics = $subStmt->fetchAll();

$codeStmt = db()->prepare('SELECT * FROM code_snippets WHERE topic_id = ? ORDER BY sort_order, id');
$codeStmt->execute([$tid]);
$codeByLang = [];
foreach ($codeStmt->fetchAll() as $snip) {
    $codeByLang[$snip['language']][] = $snip;
}

// Prev / next within the chapter.
$siblings = db()->prepare('SELECT id, title, slug FROM topics WHERE chapter_id = ? ORDER BY sort_order, id');
$siblings->execute([$cid]);
$sibList = $siblings->fetchAll();
$prev = $next = null;
foreach ($sibList as $i => $s) {
    if ((int) $s['id'] === $tid) {
        $prev = $sibList[$i - 1] ?? null;
        $next = $sibList[$i + 1] ?? null;
        break;
    }
}

// User-specific state.
$isDone = $isBookmarked = false;
$note = '';
$chapterPct = 0;
if ($uid) {
    record_activity($uid);
    $s = db()->prepare('SELECT 1 FROM user_progress WHERE user_id=? AND topic_id=? AND status="completed"');
    $s->execute([$uid, $tid]);
    $isDone = (bool) $s->fetchColumn();

    $s = db()->prepare('SELECT 1 FROM bookmarks WHERE user_id=? AND topic_id=?');
    $s->execute([$uid, $tid]);
    $isBookmarked = (bool) $s->fetchColumn();

    $s = db()->prepare('SELECT note_text FROM user_notes WHERE user_id=? AND topic_id=?');
    $s->execute([$uid, $tid]);
    $note = (string) ($s->fetchColumn() ?: '');

    $chapterPct = chapter_progress($uid, $cid)['percent'];
}

$prefLang  = $uid ? current_language() : 'php';
$langOrder = array_keys(LANGUAGES);
// Choose which language tab is active: preferred if it has code, else first available.
$activeLang = !empty($codeByLang[$prefLang])
    ? $prefLang
    : (function () use ($langOrder, $codeByLang) {
        foreach ($langOrder as $l) {
            if (!empty($codeByLang[$l])) return $l;
        }
        return null;
    })();

$currentChapterId = $cid;
$currentTopicId   = $tid;
$pageTitle = $topic['title'];
require __DIR__ . '/partials/header.php';
?>
<div class="row">
  <!-- Sidebar -->
  <div class="col-lg-3 mb-4">
    <?php require __DIR__ . '/partials/sidebar.php'; ?>
  </div>

  <!-- Lesson -->
  <div class="col-lg-9">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= url('roadmap.php') ?>">Roadmap</a></li>
        <li class="breadcrumb-item"><a href="<?= url('chapter.php?slug=' . urlencode($topic['chapter_slug'])) ?>"><?= e($topic['chapter_title']) ?></a></li>
        <li class="breadcrumb-item active"><?= e($topic['title']) ?></li>
      </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
      <h1 class="mb-0"><?= e($topic['title']) ?>
        <?php if ($uid): ?><span class="badge bg-secondary align-middle" id="chapter-progress-badge"><?= $chapterPct ?>%</span><?php endif; ?>
      </h1>
      <?php if ($uid): ?>
      <div class="btn-group">
        <button id="bookmark-btn" class="btn btn-sm btn-outline-warning" data-topic-id="<?= $tid ?>" data-on="<?= $isBookmarked ? '1' : '0' ?>">
          <i class="bi bi-bookmark-star<?= $isBookmarked ? '-fill' : '' ?>"></i> <?= $isBookmarked ? 'Bookmarked' : 'Bookmark' ?>
        </button>
        <button id="mark-complete-btn" class="btn btn-sm <?= $isDone ? 'btn-success' : 'btn-outline-success' ?>" data-topic-id="<?= $tid ?>" data-completed="<?= $isDone ? '1' : '0' ?>">
          <i class="bi bi-<?= $isDone ? 'check-circle-fill' : 'circle' ?>"></i> <?= $isDone ? 'Completed' : 'Mark as complete' ?>
        </button>
      </div>
      <?php endif; ?>
    </div>

    <?php if ($topic['summary']): ?><p class="lead text-muted"><?= e($topic['summary']) ?></p><?php endif; ?>

    <div class="lesson-content">
      <?php if ($topic['theory_md']): ?>
        <?= render_markdown($topic['theory_md']) ?>
      <?php endif; ?>

      <?php foreach ($subtopics as $sub): ?>
        <section id="<?= e($sub['slug']) ?>">
          <h2><?= e($sub['title']) ?></h2>
          <?= render_markdown($sub['body_md']) ?>
        </section>
      <?php endforeach; ?>

      <?php if ($topic['complexity_md']): ?>
        <h2><i class="bi bi-speedometer2"></i> Complexity</h2>
        <?= render_markdown($topic['complexity_md']) ?>
      <?php endif; ?>

      <?php if ($topic['real_world_md']): ?>
        <h2><i class="bi bi-lightbulb"></i> Real-World Examples</h2>
        <div class="alert alert-light border"><?= render_markdown($topic['real_world_md']) ?></div>
      <?php endif; ?>
    </div>

    <!-- Code examples in multiple languages -->
    <?php if ($codeByLang): ?>
      <h2 class="mt-4"><i class="bi bi-code-slash"></i> Code Examples</h2>
      <?php $tabId = 'code' . $tid; ?>
      <ul class="nav nav-tabs" role="tablist">
        <?php foreach ($langOrder as $lang):
            if (empty($codeByLang[$lang])) continue;
        ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= $lang === $activeLang ? 'active' : '' ?>" data-lang-tab="<?= $lang ?>"
                  data-bs-toggle="tab" data-bs-target="#<?= $tabId . $lang ?>" type="button" role="tab">
            <?= e(lang_label($lang)) ?>
          </button>
        </li>
        <?php endforeach; ?>
      </ul>
      <div class="tab-content border border-top-0 rounded-bottom p-3 mb-3 bg-white">
        <?php foreach ($langOrder as $lang):
            if (empty($codeByLang[$lang])) continue;
        ?>
        <div class="tab-pane code-tab-pane fade <?= $lang === $activeLang ? 'show active' : '' ?>" id="<?= $tabId . $lang ?>" role="tabpanel">
          <?php foreach ($codeByLang[$lang] as $snip): ?>
            <?php if ($snip['label']): ?><h6 class="text-muted"><?= e($snip['label']) ?></h6><?php endif; ?>
            <pre><code class="language-<?= e($lang) ?>"><?= e($snip['code']) ?></code></pre>
            <?php if ($snip['explanation_md']): ?><div class="small text-muted mb-3"><?= render_markdown($snip['explanation_md']) ?></div><?php endif; ?>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!$uid): ?>
      <div class="alert alert-info"><a href="<?= url('login.php') ?>">Log in</a> to track progress, take notes, and bookmark topics.</div>
    <?php else: ?>
      <!-- Notes -->
      <h2 class="mt-4"><i class="bi bi-journal-text"></i> My Notes &amp; Key Takeaways</h2>
      <textarea id="note-text" class="form-control" rows="4" data-topic-id="<?= $tid ?>"
                placeholder="Jot down key takeaways for revision..."><?= e($note) ?></textarea>
      <div class="form-text" id="note-status">Auto-saves as you type.</div>
    <?php endif; ?>

    <!-- Prev / Next -->
    <div class="d-flex justify-content-between mt-4">
      <?php if ($prev): ?>
        <a href="<?= url('topic.php?slug=' . urlencode($prev['slug'])) ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> <?= e($prev['title']) ?></a>
      <?php else: ?><span></span><?php endif; ?>
      <?php if ($next): ?>
        <a href="<?= url('topic.php?slug=' . urlencode($next['slug'])) ?>" class="btn btn-outline-info"><?= e($next['title']) ?> <i class="bi bi-arrow-right"></i></a>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
