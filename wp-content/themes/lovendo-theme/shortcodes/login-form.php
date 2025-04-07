<?php
/**
 * Archivo: formulario-registro.php
 * Descripción: Shortcode para el formulario de login personalizado. Más seguro para evitar usar el panel de wordpress.
 *              Se debe introducir:
 *                  - Email
 *                  - Contraseña
 *                  - Check para recordar
 *              Se hace uso del nonce para validar la petición del formulario.
 * Uso: [formulario_login]
 */
function shortcode_formulario_login() {
    // Si el usuario ya está logueado, mostrar mensaje y link a Mi Cuenta
    if (is_user_logged_in()) {
        return '<p>' . __('Ya has iniciado sesión.', 'mi-tema') . ' <a href="' . esc_url(wc_get_page_permalink('myaccount')) . '">' . __('Ir a Mi Cuenta', 'mi-tema') . '</a></p>';
    }
    
    $output = '';
    
    // Procesar el formulario cuando se envía
    if (isset($_POST['login_usuario']) && wp_verify_nonce($_POST['login_nonce'], 'login_usuario')) {
        $creds = array(
            'user_login'    => $_POST['login_usuario'],
            'user_password' => $_POST['login_password'],
            'remember'      => isset($_POST['login_recordar'])
        );
        
        $user = wp_signon($creds, false);
        
        if (is_wp_error($user)) {
            $output .= '<p class="error-mensaje">' . $user->get_error_message() . '</p>';
        } else {
            // Inicio de sesión exitoso
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }
    }
    
    // Construir formulario de inicio de sesión
    $output .= '
    <div class="formulario-login-personalizado">
        <h2>' . __('Iniciar sesión', 'mi-tema') . '</h2>
        <form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">
            <p class="form-row">
                <label for="login_usuario">' . __('Nombre de usuario o email', 'mi-tema') . ' <span class="required">*</span></label>
                <input type="text" class="input-text" name="login_usuario" id="login_usuario" autocomplete="username" value="' . (isset($_POST['login_usuario']) ? esc_attr($_POST['login_usuario']) : '') . '" />
            </p>
            
            <p class="form-row">
                <label for="login_password">' . __('Contraseña', 'mi-tema') . ' <span class="required">*</span></label>
                <input class="input-text" type="password" name="login_password" id="login_password" autocomplete="current-password" />
            </p>
            
            <p class="form-row">
                <label for="login_recordar" class="inline">
                    <input class="woocommerce-form__input" name="login_recordar" type="checkbox" id="login_recordar" value="forever" /> 
                    <span>' . __('Recordarme', 'mi-tema') . '</span>
                </label>
            </p>
            
            <p class="form-row">
                ' . wp_nonce_field('login_usuario', 'login_nonce', true, false) . '
                <button type="submit" class="button" name="login_enviar" value="' . esc_attr__('Iniciar sesión', 'mi-tema') . '">' . __('Iniciar sesión', 'mi-tema') . '</button>
            </p>
            
            <p class="lost_password">
                <a href="' . esc_url(wp_lostpassword_url()) . '">' . __('¿Olvidaste tu contraseña?', 'mi-tema') . '</a>
            </p>
            
            <p class="woocommerce-info">
                ' . __('¿No tienes una cuenta?', 'mi-tema') . ' <a href="' . esc_url(wc_get_page_permalink('myaccount')) . '?action=register">' . __('Regístrate', 'mi-tema') . '</a>
            </p>
        </form>
    </div>';
    
    return $output;
}
add_shortcode('formulario_login', 'shortcode_formulario_login');

?>