<article class="border rounded bg-body my-3 p-3">
    <!-- Populated via JS. -->
</article>
<?php if (isset($template_params["admin"]) && $template_params["admin"]): ?>
    <aside class="border rounded bg-body text-center my-3">
        <h4 class="my-2">Azioni</h4>
        <div class="container-fluid py-2">
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <a href="../itemmanagement.php?action=update&id=<?php echo $template_params["itemid"] ?>" class="btn btn-warning w-100">Modifica articolo</a>
                </div>
                <div class="col-12 col-md-6">
                    <a href="../itemmanagement.php?action=delete&id=<?php echo $template_params["itemid"] ?>" class="btn btn-danger w-100">Elimina articolo</a>
                </div>
            </div>
        </div>
    </aside>
<?php endif; ?>
