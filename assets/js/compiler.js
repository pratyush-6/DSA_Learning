/* Built-in compiler UI: editor, run, submit against test cases. */
(function () {
  'use strict';
  const cfg = window.__compiler;
  const ta = document.getElementById('code-editor');
  if (!cfg || !ta || !window.CodeMirror) return;

  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const api = (p) => `${window.BASE_URL}/api/${p}`;
  const esc = (s) => (s || '').replace(/[&<>]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[c]));
  const toast = (m, t) => (window.dsaToast ? window.dsaToast(m, t) : null);

  const modeFor = (lang) => ({
    python: 'python', cpp: 'text/x-c++src', java: 'text/x-java', php: 'text/x-csrc',
  }[lang] || 'text/plain');
  const isDark = () => document.documentElement.getAttribute('data-bs-theme') === 'dark';

  const buffers = Object.assign({}, cfg.starters);
  let lang = cfg.activeLang;

  const cm = window.CodeMirror.fromTextArea(ta, {
    lineNumbers: true,
    indentUnit: 4,
    tabSize: 4,
    mode: modeFor(lang),
    theme: isDark() ? 'material-darker' : 'default',
  });
  cm.setSize('100%', 320);
  cm.setValue(buffers[lang] || '');
  setTimeout(() => cm.refresh(), 50);

  // Re-theme the editor when the site theme changes.
  document.getElementById('theme-toggle')?.addEventListener('click', () => {
    setTimeout(() => cm.setOption('theme', isDark() ? 'material-darker' : 'default'), 30);
  });

  // Language tabs.
  document.querySelectorAll('#lang-tabs [data-lang]').forEach((btn) => {
    btn.addEventListener('click', () => {
      buffers[lang] = cm.getValue();            // save current
      lang = btn.dataset.lang;
      document.querySelectorAll('#lang-tabs [data-lang]').forEach((b) => b.classList.toggle('active', b === btn));
      cm.setOption('mode', modeFor(lang));
      cm.setValue(buffers[lang] || '');
      cm.refresh();
    });
  });

  document.getElementById('reset-btn')?.addEventListener('click', () => {
    cm.setValue(cfg.starters[lang] || '');
    cm.focus();
  });

  async function postJson(url, data) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf },
      body: JSON.stringify(data),
    });
    let j = null; try { j = await res.json(); } catch (e) {}
    if (!res.ok || !j) return { ok: false, error: (j && j.error) || ('http_' + res.status) };
    return j;
  }
  function busy(btn, on, label) {
    btn.disabled = on;
    if (on) { btn.dataset.html = btn.innerHTML; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + label; }
    else if (btn.dataset.html) { btn.innerHTML = btn.dataset.html; }
  }

  // ---- Run ----------------------------------------------------------------
  const runBtn = document.getElementById('run-btn');
  const out = document.getElementById('run-output');
  runBtn?.addEventListener('click', async () => {
    busy(runBtn, true, 'Running…');
    out.innerHTML = '<span class="text-muted">Running…</span>';
    const r = await postJson(api('run.php'), {
      language: lang, source: cm.getValue(), stdin: document.getElementById('custom-stdin').value,
    });
    busy(runBtn, false);
    if (!r.ok) { out.innerHTML = `<span class="text-danger">${esc(r.error || 'Error')}</span>`; return; }
    let html = '';
    if (r.compile_error) html += `<span class="text-danger">Compilation error:\n${esc(r.compile_error)}</span>\n`;
    if (r.stdout) html += esc(r.stdout);
    if (r.stderr) html += `<span class="text-warning">${esc(r.stderr)}</span>`;
    if (r.timed_out) html += `<span class="text-danger">\n[Timed out]</span>`;
    if (!html.trim()) html = '<span class="text-muted">(no output)</span>';
    if (r.backend === 'remote') html += `<span class="text-muted small">\n— executed remotely (Piston)</span>`;
    out.innerHTML = html;
  });

  // ---- Submit -------------------------------------------------------------
  const submitBtn = document.getElementById('submit-btn');
  const resBox = document.getElementById('submit-results');
  submitBtn?.addEventListener('click', async () => {
    busy(submitBtn, true, 'Judging…');
    resBox.innerHTML = '<div class="skeleton sk-line" style="width:60%"></div><div class="skeleton sk-line" style="width:40%"></div>';
    const r = await postJson(api('submit_code.php'), { problem_id: cfg.problemId, language: lang, source: cm.getValue() });
    busy(submitBtn, false);
    if (!r.ok) { resBox.innerHTML = `<div class="alert alert-danger">${esc(r.error || 'Error')}</div>`; return; }
    renderResults(r);
  });

  function renderResults(r) {
    const pct = Math.round((r.passed / r.total) * 100);
    let html = '';
    if (r.all_passed) {
      html += `<div class="alert alert-success d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill fs-4"></i>
        <div><strong>All ${r.total} test cases passed!</strong> Exercise completed ✓</div></div>`;
    } else {
      html += `<div class="alert alert-warning"><strong>${r.passed} / ${r.total} test cases passed</strong> (${pct}%). Keep going!</div>`;
    }
    html += '<div class="d-flex flex-wrap gap-2 mb-2">';
    r.results.forEach((c) => {
      html += `<span class="badge ${c.passed ? 'bg-success' : 'bg-danger'}">#${c.index} ${c.passed ? '✓' : '✗'}${c.sample ? '' : ' (hidden)'}</span>`;
    });
    html += '</div>';
    r.results.forEach((c) => {
      if (c.compile_error) html += `<pre class="surface-2 p-2 rounded small text-danger">Compilation error:\n${esc(c.compile_error)}</pre>`;
      else if (!c.passed && c.sample) {
        html += `<div class="card mb-2"><div class="card-body py-2 small">
          <div class="fw-semibold text-danger mb-1">Test #${c.index} failed${c.timed_out ? ' (timed out)' : ''}</div>
          <div class="row g-2">
            <div class="col-md-4"><div class="text-muted">Input</div><pre class="surface-2 p-2 rounded mb-0">${esc(c.input)}</pre></div>
            <div class="col-md-4"><div class="text-muted">Expected</div><pre class="surface-2 p-2 rounded mb-0">${esc(c.expected)}</pre></div>
            <div class="col-md-4"><div class="text-muted">Your output</div><pre class="surface-2 p-2 rounded mb-0">${esc(c.got)}</pre></div>
          </div></div></div>`;
      }
    });
    resBox.innerHTML = html;

    if (r.solved) {
      const badge = document.getElementById('solved-badge');
      if (badge) { badge.className = 'badge ms-auto bg-success'; badge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Completed'; }
      toast('Exercise completed — all tests passed! ✓', 'ok');
    }
  }
})();
