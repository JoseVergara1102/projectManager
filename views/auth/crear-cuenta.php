<div class="container mb-2">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 col-lg-4">
            <div class="card my-5">

                <div class="card-header">
                    <h3>Registro</h3>
                    <div class="d-flex justify-content-end social_icon">
                        <span><i class="fab fa-facebook-square"></i></span>
                        <span><i class="fab fa-google-plus-square"></i></span>
                        <span><i class="fab fa-twitter-square"></i></span>
                    </div>
                </div>
                <div class="card-body">

                    <form class="formulario" method="POST" action="/crear-cuenta">

                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                            </div>
                            <input type="text" id="nombre" name="nombre" placeholder="Tu Nombre" value="<?php echo s($usuario->nombre); ?>" />
                        </div>

                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                            </div>
                            <input type="text" id="apellido" name="apellido" placeholder="Tu Apellido" value="<?php echo s($usuario->apellido); ?>" />
                        </div>

                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                            </div>
                            <select id="sexo" name="sexo">
                                <option value="" disabled <?php echo !isset($form_data['sexo']) ? 'selected' : ''; ?>>Sexo...</option>
                                <option value="Masculino" <?php echo isset($form_data['sexo']) && $form_data['sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                <option value="Femenino" <?php echo isset($form_data['sexo']) && $form_data['sexo'] == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                            </select>
                        </div>

                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                        <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                            </div>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo isset($form_data['fecha_nacimiento']) ? s($form_data['fecha_nacimiento']) : ''; ?>">
                        </div>

                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                            </div>
                            <input type="email" id="email" name="email" placeholder="Tu E-mail" value="<?php echo s($usuario->email); ?>" />
                        </div>

                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                            </div>
                            <input type="tel" id="telefono" name="telefono" placeholder="Tu Teléfono" value="<?php echo s($usuario->telefono); ?>" />
                        </div>

                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                            </div>
                            <input type="text" id="profesion" name="profesion" placeholder="Tu Profesión" value="<?php echo s($usuario->profesion); ?>" />
                        </div>


                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info"></i></span>
                            </div>
                            <input type="text" id="direccion" name="direccion" placeholder="Tu Dirección" value="<?php echo s($usuario->direccion); ?>" />
                        </div>


                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" id="password" name="password" placeholder="Tu Password" />
                        </div>




                        <div class="form-group d-flex justify-content-center">
                            <input type="submit" value="Crear" class="btn float-right login_btn">
                        </div>

                    </form>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-center links">
                        ¿Ya tienes una cuenta?<a href="/login">Inicia Sesión</a>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="/olvide">Olvidaste tu contraseña?</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>