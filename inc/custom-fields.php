<?php




add_action('add_meta_boxes', 'custom_post_fields', 1);

function custom_post_fields() {
    add_meta_box( 'extra_fields', 'Дополнительные поля', 'extra_fields_box_func', 'post', 'normal', 'high'  );
}


function extra_fields_box_func( $post ){
    ?>
    <p>Заголовок
        <input
            type="text"
            name="extra[title]"
            value="<?php echo get_post_meta($post->ID, 'title', 1); ?>"
            style="width:100%"
        />
    </p>

    <p>Описание
        <textarea
            type="text"
            name="extra[description]"
            style="width:100%;height:50px;"><?php echo get_post_meta($post->ID, 'description', 1); ?>
        </textarea>
    </p>

    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
    <?php
}




add_action( 'save_post', 'custom_post_fields_update', 0 );

function custom_post_fields_update( $post_id ){

    if (
        empty( $_POST['extra'] )
        || ! wp_verify_nonce( $_POST['extra_fields_nonce'], __FILE__ )
        || wp_is_post_autosave( $post_id )
        || wp_is_post_revision( $post_id )
    )
        return false;

    $_POST['extra'] = array_map( 'sanitize_text_field', $_POST['extra'] );
    foreach( $_POST['extra'] as $key => $value ){
        if( empty($value) ){
            delete_post_meta( $post_id, $key );
            continue;
        }
        update_post_meta( $post_id, $key, $value );
    }
    return $post_id;
}