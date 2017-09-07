<?php
/*
Plugin Name: Custom Template Dependencies
Plugin URI:  https://untitled-production.com/
Description: Create dependencies between your page templates and custom fields. Auto-creation of custom fields for different templates.
Version:     0.1.0
Author:      Sergey Khomenko
Author URI:  https://untitled-production.com/
*/



$ctd_plugin = new Custom_Template_Dependencies();

class Custom_Template_Dependencies {

	private $settings;

	function __construct(){
		$this->settings = $this->get_plugin_option();

		$this->run_action_queue();
	}

	private function get_plugin_option(){
		return get_option( 'ctd_plugin_options' );
	}

	function run_action_queue(){
		add_action( 'admin_init', array( $this, 'init_plugin_options' ) );
		add_action( 'admin_menu', array( $this, 'add_plugin_options_page' ) );

		add_filter( 'content_save_pre', array( $this, 'apply_rules_filter' ) );
	}

	function init_plugin_options(){
		register_setting( 'ctd_plugin_options', 'ctd_plugin_options' );
	}

	function add_plugin_options_page(){
		add_management_page(
			'Custom Template Dependencies',
			'Custom Template Dependencies',
			'install_plugins',
			'ctd_plugin_options',
			array( $this, 'plugin_options' )
		);
	}

	function plugin_options(){
		if( isset( $_POST['ctd_plugin_options'] ) ){
			$this->update_plugin_options( $_POST['ctd_plugin_options'] );
		}

		if ( ! isset( $_REQUEST['settings-updated'] ) ){
			$_REQUEST['settings-updated'] = false;
		}

		?>
		<div class="wrap">
			<h2>Custom Template Dependencies</h2>
			<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
				<div id="message" class="updated">
					<p><strong><?php _e( 'Настройки сохранены', 'WP-Unique' ); ?></strong></p>
				</div>
			<?php endif; ?>
		</div>
		<form method="POST">
			<?php if( count( $this->settings['rules'] ) ): ?>
				<div class="wrap">
					<h3>Current Rules:</h3>
				</div>
				<table class="wp-list-table widefat">
					<thead>
						<tr>
							<td width="5%"><input type="checkbox" title="Select All" /></td>
							<td width="35%">Rule name</td>
							<td width="20%">Template Name</td>
							<td width="20%">Custom field</td>
							<td width="20%">Default value</td>
						</tr>
					</thead>
					<tbody id="the-list">
						<?php foreach( $this->settings['rules'] as $key => $rule ) : ?>
							<tr>
								<td align="center"><input type="checkbox" name="ctd_plugin_rule_remove[]" value="<?php echo $key; ?>" title="Select All" /></td>
								<td><?php echo $rule['rule_name']; ?></td>
								<td><?php echo $rule['tpl_path']; ?></td>
								<td><?php echo $rule['cstm_field']; ?></td>
								<td><?php echo $rule['def_value']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<div class="wrap">
				<h3>Add New Rule:</h3>
			</div>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">Rule Name:</th>
						<td><input type="text" name="ctd_plugin_options[rule_name]" id="ctd_plugin_options[rule_name]" value="" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row">Template Name:</th>
						<td><input type="text" name="ctd_plugin_options[tpl_path]" id="ctd_plugin_options[tpl_path]" value="" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row">Custom field:</th>
						<td><input type="text" name="ctd_plugin_options[cstm_field]" id="ctd_plugin_options[cstm_field]" value="" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row">Default value:</th>
						<td><input type="text" name="ctd_plugin_options[def_value]" id="ctd_plugin_options[def_value]" value="" class="regular-text"></td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Сохранить изменения">
			</p>
		</form>
		<?php
	}

	function update_plugin_options( $request ){
		if( ! isset($request) || $request == false )
			return false;

		// Remove unsed rules
		if( count( $_POST['ctd_plugin_rule_remove'] ) >= 1 ){
			foreach( $_POST['ctd_plugin_rule_remove'] as $key ){
				unset( $this->settings['rules'][$key] );
			}
		}

		// Add new
		if( !empty( $request['rule_name'] ) ){
			$this->settings['rules'][] = array(
				'rule_name' => $request['rule_name'],
				'tpl_path' => $request['tpl_path'],
				'cstm_field' => $request['cstm_field'],
				'def_value' => $request['def_value']
			);
		}

		update_option( 'ctd_plugin_options', $this->settings );
	}

	public function apply_rules_filter( $content ){
		$req = $_REQUEST;

		if( isset($req['post_ID']) && isset($req['page_template']) ){
			foreach ( $this->settings['rules'] as $rule_to_apply ){
				delete_post_meta( $req['post_ID'] , $rule_to_apply['cstm_field'] );
				if( $rule_to_apply['tpl_path'] == $req['page_template'] ){
					add_post_meta( $req['post_ID'] , $rule_to_apply['cstm_field'] , $rule_to_apply['def_value'] );
				}
			}
		}

		return $content;
	}

}
