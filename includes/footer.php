</main>
<footer class="text-center text-muted small py-3 border-top">
    Gerenciador Logístico &mdash; APO Fundamentos de Programação para Internet &mdash; UNIPAR EAD
</footer>
<script src="<?= base_url() ?>/assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>/assets/js/mascaras.js"></script>
<?php if (!empty($scripts)) foreach ($scripts as $s): ?>
<script src="<?= base_url() ?>/assets/js/<?= e($s) ?>"></script>
<?php endforeach; ?>
</body>
</html>
