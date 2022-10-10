<?php include_once __DIR__ . '/header.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php';?>

    <a href="/cambiar-password" class="enlace">Cambiar Password</a>

    <form class="formulario" method="POST" action="/perfil">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" value="<?php echo $usuario->nombre;?>">
        </div>
        <div class="campo">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Tu email" value="<?php echo $usuario->email;?>">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
