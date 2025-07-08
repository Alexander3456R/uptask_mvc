<div class="contenedor reestablecer">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nueva contraseña</p>

        <form action="/reestablecer" class="formulario" method="POST">
    
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

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una!</a>
            <a href="/olvide">Olvidé Contraseña</a>
        </div>
  </div>    <!-- contenedor sm -->
</div>