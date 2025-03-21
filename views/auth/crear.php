<div class='contenedor crear'>
    <?php
    include_once __DIR__ . '/../templates/nombre-sitio.php';
    ?>

    <div class='contenedor-sm'>
        <p class='descripcion-pagina'>Crea tu Cuenta en UpTask</p>
        <?php
        include_once __DIR__ . '/../templates/alertas.php';
        ?>
        <form class='formulario' method='POST' action='/crear'>
            <div class='campo'>
                <label for='nombre'>Nombre</label>
                <input
                    type='text'
                    id='nombre'
                    placeholder='Tu Nombre'
                    name='nombre'
                    autocomplete='nombre'
                    value='<?php echo $usuario->nombre ?>'>
            </div>
            <div class='campo'>
                <label for='email'>Email</label>
                <input
                    type='email'
                    id='email'
                    placeholder='Tu Email'
                    name='email'
                    autocomplete='email'
                    value='<?php echo $usuario->email ?>'>
            </div>
            <div class='campo'>
                <label for='password'>Password</label>
                <input
                    type='password'
                    id='password'
                    placeholder='Tu Password'
                    name='password'>
            </div>
            <div class='campo'>
                <label for='password2'>Repetir Password</label>
                <input
                    type='password'
                    id='password2'
                    placeholder='Repite tu Password'
                    name='password2'>
            </div>
            <input type='submit' class='boton' value='Crear Cuenta'>
            <div class='acciones'>
                <a href='/'>Ya tienes Cuenta? Inicia Sesion</a>
                <a href='/olvide'>Olvide mi Contrasena</a>
            </div>
        </form>
    </div><!-- .contenedor-sm -->
</div>