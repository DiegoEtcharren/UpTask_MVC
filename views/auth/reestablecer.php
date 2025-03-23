<div class='contenedor reestablecer'>
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class='contenedor-sm'>
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <?php if ($mostrar) : ?>
            <p class='descripcion-pagina'>Coloca tu Nueva Password</p>
            <form class='formulario' method='POST'>
                <div class='campo'>
                    <label for='password'>Password</label>
                    <input
                        type='password'
                        id='password'
                        placeholder='Tu Password'
                        name='password'
                    >
                </div>
                <input type='submit' class='boton' value='Guardar Password'>
            </form>
        <?php endif; ?>
            <div class='acciones'>
                <a href='/crear'>Aun no tienes una Cuenta? Crea una Cuenta</a>
                <a href='/olvide'>Olvide mi Contrasena</a>
            </div>
    </div><!-- .contenedor-sm -->
</div>