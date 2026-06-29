  </div><!-- /.container-xl -->
</main>

<footer class="app-footer py-4 mt-5">
  <div class="container-xl">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 small">
      <div>
        <span class="fw-bold">DSA<span class="brand-mark">Learn</span></span>
        <span class="text-muted ms-2">Learn DSA from beginner to advanced.</span>
      </div>
      <div class="text-muted">Multi-language · Real-world examples · Interview prep · Group study</div>
    </div>
  </div>
</footer>

<div class="toast-stack" id="toast-stack" aria-live="polite" aria-atomic="true"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-core.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<script>window.BASE_URL = <?= json_encode(BASE_URL) ?>;</script>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
