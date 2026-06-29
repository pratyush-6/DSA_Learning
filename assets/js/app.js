/* DSALearn — front-end interactions */
(function () {
  'use strict';

  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const api = (path) => `${window.BASE_URL}/api/${path}`;

  // ---- Toasts -------------------------------------------------------------
  function toast(message, type = '') {
    const stack = document.getElementById('toast-stack');
    if (!stack) { return; }
    const el = document.createElement('div');
    el.className = 'toast-msg ' + type;
    el.textContent = message;
    stack.appendChild(el);
    setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity .3s'; }, 3200);
    setTimeout(() => el.remove(), 3600);
  }
  window.dsaToast = toast;

  // ---- Theme toggle -------------------------------------------------------
  const root = document.documentElement;
  function applyThemeIcon() {
    const dark = root.getAttribute('data-bs-theme') === 'dark';
    document.querySelectorAll('[data-theme-icon]').forEach((i) => {
      i.className = dark ? 'bi bi-sun' : 'bi bi-moon-stars';
    });
  }
  applyThemeIcon();
  document.getElementById('theme-toggle')?.addEventListener('click', () => {
    const next = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
    root.setAttribute('data-bs-theme', next);
    try { localStorage.setItem('dsa_theme', next); } catch (e) {}
    applyThemeIcon();
  });

  // ---- Fetch helper -------------------------------------------------------
  async function postJson(url, data) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
      body: JSON.stringify(data),
    });
    let json = null;
    try { json = await res.json(); } catch (e) { json = null; }
    if (!res.ok || !json) { return { ok: false, error: (json && json.error) || ('http_' + res.status) }; }
    return json;
  }

  // ---- Mark topic (module) complete --------------------------------------
  const completeBtn = document.getElementById('mark-complete-btn');
  if (completeBtn) {
    completeBtn.addEventListener('click', async () => {
      const topicId = parseInt(completeBtn.dataset.topicId, 10);
      const makeDone = completeBtn.dataset.completed !== '1';
      completeBtn.disabled = true;
      try {
        const r = await postJson(api('progress.php'), { topic_id: topicId, completed: makeDone });
        if (r.ok) {
          setCompleteState(makeDone, r.chapter_percent);
          toast(makeDone ? 'Module marked complete ✓' : 'Marked as not complete', 'ok');
        } else { toast('Could not save: ' + r.error, 'bad'); }
      } catch (e) { toast('Network error. Please try again.', 'bad'); }
      finally { completeBtn.disabled = false; }
    });
  }
  function setCompleteState(done, chapterPercent) {
    completeBtn.dataset.completed = done ? '1' : '0';
    completeBtn.classList.toggle('btn-success', done);
    completeBtn.classList.toggle('btn-outline-success', !done);
    completeBtn.innerHTML = done
      ? '<i class="bi bi-check-circle-fill"></i> Completed'
      : '<i class="bi bi-circle"></i> Mark as complete';
    const badge = document.getElementById('chapter-progress-badge');
    if (badge && typeof chapterPercent === 'number') { badge.textContent = chapterPercent + '%'; }
  }

  // ---- Mark practice problem (question) solved ---------------------------
  const solveBtn = document.getElementById('solve-btn');
  if (solveBtn) {
    solveBtn.addEventListener('click', async () => {
      const problemId = parseInt(solveBtn.dataset.problemId, 10);
      const makeSolved = solveBtn.dataset.solved !== '1';
      solveBtn.disabled = true;
      try {
        const r = await postJson(api('solve.php'), { problem_id: problemId, solved: makeSolved });
        if (r.ok) {
          solveBtn.dataset.solved = makeSolved ? '1' : '0';
          solveBtn.classList.toggle('btn-success', makeSolved);
          solveBtn.classList.toggle('btn-outline-success', !makeSolved);
          solveBtn.innerHTML = makeSolved
            ? '<i class="bi bi-check-circle-fill"></i> Solved'
            : '<i class="bi bi-circle"></i> Mark as solved';
          toast(makeSolved ? 'Nice! Question marked solved ✓' : 'Removed from solved', 'ok');
        } else { toast('Could not save: ' + r.error, 'bad'); }
      } catch (e) { toast('Network error. Please try again.', 'bad'); }
      finally { solveBtn.disabled = false; }
    });
  }

  // ---- Bookmark toggle ----------------------------------------------------
  const bookmarkBtn = document.getElementById('bookmark-btn');
  if (bookmarkBtn) {
    bookmarkBtn.addEventListener('click', async () => {
      const topicId = parseInt(bookmarkBtn.dataset.topicId, 10);
      const makeOn = bookmarkBtn.dataset.on !== '1';
      bookmarkBtn.disabled = true;
      try {
        const r = await postJson(api('bookmark.php'), { topic_id: topicId, on: makeOn });
        if (r.ok) {
          bookmarkBtn.dataset.on = makeOn ? '1' : '0';
          bookmarkBtn.innerHTML = makeOn
            ? '<i class="bi bi-bookmark-star-fill"></i> Bookmarked'
            : '<i class="bi bi-bookmark-star"></i> Bookmark';
          toast(makeOn ? 'Bookmarked' : 'Bookmark removed', 'ok');
        }
      } finally { bookmarkBtn.disabled = false; }
    });
  }

  // ---- Notes auto-save ----------------------------------------------------
  const noteArea = document.getElementById('note-text');
  const noteStatus = document.getElementById('note-status');
  if (noteArea) {
    let timer = null;
    noteArea.addEventListener('input', () => {
      clearTimeout(timer);
      if (noteStatus) noteStatus.textContent = 'Saving…';
      timer = setTimeout(async () => {
        const r = await postJson(api('note.php'), { topic_id: parseInt(noteArea.dataset.topicId, 10), note: noteArea.value });
        if (noteStatus) noteStatus.textContent = r.ok ? 'Saved ✓' : 'Error saving';
      }, 700);
    });
  }

  // ---- Language tab sync --------------------------------------------------
  document.querySelectorAll('[data-lang-tab]').forEach((tab) => {
    tab.addEventListener('shown.bs.tab', () => {
      const lang = tab.dataset.langTab;
      try { localStorage.setItem('dsa_lang', lang); } catch (e) {}
      document.querySelectorAll(`[data-lang-tab="${lang}"]`).forEach((other) => {
        if (other !== tab && window.bootstrap) { bootstrap.Tab.getOrCreateInstance(other).show(); }
      });
    });
  });

  // ---- Quiz submission ----------------------------------------------------
  const quizForm = document.getElementById('quiz-form');
  if (quizForm) {
    quizForm.addEventListener('submit', async (ev) => {
      ev.preventDefault();
      const quizId = parseInt(quizForm.dataset.quizId, 10);
      const answers = {};
      quizForm.querySelectorAll('input[type=radio]:checked').forEach((inp) => {
        answers[inp.name.replace('q_', '')] = parseInt(inp.value, 10);
      });
      const r = await postJson(api('quiz_submit.php'), { quiz_id: quizId, answers });
      if (!r.ok) { toast('Could not submit quiz', 'bad'); return; }
      renderQuizResult(r);
    });
  }
  function renderQuizResult(r) {
    const result = document.getElementById('quiz-result');
    if (result) {
      result.innerHTML =
        `<div class="alert alert-info"><h5 class="mb-1">Score: ${r.score} / ${r.total}</h5>` +
        `<div>${Math.round((r.score / r.total) * 100)}% correct</div></div>`;
      result.scrollIntoView({ behavior: 'smooth' });
    }
    (r.details || []).forEach((d) => {
      const card = document.querySelector(`[data-question-id="${d.question_id}"]`);
      if (!card) return;
      card.querySelectorAll('.form-check').forEach((fc) => {
        const val = parseInt(fc.querySelector('input').value, 10);
        if (val === d.correct_option_id) fc.classList.add('text-success', 'fw-bold');
        if (val === d.chosen_option_id && !d.correct) fc.classList.add('text-danger');
      });
      const exp = card.querySelector('.quiz-explanation');
      if (exp) exp.classList.remove('d-none');
    });
  }

  // ---- Generic form loading states ---------------------------------------
  document.querySelectorAll('form').forEach((f) => {
    if (f.id === 'quiz-form' || f.getAttribute('role') === 'search') return;
    if ((f.getAttribute('method') || '').toLowerCase() !== 'post') return;
    f.addEventListener('submit', () => {
      if (typeof f.checkValidity === 'function' && !f.checkValidity()) return;
      const btn = f.querySelector('button[type=submit], button:not([type])');
      if (btn && !btn.disabled) {
        btn.dataset.html = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' +
          (btn.dataset.loadingText || 'Please wait…');
      }
    });
  });
})();
