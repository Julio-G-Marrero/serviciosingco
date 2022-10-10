<?php include_once __DIR__ . '/header.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php';?>

    <a href="/perfil" class="enlace">Volver al Perfil</a>

    <form class="formulario" method="POST" action="/cambiar-password">
        <div class="campo">
            <label for="password_actual">Password Actual</label>
            <input type="password" name="passwordActual" id="password_actual" placeholder="Tu password actual">
        </div>
        <div class="campo">
            <label for="password_nuevo">Password Nuevo</label>
            <input type="password" name="passwordNuevo" id="password_nuevo" placeholder="Tu password nuevo">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
