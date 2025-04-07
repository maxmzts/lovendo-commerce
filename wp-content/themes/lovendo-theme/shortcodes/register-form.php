<?php
/**
 * Archivo: formulario-registro.php
 * Descripción: Shortcode para el formulario de registro personalizado. Más seguro para evitar usar el panel de wordpress.
 *              Se debe introducir:
 *                  - Nombre de usuario
 *                  - Email
 *                  - Contraseña
 *                  - Repetir contraseña
 *              El email, el nombre de usuario son comprobadas para evitar duplicidades o errores con la base de datos. 
 *              También se comprueba que las contraseñas coinciden. Se hace uso del nonce para validar la petición del 
 *              formulario.
 * Uso: [formulario_login]
 */

// Evitar acceso directo al archivo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcode para el formulario de registro personalizado
 * Uso: [formulario_registro]
 */
function shortcode_formulario_registro() {
    // Si el usuario ya está logueado, mostrar mensaje y link a Mi Cuenta
    if (is_user_logged_in()) {
        return '<p>' . __('Ya estás registrado y has iniciado sesión.', 'mi-tema') . ' <a href="' . esc_url(wc_get_page_permalink('my_account')) . '">' . __('Ir a Mi Cuenta', 'mi-tema') . '</a></p>';
    }
    
    $output = '';
    $errors = array();
    
    // Procesar el formulario cuando se envía
    if (isset($_POST['registro_usuario']) && wp_verify_nonce($_POST['registro_nonce'], 'registro_usuario')) {
        
        $username = sanitize_user($_POST['registro_usuario']);
        $email = sanitize_email($_POST['registro_email']);
        $password = $_POST['registro_password'];
        $password_confirm = $_POST['registro_password_confirm'];
        
        // Validaciones
        if (empty($username)) {
            $errors[] = __('Por favor, introduce un nombre de usuario.', 'mi-tema');
        }
        
        if (empty($email) || !is_email($email)) {
            $errors[] = __('Por favor, introduce un email válido.', 'mi-tema');
        }
        
        if (email_exists($email)) {
            $errors[] = __('Este email ya está registrado. Por favor, utiliza otro o inicia sesión.', 'mi-tema');
        }
        
        if (username_exists($username)) {
            $errors[] = __('Este nombre de usuario ya existe. Por favor, elige otro.', 'mi-tema');
        }
        
        if (empty($password)) {
            $errors[] = __('Por favor, introduce una contraseña.', 'mi-tema');
        }
        
        if ($password != $password_confirm) {
            $errors[] = __('Las contraseñas no coinciden.', 'mi-tema');
        }
        
        // Si no hay errores, crear el usuario
        if (empty($errors)) {
            $user_id = wp_create_user($username, $password, $email);
            
            if (!is_wp_error($user_id)) {
                // Establecer rol de suscriptor
                $user = new WP_User($user_id);
                $user->set_role('subscriber');
                
                // Iniciar sesión automáticamente
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                
                // Mensaje de éxito y redirección
                wc_add_notice(__('¡Registro completado con éxito!', 'mi-tema'), 'success');
                wp_redirect(wc_get_page_permalink('myaccount'));
                exit;
            } else {
                $errors[] = $user_id->get_error_message();
            }
        }
        
        // Mostrar errores si los hay
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $output .= '<p class="error-mensaje">' . $error . '</p>';
            }
        }
    }
    
    // Construir formulario de registro
    $output .= '
    <div class="formulario-registro-personalizado">
        <h2>' . __('Crear una cuenta', 'mi-tema') . '</h2>
        <form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">
            <p class="form-row">
                <label for="registro_usuario">' . __('Nombre de usuario', 'mi-tema') . ' <span class="required">*</span></label>
                <input type="text" class="input-text" name="registro_usuario" id="registro_usuario" value="' . (isset($_POST['registro_usuario']) ? esc_attr($_POST['registro_usuario']) : '') . '" />
            </p>
            
            <p class="form-row">
                <label for="registro_email">' . __('Email', 'mi-tema') . ' <span class="required">*</span></label>
                <input type="email" class="input-text" name="registro_email" id="registro_email" value="' . (isset($_POST['registro_email']) ? esc_attr($_POST['registro_email']) : '') . '" />
            </p>
            
            <p class="form-row">
                <label for="registro_password">' . __('Contraseña', 'mi-tema') . ' <span class="required">*</span></label>
                <input type="password" class="input-text" name="registro_password" id="registro_password" />
            </p>
            
            <p class="form-row">
                <label for="registro_password_confirm">' . __('Confirmar contraseña', 'mi-tema') . ' <span class="required">*</span></label>
                <input type="password" class="input-text" name="registro_password_confirm" id="registro_password_confirm" />
            </p>
            
            <p class="form-row">
                ' . wp_nonce_field('registro_usuario', 'registro_nonce', true, false) . '
                <button type="submit" class="button" name="registro_enviar" value="' . esc_attr__('Registrarse', 'mi-tema') . '">' . __('Registrarse', 'mi-tema') . '</button>
            </p>
            
            <p class="woocommerce-info">
                ' . __('¿Ya tienes una cuenta?', 'mi-tema') . ' <a href="' . esc_url(wc_get_page_permalink('myaccount')) . '">' . __('Inicia sesión', 'mi-tema') . '</a>
            </p>
        </form>
    </div>';
    
    return $output;
}
add_shortcode('formulario_registro', 'shortcode_formulario_registro');
