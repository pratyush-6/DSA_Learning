  </div><!-- /.container-xl -->
</main>

<footer class="border-top py-4 mt-5 bg-light">
  <div class="container-xl text-center text-muted small">
    <p class="mb-1"><strong><?= e(APP_NAME) ?></strong> &mdash; Learn DSA from beginner to advanced.</p>
    <p class="mb-0">Multi-language &middot; Real-world examples &middot; Interview prep &middot; Progress tracking</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-core.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<script>window.BASE_URL = <?= json_encode(BASE_URL) ?>;</script>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
