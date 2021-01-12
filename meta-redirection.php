<?php
/**
Plugin Name: Content Redirection
Version: 0.1
Author: Javier Casares
Author URI: https://www.javiercasares.com/
License: GPLv2 or later
**/
defined('ABSPATH') or die('Bye bye!');

function cr_metabox_redir_head()
{
	global $post;
  $redir = get_post_meta( $post->ID, 'redir_cr', true );

  if ( $redir )
  {
    wp_redirect( $redir, 301 );
  }
}
add_action( 'wp', 'cr_metabox_redir_head', 100 );


function cr_metabox_redir() {
	add_meta_box( 'cr', 'redirection', 'cr_metabox_redir_show', 'page', 'normal', 'low' );
}
add_action( 'add_meta_boxes', 'cr_metabox_redir' );

function cr_metabox_redir_show( $post ) {
	wp_nonce_field( 'redir_cr_metabox_nonce', 'redir_cr_nonce' );
	$redir = get_post_meta( $post->ID, 'redir_cr', true );

	if(!$redir) $redir = null;

?>
	<p><label for="cr_metabox_redir">New URL: </label>
    <input type="url" name='cr_metabox_redir' id='cr_metabox_redir' value="<?php echo $redir; ?>">
	</p>
<?php
}

function cr_metabox_redir_save( $post_id ) {

  if( !isset( $_POST['redir_cr_nonce'] ) || !wp_verify_nonce( $_POST['redir_cr_nonce'], 'redir_cr_metabox_nonce') ) 
    return;

	if ( !current_user_can( 'edit_post', $post_id ))
		return;
	
	if ( isset($_POST['cr_metabox_redir']) ) {        
		update_post_meta($post_id, 'redir_cr', esc_url_raw($_POST['cr_metabox_redir']));      
	}
	
}
add_action('save_post', 'cr_metabox_redir_save');
?>