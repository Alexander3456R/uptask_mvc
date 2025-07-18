<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <div class="contenedor-nueva-tarea">
        <button
            type="button"
            class="agregar-tarea"
            id="agregar-tarea"
        >&#43; Nueva Tarea</button>

        <form id="form-eliminar-proyecto" method="POST" action="/eliminar-proyecto">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
        <button type="submit" class="eliminar-proyecto">&#215; Eliminar Proyecto</button>
</form>
    </div>
    <div id="filtros" class="filtros">
        <div class="filtros-inputs">
            <h2>Filtros</h2>
            <div class="campo">
                <label for="todas">Todas</label>
                <input 
                    type="radio"
                    id="todas"
                    name="filtro"
                    value=""
                    checked
                
                />
            </div>


            <div class="campo">
                <label for="completadas">Completadas</label>
                <input 
                    type="radio"
                    id="completadas"
                    name="filtro"
                    value="1"
                />
            </div>


            <div class="campo">
                <label for="pendientes">Pendientes</label>
                <input 
                    type="radio"
                    id="pendientes"
                    name="filtro"
                    value="0"
                />
            </div>
        </div>
    </div>
    
     <ul id="listado-tareas" class="listado-tareas"></ul>
     
</div>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>

<?php
    $script .= '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="build/js/tareas.js"></script>
        <script>
        document.querySelector("#form-eliminar-proyecto").addEventListener("submit", function(e) {
            e.preventDefault();
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Esta acción eliminará el proyecto y no se puede deshacer!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e74c3c",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
        </script>
    ';
?>