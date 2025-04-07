<?php

// para que wordpress tenga en cuenta este 
function theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

// bucle para cargar todos los shortcodes desde la carpeta /shortcodes del tema hijo
foreach (glob(get_stylesheet_directory() . '/shortcodes/*.php') as $file) {
    include_once $file;
}


/**********************************************************************************
 * Registrar tipo de post personalizado para las sugerencias                      *
 **********************************************************************************/
 
function registrar_post_type_sugerencias() {
    $labels = array(
        'name'                  => 'Sugerencias',
        'singular_name'         => 'Sugerencia',
        'menu_name'             => 'Sugerencias',
        'name_admin_bar'        => 'Sugerencia',
        'archives'              => 'Archivo de sugerencias',
        'all_items'             => 'Todas las sugerencias',
        'add_new_item'          => 'Añadir nueva sugerencia',
        'add_new'               => 'Añadir nueva',
        'new_item'              => 'Nueva sugerencia',
        'edit_item'             => 'Editar sugerencia',
        'update_item'           => 'Actualizar sugerencia',
        'view_item'             => 'Ver sugerencia',
        'search_items'          => 'Buscar sugerencia',
    );
    
    $args = array(
        'label'                 => 'Sugerencia',
        'description'           => 'Sugerencias enviadas por los usuarios',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'author'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-feedback',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'capabilities' => array(
            'create_posts' => false, // Deshabilitar la creación desde el admin
        ),
        'map_meta_cap' => true,
    );
    
    register_post_type('sugerencia', $args);
}
add_action('init', 'registrar_post_type_sugerencias');


///// COMPROBAR


// Añadir metaboxes personalizados para los campos adicionales
function sugerencias_agregar_metaboxes() {
    add_meta_box(
        'sugerencias_contacto',
        'Información de contacto',
        'sugerencias_contacto_callback',
        'sugerencia',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'sugerencias_agregar_metaboxes');

// Callback para mostrar los campos de contacto
function sugerencias_contacto_callback($post) {
    wp_nonce_field('sugerencias_save_contacto', 'sugerencias_contacto_nonce');
    
    $nombre = get_post_meta($post->ID, 'nombre_sugerencia', true);
    $email = get_post_meta($post->ID, 'email_sugerencia', true);
    
    echo '<p><strong>Nombre:</strong> ' . esc_html($nombre) . '</p>';
    echo '<p><strong>Email:</strong> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></p>';
}



