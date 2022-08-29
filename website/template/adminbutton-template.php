<section class="border rounded text-center my-3">
    <h3 class="my-2">Gestione articolo</h3>
    <ul class="m-0 p-0">
        <div class="container-fluid py-2 d-flex flex-row justify-content-evenly">
            <li class="list-group-item px-1 flex-fill">
                <a href="../itemmanagement.php?action=update&id=<?php echo $template_params["itemid"] ?>" class="btn btn-warning w-100">Modifica</a>
            </li>
            <li class="list-group-item px-1 flex-fill">
                <a href="../itemmanagement.php?action=delete&id=<?php echo $template_params["itemid"] ?>" class="btn btn-danger w-100">Elimina</a>
            </li>
        </div>
    </ul>
</section>