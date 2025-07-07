<div class="contenedor">
    <h1>UpTask</h1>
    <p class="tagline">Crea y administra tus proyectos</p>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <form action="/" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    placeholder="Tu e-mail"
                    name="email"
                />
            </div>


            <div class="campo">
                <label for="email">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    placeholder="Tu contraseña"
                    name="password"
                />
            </div>
        <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/crear">Aún no tienes una cuenta? Crear una!</a>
            <a href="/olvide">Olvidé Contraseña</a>
        </div>
  </div>    <!-- contenedor sm -->
</div>