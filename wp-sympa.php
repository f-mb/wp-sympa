<?php
/* 
 * Plugin Name:	WP Sympa
 * Version:     0.1.6
 * Description:	Ajouter un formulaire d'inscription/désinscription à vos listes gérées par un serveur Sympa avec un simple [wpsympa s=sympa@serveur.xyz l=nomdelaliste].
 * Author: 		Florian Martin-Bariteau
 * Licence: 	GNU GPL v2
 * Text Domain:	wpsympa
 * Domain Path:	/languages/
 *
 * @package		WP Sympa
 * @since		0.1.1
 * @version		0.1.6, 2015-08-13
 * @author		Florian Martin-Bariteau 
 */

/*
**	Shortcode
*/

add_shortcode( 'wpsympa', 'wpsympa_form__callback' );

function wpsympa_form__callback( $params ){

	if(empty($params['s']))
		return '<p style="color: red;"><strong>' . __('Erreur de configuration', 'wpsympa') . ' [s=].</strong>' . __('Aucune intermédiaire Sympa précisé.', 'wpsympa') . '</p>';
	if(empty($params['l']))
		return '<p style="color: red;"><strong>' . __('Erreur de configuration', 'wpsympa') . ' [l=].</strong>' . __('Aucune liste précisée.', 'wpsympa') . '</strong></p>';

	if(empty($params['r'])) $params['r'] = 'subscribe';
	if(empty($params['u'])) $params['u'] = 'unsubscribe';

	$tpl_form = '<form method="post" id="wpsympa-form">

	<p><label for="wpsympa_firstname">' . __('Prénom', 'wpsympa') . '</label><br/>
	<input type="text" name="wpsympa_firstname"/></p>
	
	<p><label for="wpsympa_name">' . __('Nom', 'wpsympa') . '</label><br/>
	<input type="text" name="wpsympa_name"/></p>
	
	<p><label for="wpsympa_email">' . __('Courriel', 'wpsympa') . ' ('.__('obligatoire', 'wpsympa').')</label><br/>
	<input type="email" name="wpsympa_email"/></p>
	
	<p><label><input type="radio" name="wpsympa_action" value="' . $params['r'] . '" checked/>' . __('S\'inscrire à la liste de diffusion', 'wpsympa') . '</label><br/>
	<label><input type="radio" name="wpsympa_action" value="' . $params['u'] . '"/>' . __('Se désinscrire de la liste de diffusion', 'wpsympa') . '</label></p>

	<p><input type="submit" value="' . __('Continuer', 'wpsympa') . ' › "/></p>

	</form>';

	$msg = '<div id="wpsympa">';

	if( isset($_POST['wpsympa_action']) ) {
	
		if( !empty($_POST['wpsympa_email']) ) {
	
			$email = filter_input( INPUT_POST, 'wpsympa_email', FILTER_SANITIZE_EMAIL);
			$name = filter_input( INPUT_POST, 'wpsympa_firstname', FILTER_SANITIZE_STRING) . ' ' . filter_input( INPUT_POST, 'wpsympa_name', FILTER_SANITIZE_STRING);
			$action = filter_input( INPUT_POST, 'wpsympa_action', FILTER_SANITIZE_STRING);
	
			if($action == $params['r']) { $command = $params['r'] . ' ' . $params['l'] . ' ' . $name; }
			if($action == $params['u']) { $command = $params['u'] . ' ' . $params['l'] . ' ' . $email; }
	
			if(isset($command)){ $msg = $msg . wpsympa_mailapi( $params['s'], $params['l'], $email, $name, $command); }
			else { $msg = $msg . '<p style="color: red;"><strong>' . __('Une erreur est apparue.', 'wpsympa') . ' [E66]</strong></p>'; }
		
		} else {
			$msg = $msg . '<p style="color: red;"><strong>' . __('Vous devez renseigner une adresse courriel !', 'wpsympa') . '</strong></p>';
		}
	}
		
	return $msg . $tpl_form . '</div>';

}

/*
**	Sympa Mail API
*/

function wpsympa_mailapi( $sympahost, $list, $email, $name, $command) {
	
	if($name != ' ') { $hname = $name; } else { $hname = $email; }
	
	$headers = 'From: ' . $hname . ' <' . $email . '>' . "\r\n";
		
	$emailsent = wp_mail( $sympahost, $command, '--WP SYMPA--', $headers );
	
	if( $emailsent ) {
	
		return '<p><strong>' . sprintf( __('%1$s, votre demande a été communiquée au serveur de liste avec succès. Vous devriez recevoir un courriel de confirmation à %2$s sous peu (vérifiez vos pourriels).', 'wpsympa'), $hname, $email) . '</strong></p>';
		
	} else {
	
		return '<p><strong style="color: red;">' . __('Une erreur est apparue.', 'wpsympa') . ' [E95]</strong> ' . __('Le système semble incapable de communiquer avec le serveur de liste. Vous pouvez procéder manuellement, en cliquant sur le lien suivant et en envoyant le message tel quel depuis votre adresse de messagerie :', 'wpsympa') . ' <a href="mailto:' . $sympahost . '?subject=' . $command . '">[ ' . $list . ' ]</a></p>';

	}


}

/*
**	i18n strings
*/

add_action( 'plugins_loaded', 'wpsympa_i18n' );
function wpsympa_i18n() {
	load_plugin_textdomain( 'wpsympa', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

$wpsympadescriptionfori18n = __('Ajouter un formulaire d\'inscription/désinscription à vos listes gérées par un serveur Sympa avec un simple [wpsympa s=sympa@serveur.xyz l=nomdelaliste].', 'wpsympa');

?>