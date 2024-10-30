<?php
# Check if class exist
if (!class_exists('MRKV_NOVA_MENU'))
{
	/**
	 * Class for setup plugin menu data
	 */
	class MRKV_NOVA_MENU
	{
		/**
		 * Constructor for plugin menu data
		 * */
		function __construct()
		{
			# Register page settings
			add_action('admin_menu', array($this, 'mrkv_nova_register_plugin_page'));
		}

		/**
	     * Register plugin page in admin menu
	     */
		public function mrkv_nova_register_plugin_page()
		{
			# Add menu to WP
	        add_menu_page(__('Nova Post Settings', 'mrkv-nova-post'), __('Morkva Nova Post', 'mrkv-nova-post'), 'manage_woocommerce', 'mrkv_nova_settings', array($this, 'mrkv_nova_plugin_page_content'), MRKV_NOVA_PLUGIN_URL . '/assets/img/mrkv_novapost.svg', 56);

	        /*# Add submenu page
	        add_submenu_page('mrkv_nova_settings', __('Create documents', 'mrkv-nova-post'), __('Create documents', 'mrkv-nova-post'), 'manage_woocommerce', 'mrkv_nova_create_documents', array($this, 'mrkv_nova_plugin_create_documents_content'));

	        # Add submenu page
	        add_submenu_page('mrkv_nova_settings', __('Documents', 'mrkv-nova-post'), __('Documents', 'mrkv-nova-post'), 'manage_woocommerce', 'mrkv_nova_documents', array($this, 'mrkv_nova_plugin_documents_content'));*/
		}

		/**
		 * Include page content 
		 * */
		public function mrkv_nova_plugin_page_content()
		{
			# Include template
			include MRKV_NOVA_PLUGIN_PATH . 'templates/template-mrkv-nova-post-settings-page.php';
		}

		/**
		 * Include page content 
		 * */
		public function mrkv_nova_plugin_create_documents_content()
		{
			# Include template
			include MRKV_NOVA_PLUGIN_PATH . 'templates/template-mrkv-nova-post-create-documents-page.php';
		}

		/**
		 * Include page content 
		 * */
		public function mrkv_nova_plugin_documents_content()
		{
			# Include template
			include MRKV_NOVA_PLUGIN_PATH . 'templates/template-mrkv-nova-post-documents-page.php';
		}
	}
}