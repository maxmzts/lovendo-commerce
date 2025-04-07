<?php


function shortcode_botones_usuario() {
    ob_start();
    
    if (is_user_logged_in()) {
        // Obtener información del usuario actual
        $usuario_actual = wp_get_current_user();
        $nombre_usuario = $usuario_actual->display_name;
        
        // HTML para usuario logueado
        ?>
        <div class="boton-usuario-logueado">
            <a href="<?php echo site_url('/my-account'); ?>" class="perfil-usuario">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icono-usuario">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span class="nombre-usuario"><?php echo esc_html($nombre_usuario); ?></span>
            </a>
        </div>
        <?php
    } else {
        // HTML para usuario no logueado
        ?>
        <div class="botones-usuario-no-logueado">
            <a href="<?php echo site_url('/login'); ?>" class="boton boton-login"><!--
            --><?php _e('Iniciar sesión', 'botones-usuario'); ?>
            </a>
            
            <a href="<?php echo site_url('/registro'); ?>" class="boton boton-registro"><!--
            --><?php _e('Registrarse', 'botones-usuario'); ?>
            </a>
        </div>
        <?php
    }
    
    return ob_get_clean();
}
add_shortcode('botones_usuario', 'shortcode_botones_usuario');

?>