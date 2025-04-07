<?php
/**
 * Archivo: formulario-registro.php
 * Descripción: Shortcode para el formulario de login personalizado. Más seguro para evitar usar el panel de wordpress.
 *              Introduce:
 *                  - Título de la sugerencia (Asunto)
 *                  - Texto de la sugerencia
 *              Como el formulario es restringido a usuarios registrados toma el usuario actual para identificar quién
 *              ha realizado la sugerencia. Utiliza el tipo de dato 'sugerencia'.
 * Dependencias: 'data-type: sugerencia'
 * Uso: [formulario_sugerencias]              
 */

// Registrar shortcode para el formulario de sugerencias
function shortcode_formulario_sugerencias() {
    ob_start();
    
    // Comprobar si el usuario ha iniciado sesión
    if (is_user_logged_in()) {
        // Obtener datos del usuario actual
        $current_user = wp_get_current_user();
        $user_display_name = $current_user->display_name;
        $user_email = $current_user->user_email;
        
        // Procesar el formulario cuando se envía
        if (isset($_POST['submit_sugerencia'])) {
            // Validar campos (ya no necesitamos validar email)
            $nombre = sanitize_text_field($_POST['nombre']);
            $asunto = sanitize_text_field($_POST['asunto']);
            $descripcion = sanitize_textarea_field($_POST['descripcion']);
            
            // Validar que no hay campos vacíos
            $errores = array();
            if (empty($nombre)) $errores[] = 'El nombre es obligatorio.';
            if (empty($asunto)) $errores[] = 'El asunto es obligatorio.';
            if (empty($descripcion)) $errores[] = 'La descripción es obligatoria.';
            
            // Si no hay errores, guardar la sugerencia
            if (empty($errores)) {
                // Crear un post personalizado para la sugerencia
                $sugerencia_data = array(
                    'post_title'    => $asunto,
                    'post_content'  => $descripcion,
                    'post_status'   => 'private',
                    'post_type'     => 'sugerencia',
                    'post_author'   => $current_user->ID,  // Asociamos directamente al usuario actual
                    'meta_input'    => array(
                        'nombre_sugerencia' => $nombre
                        // Ya no guardamos el email porque usamos el del usuario
                    )
                );
                
                // Insertar la sugerencia en la base de datos
                $sugerencia_id = wp_insert_post($sugerencia_data);
                
                if (!is_wp_error($sugerencia_id)) {
                    // Opcional: Enviar email al administrador
                    $admin_email = get_option('admin_email');
                    $sitio = get_bloginfo('name');
                    $mensaje = "Nueva sugerencia recibida:\n\nUsuario: {$current_user->user_login} (ID: {$current_user->ID})\nNombre mostrado: $nombre\nEmail: $user_email\nAsunto: $asunto\nDescripción: $descripcion";
                    wp_mail($admin_email, "[$sitio] Nueva sugerencia: $asunto", $mensaje);
                    
                    echo '<div class="alert alert-success">¡Gracias! Tu sugerencia ha sido enviada correctamente.</div>';
                } else {
                    echo '<div class="alert alert-danger">Ha ocurrido un error al guardar tu sugerencia. Por favor, inténtalo de nuevo.</div>';
                }
            } else {
                // Mostrar errores
                echo '<div class="alert alert-danger"><strong>Por favor corrige los siguientes errores:</strong><ul>';
                foreach ($errores as $error) {
                    echo '<li>' . $error . '</li>';
                }
                echo '</ul></div>';
            }
        }
        
        // Mostrar información del usuario
        echo '<div class="user-info">';
        echo '<p><strong>Usuario:</strong> ' . esc_html($user_display_name) . ' (' . esc_html($user_email) . ')</p>';
        echo '</div>';
        
        ?>
        
        <div class="formulario-sugerencias-wrapper">
            <form method="post" action="" id="formulario-sugerencias">
                <div class="form-group">
                    <label for="nombre">Nombre a mostrar <span class="required">*</span></label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo esc_attr($user_display_name); ?>" required>
                    <small class="form-text text-muted">Puedes usar tu nombre completo u otro nombre para mostrar.</small>
                </div>
                
                <div class="form-group">
                    <label for="asunto">Asunto de la sugerencia <span class="required">*</span></label>
                    <input type="text" name="asunto" id="asunto" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción de la sugerencia <span class="required">*</span></label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="6" required></textarea>
                </div>
                
                <div class="form-group submit-group">
                    <input type="submit" name="submit_sugerencia" class="btn btn-primary" value="Enviar sugerencia">
                </div>
            </form>
        </div>
        
        <?php
    } else {
        // Mensaje para usuarios no registrados
        echo '<div class="alert alert-warning">Debes iniciar sesión para poder enviar sugerencias. ';
        echo '<a href="' . esc_url(wp_login_url(get_permalink())) . '">Iniciar sesión</a></div>';
    }
    
    return ob_get_clean();
}
add_shortcode('formulario_sugerencias', 'shortcode_formulario_sugerencias');


?>