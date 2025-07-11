<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <form class="formulario" method="POST" action="/crear-proyecto">
        <?php include_once __DIR__ . '/formulario-proyecto.php' ?>
        <input type="submit" value="Crear Proyecto">
    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>

<?php if(isset($proyectoCreado) && $proyectoCreado): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
  title: "¡Proyecto creado correctamente!",
  icon: "success",
  confirmButtonText: "Ir al proyecto"
}).then(() => {
  window.location.href = "/proyecto?id=<?php echo $urlProyecto; ?>";
});
</script>
<?php endif; ?>