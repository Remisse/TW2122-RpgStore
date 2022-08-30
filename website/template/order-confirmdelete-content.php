<div class="text-center">
    <h2 class="py-3">Conferma eliminazione ordine</h2>
    <div class="border rounded bg-body p-3">
        <p class="alert alert-warning fs-5 fw-semibold">Vuoi davvero annullare l'ordine? Questa azione è irreversibile.</p>
        <a href="../ordermanagement.php?status=cancelled&id=<?php echo $_GET["id"] ?>" class="btn btn-danger">Annulla</a>
    </div>
</div>
