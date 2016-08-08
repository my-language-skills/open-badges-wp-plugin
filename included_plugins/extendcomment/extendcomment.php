<?php
/*
Code coming from 'Extend Comment' (v1.0) by Specky Geek
Plugin URI: http://smartwebworker.com
A plug-in to add additional fields in the comment form.
*/


// Add fields after default fields above the comment box, always visible

add_action( 'comment_form_logged_in_after', 'additional_fields' );
add_action( 'comment_form_after_fields', 'additional_fields' );

function additional_fields () {
    if(get_post_type() == 'class') {//////////////////////////////////////////////////////////////////////////////////////////////
	echo '<p class="comment-form-title">'.
	'<label for="title">' . __( 'Comment Title' ) . '</label>'.
	'<input id="title" name="title" type="text" size="30"  tabindex="5" /></p>';

	echo '<p class="comment-form-rating">'.
	'<label for="rating">'. __('Rating') . '<span class="required">*</span></label>
	<span class="commentratingbox">';
	
	for( $i=1; $i <= 5; $i++ )
            echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"/>'. $i .'</span>';

	echo'</span></p>';
    }/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

// Save the comment meta data along with comment

add_action( 'comment_post', 'save_comment_meta_data' );
function save_comment_meta_data( $comment_id ) {
	if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') )
            $title = wp_filter_nohtml_kses($_POST['title']);
	add_comment_meta( $comment_id, 'title', $title );

	if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') )
            $rating = wp_filter_nohtml_kses($_POST['rating']);
	add_comment_meta( $comment_id, 'rating', $rating );
}


// Add the filter to check if the comment meta data has been filled or not

add_filter( 'preprocess_comment', 'verify_comment_meta_data' );
function verify_comment_meta_data( $commentdata ) {
	if ( ! isset( $_POST['rating'] ) )
            wp_die( __( 'Error: You did not add your rating. Hit the BACK button of your Web browser and resubmit your comment with rating.' ) );
	return $commentdata;
}

//Add an edit option in comment edit screen  

add_action( 'add_meta_boxes_comment', 'extend_comment_add_meta_box' );
function extend_comment_add_meta_box() {
    add_meta_box( 'title', __( 'Comment Metadata - Extend Comment' ), 'extend_comment_meta_box', 'comment', 'normal', 'high' );
}
 
function extend_comment_meta_box ( $comment ) {
    $title = get_comment_meta( $comment->comment_ID, 'title', true );
    $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
    wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
    ?>
    <p>
        <label for="title"><?php _e( 'Comment Title' ); ?></label>
        <input type="text" name="title" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
    </p>
    <p>
        <label for="rating"><?php _e( 'Rating: ' ); ?></label>
            <span class="commentratingbox">
            <?php for( $i=1; $i <= 5; $i++ ) {
                    echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"';
                    if ( $rating == $i ) echo ' checked="checked"';
                    echo ' />'. $i .' </span>'; 
                    }
            ?>
            </span>
    </p>
    <?php
}

// Update comment meta data from comment edit screen 

add_action( 'edit_comment', 'extend_comment_edit_metafields' );
function extend_comment_edit_metafields( $comment_id ) {
    if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;
        if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') ):
            $title = wp_filter_nohtml_kses($_POST['title']);
            update_comment_meta( $comment_id, 'title', $title );
	else :
            delete_comment_meta( $comment_id, 'title');
	endif;

	if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') ):
            $rating = wp_filter_nohtml_kses($_POST['rating']);
            update_comment_meta( $comment_id, 'rating', $rating );
	else :
            delete_comment_meta( $comment_id, 'rating');
	endif;
}

// Add the comment meta (saved earlier) to the comment text 
// You can also output the comment meta values directly in comments template  

add_filter( 'comment_text', 'modify_comment');
function modify_comment( $text ){

	$plugin_url_path = WP_PLUGIN_URL;

	if( $commenttitle = get_comment_meta( get_comment_ID(), 'title', true ) ) {
		$commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';
		$text = $commenttitle . $text;
	} 

	if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
		$commentrating = '<p class="comment-rating"><img src="'. $plugin_url_path .
		'/ExtendComment/images/'. $commentrating . 'star.gif"/><br/>Rating: <strong>'. $commentrating .' / 5</strong></p>';
		$text = $text . $commentrating;
		return $text;		
	} else {
		return $text;		
	}	 
}