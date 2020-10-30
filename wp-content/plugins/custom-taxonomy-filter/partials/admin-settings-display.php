<?php
	/**
	 * Provide a dashboard view for the plugin
	 *
	 * This file is used to markup the public-facing aspects of the plugin.
	 *
	 * @link       http://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    customtaxfilterinadmin
	 * @subpackage customtaxfilterinadmin/partials
	 */
	if (!defined('WPINC')) {
		die;
	}
?>
<div class="wrap">
    <h2><?php esc_html_e('CBX Custom Tax Filter: Setting', 'customtaxfilterinadmin'); ?></h2>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">

            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">
							<?php
								$this->settings_api->show_navigation();
								$this->settings_api->show_forms();
							?>
                        </div>
                    </div>
                </div>
            </div>
			<?php
				include('sidebar.php');
			?>

        </div>
		<div class="clear clearfix"></div>
    </div>
</div>