<div class='contenedor olvide'>
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class='contenedor-sm'>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <p class='descripcion-pagina'>Recupera tu Accesso</p>
        <form class='formulario' method='POST' action='/olvide'>
            <div class='campo'>
                <label for='email'>Email</label>
                <input
                    type='email'
                    id='email'
                    placeholder='Tu Email'
                    name='email'
                    autocomplete='email'>
            </div>
            <input type='submit' class='boton' value='Enviar Instrucciones'>
            <div class='acciones'>
                <a href='/'>Ya tienes Cuenta? Inicia Sesion</a>
                <a href='/crear'>Aun no tienes una Cuenta? Crea una Cuenta</a>
            </div>
        </form>
    </div><!-- .contenedor-sm -->
</div>