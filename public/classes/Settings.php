<?php


namespace Palasthotel\WordPress\ContentObserver;


class Settings extends _Component {

	public function onCreate() {
		parent::onCreate();

		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );

	}

	public function getApiKey(){
		return get_option(Plugin::OPTION_API_KEY, "");
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'Content sync',
			'Content sync',
			'manage_options',
			Plugin::SETTINGS_PAGE,
			array( $this, 'create_admin_page' )
		);
	}


	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		?>
		<div class="wrap">
			<h1>Content sync</h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( Plugin::DOMAIN );
				do_settings_sections( Plugin::SETTINGS_PAGE );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init()
	{
		register_setting(
			Plugin::DOMAIN,
			Plugin::OPTION_API_KEY,
			array( $this, 'sanitize' )
		);

		add_settings_section(
			'security',
			'Security',
			function(){ },
			Plugin::SETTINGS_PAGE
		);

		add_settings_field(
			Plugin::OPTION_API_KEY,
			'API Key',
			array( $this, 'api_key' ),
			Plugin::SETTINGS_PAGE,
			'security'
		);

		add_settings_section(
			'content_sync_sites',
			'Sites',
			function(){
                $this->plugin->assets->enqueueSettings();
                echo "<div id='content-sync__sites'></div>";
            },
			Plugin::SETTINGS_PAGE
		);

	}

	public function api_key(){
	    $apiKey = $this->getApiKey();
	    echo "<input value='$apiKey' type='text' name='".Plugin::OPTION_API_KEY."' />";
	}

}