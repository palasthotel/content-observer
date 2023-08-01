<?php


namespace Palasthotel\WordPress\ContentObserver;


use Palasthotel\WordPress\ContentObserver\Components\Component;

class Settings extends Component {

	public function onCreate() {
		parent::onCreate();

		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );

	}

	public function getApiKey() {
		return get_option( Plugin::OPTION_API_KEY, "" );
	}

	public function isNotifyObserversScheduleDisabled() {
		return get_option( Plugin::OPTION_SCHEDULE_NOTIFY_OBSERVERS_IS_DISABLED, "" ) === "1";
	}

	public function isStartModificationScheduleDisabled() {
		return get_option( Plugin::OPTION_SCHEDULE_START_MODIFICATION_HOOK_IS_DISABLED, "" ) === "1";
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		add_menu_page(
			"Content Observer",
			"Content Observer",
			"manage_options",
			Plugin::DOMAIN,
			'',
			"dashicons-update"
		);
		add_submenu_page(
			Plugin::DOMAIN,
			'Content Observer',
			'Modifications',
			'manage_options',
			Plugin::MODIFICATIONS_PAGE,
			function () {
               $this->plugin->assets->enqueueModifications();
				echo "<div class='wrap'><div id='content-observer-modifications'></div></div>";
			}
		);
		add_submenu_page(
			Plugin::DOMAIN,
			'Content Observer',
			'Manage',
			'manage_options',
			Plugin::MANAGE_PAGE,
			function () {
				$this->plugin->assets->enqueueSettings();
				echo "<div class='wrap'><div id='content-sync__sites'></div></div>";
			}
		);
		add_submenu_page(
			Plugin::DOMAIN,
			'Settings « Content Observer',
			'Settings',
			'manage_options',
			Plugin::SETTINGS_PAGE,
			array( $this, 'create_admin_page' )
		);
	}


	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		?>
        <div class="wrap">
            <h1>Settings « Content Observer</h1>
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
	public function page_init() {
		register_setting(
			Plugin::DOMAIN,
			Plugin::OPTION_API_KEY
		);
		register_setting(
			Plugin::DOMAIN,
			Plugin::OPTION_SCHEDULE_NOTIFY_OBSERVERS_IS_DISABLED
		);
		register_setting(
			Plugin::DOMAIN,
			Plugin::OPTION_SCHEDULE_START_MODIFICATION_HOOK_IS_DISABLED
		);

		add_settings_section(
			'security',
			'Security',
			function () {
			},
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
			'schedules',
			'Schedules',
			function () {
			    echo "<p>Disable schedules if you use a custom cron with wp cli which is much better!</p>";
			},
			Plugin::SETTINGS_PAGE
		);

		add_settings_field(
			Plugin::OPTION_SCHEDULE_NOTIFY_OBSERVERS_IS_DISABLED,
			'Disable observer notification',
			function(){
			    $this->checkbox(
				    $this->isNotifyObserversScheduleDisabled(),
				    Plugin::OPTION_SCHEDULE_NOTIFY_OBSERVERS_IS_DISABLED,
                    "Disable observer notification schedule."
                );
            },
			Plugin::SETTINGS_PAGE,
			'schedules'
		);

		add_settings_field(
			Plugin::OPTION_SCHEDULE_START_MODIFICATION_HOOK_IS_DISABLED,
			'Disable modification action',
			function(){
			    $this->checkbox(
			            $this->isStartModificationScheduleDisabled(),
                    Plugin::OPTION_SCHEDULE_START_MODIFICATION_HOOK_IS_DISABLED,
                    "Disable modification propagation schedule."
                );
            },
			Plugin::SETTINGS_PAGE,
			'schedules'
		);

	}

	public function api_key() {
		$apiKey = $this->getApiKey();
		echo "<input value='$apiKey' type='text' name='" . Plugin::OPTION_API_KEY . "' />";
	}

	public function checkbox($isChecked, $optionName, $info){
		$checked = $isChecked ? "checked='checked'" : "";
		printf(
			"<input value='1' %s type='checkbox' name='%s' /> %s",
			$checked,
			$optionName,
			$info
		);
	}

}
