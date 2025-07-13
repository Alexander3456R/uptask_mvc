<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<?php if(count($proyectos) === 0) { ?>
    <p class="no-proyectos">No Hay Proyectos Aún<a href="/crear-proyecto">, Comienza Creando Uno</a></p>
<?php } else { ?>
    <ul class="listado-proyectos">
        <?php foreach($proyectos as $proyecto) { ?>
            <li class="proyecto">
                <a href="/proyecto?id=<?php echo $proyecto->url; ?>">
                    <?php echo $proyecto->proyecto; ?>
                </a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>

<?php if(isset($_GET['eliminado']) && $_GET['eliminado'] == 1): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    title: "¡Proyecto eliminado!",
    text: "El proyecto se ha borrado exitosamente.",
    icon: "success",
    confirmButtonText: "OK"
}).then(() => {
    // Limpia el parámetro 'eliminado' de la URL sin recargar la página
    if(window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('eliminado');
        window.history.replaceState({}, document.title, url.pathname + url.search);
    }
});
</script>
<?php endif; ?>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>
