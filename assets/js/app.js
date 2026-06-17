/* DSA Learning Platform — front-end interactions */
(function () {
  'use strict';

  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const api = (path) => `${window.BASE_URL}/api/${path}`;

  async function postJson(url, data) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
      body: JSON.stringify(data),
    });
    return res.json();
  }

  // --- Mark topic complete -------------------------------------------------
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
        }
      } finally {
        completeBtn.disabled = false;
      }
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
    if (badge && typeof chapterPercent === 'number') {
      badge.textContent = chapterPercent + '%';
    }
  }

  // --- Bookmark toggle -----------------------------------------------------
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
        }
      } finally {
        bookmarkBtn.disabled = false;
      }
    });
  }

  // --- Notes auto-save -----------------------------------------------------
  const noteArea = document.getElementById('note-text');
  const noteStatus = document.getElementById('note-status');
  if (noteArea) {
    let timer = null;
    noteArea.addEventListener('input', () => {
      clearTimeout(timer);
      if (noteStatus) noteStatus.textContent = 'Saving…';
      timer = setTimeout(async () => {
        const r = await postJson(api('note.php'), {
          topic_id: parseInt(noteArea.dataset.topicId, 10),
          note: noteArea.value,
        });
        if (noteStatus) noteStatus.textContent = r.ok ? 'Saved ✓' : 'Error saving';
      }, 700);
    });
  }

  // --- Language tab sync (remember preferred language across the page) -----
  document.querySelectorAll('[data-lang-tab]').forEach((tab) => {
    tab.addEventListener('shown.bs.tab', () => {
      const lang = tab.dataset.langTab;
      try { localStorage.setItem('dsa_lang', lang); } catch (e) {}
      // Sync any other tab groups on the page to the same language.
      document.querySelectorAll(`[data-lang-tab="${lang}"]`).forEach((other) => {
        if (other !== tab && window.bootstrap) {
          bootstrap.Tab.getOrCreateInstance(other).show();
        }
      });
    });
  });

  // --- Quiz submission -----------------------------------------------------
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
      if (!r.ok) return;
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
})();
