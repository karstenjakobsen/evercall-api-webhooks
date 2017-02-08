<?php if ( ! defined( 'ABSPATH' ) ) exit;

return array(
	

	/*
	|--------------------------------------------------------------------------
	| Evercall Method(s)
	|--------------------------------------------------------------------------
	*/

	'wh-evercall-method' => array(
		'name' => 'wh-evercall-method',
		'type' => 'select',
		'group' => 'primary',
		'label' => __( 'evercall Method', 'evercall-forms-webhooks' ),
		'options' => array(
			array(
				'label' => __( 'Send Telemeeting invitation SMS', 'evercall-forms-webhooks' ),
				'value' => 'telemeeting-invitation-sms'
			),
			array(
				'label' => __( 'Ping', 'evercall-forms-webhooks' ),
				'value' => 'ping'
			),
		),
		'width' => 'full',
	),

    /*
    |--------------------------------------------------------------------------
    | Args Option Rep
    |--------------------------------------------------------------------------
    */

	'wh-args' => array(
		'name' => 'wh-args',
		'type' => 'option-repeater',
		'label' => __( 'Args', 'evercall-forms-webhooks' ). ' <a href="#" class="nf-add-new">' . __( 'Add New' ) . '</a>',
		'width' => 'full',
		'group' => 'primary',
		'tmpl_row' => 'tmpl-nf-webhooks-args-repeater-row',
		'value' => array(),
		'columns'   =>array(
			'key' => array(
				'header' => __( 'Key', 'evercall-forms-webhooks' ),
				'default' => '',
				),
			'value' => array(
				'header' => __( 'Value', 'evercall-forms-webhooks' ),
				'default' => '',
			),
		),
	),

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    */

    'wh-debug-mode' => array(
        'name' => 'wh-debug-mode',
        'type' => 'toggle',
        'label' => __( 'Run in Debug Mode', 'evercall-forms-webhooks' ),
        'width' => 'one-third',
        'group' => 'advanced',
        'help' => __( 'This will show the full request to the remote server and the response received.', 'evercall-forms-webhooks' ),
    ),

	/*
   |--------------------------------------------------------------------------
   | Development Mode
   |--------------------------------------------------------------------------
   */

	'wh-dev-mode' => array(
		'name' => 'wh-dev-mode',
		'type' => 'toggle',
		'label' => __( 'Run in Development Mode', 'evercall-forms-webhooks' ),
		'width' => 'one-third',
		'group' => 'advanced',
		'help' => __( 'This will turn on development mode. Meaning that all request will be sent to development servers.', 'evercall-forms-webhooks' ),
	),

	'wh-sandbox-mode' => array(
		'name' => 'wh-sandbox-mode',
		'type' => 'toggle',
		'label' => __( 'Run in Sandbox Mode', 'evercall-forms-webhooks' ),
		'width' => 'one-third',
		'group' => 'advanced',
		'help' => __( 'This will turn on sandbox mode. Meaning that all request will be NOT be sent to servers.', 'evercall-forms-webhooks' ),
	),

);