<div class="contenedor reestablecer">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nueva contraseña</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if($mostrar) {  ?>
        <form class="formulario" method="POST">
    
            <div class="campo">
                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    placeholder="Tu contraseña"
                    name="password"
                />
            </div>
        <input type="submit" class="boton" value="Guardar Contraseña">
        </form>
       <?php } ?>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una!</a>
            <a href="/">¿Ya tienes cuenta? Iniciar Sesión!</a>
        </div>
  </div>    <!-- contenedor sm -->
</div>

<?php if(isset($sweetAlert) && $sweetAlert): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
  title: "¡Contraseña guardada correctamente!",
  icon: "success",
  draggable: true,
  confirmButtonText: "OK"
}).then(() => {
  window.location.href = "/";
});
</script>
<?php endif; ?>