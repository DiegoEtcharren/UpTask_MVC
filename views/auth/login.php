<div class='contenedor login'>
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class='contenedor-sm'>
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <p class='descripcion-pagina'>Iniciar Sesion</p>
        <form class='formulario' method='POST' action='/'>
            <div class='campo'>
                <label for='email'>Email</label>
                <input
                    type='email'
                    id='email'
                    placeholder='Tu Email'
                    name='email'
                    autocomplete='email'
                >
            </div>
            <div class='campo'>
                <label for='password'>Password</label>
                <input
                    type='password'
                    id='password'
                    placeholder='Tu Password'
                    name='password'
                >
            </div>
            <input type='submit' class='boton' value='Iniciar Sesion'>
            <div class='acciones'>
                <a href='/crear'>Aun no tienes una Cuenta? Crea una Cuenta</a>
                <a href='/olvide'>Olvide mi Contrasena</a>
            </div>
        </form>
    </div><!-- .contenedor-sm -->
</div>