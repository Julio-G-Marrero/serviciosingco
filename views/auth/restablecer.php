<div class="contenedor restablecer">
    <?php include_once __DIR__  .'/../templates/nombre-sitio.php' ?>
    <div class="contenedor-sm">

    <?php include_once __DIR__  .'/../templates/alertas.php' ?>

        <?php if($mostrar): ?>
        <p class="descripcion-pagina">Coloca tu nuevo Password</p>
        <form method="POST" class="formulario">
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu Password" name="password">
            </div>
            <input type="submit" class="boton" value="Guardar Password">
        </form>
            <?php endif; ?>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Obtiene una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div><!-- .contenedor-sm-->
</div>