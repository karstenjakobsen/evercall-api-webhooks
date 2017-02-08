<?php if ( ! defined( 'ABSPATH' ) || ! class_exists( 'NF_Abstracts_Action' )) exit;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../Factories/ClientFactory.php';

/**
 * Class Evercall_Webhooks_Actions_Webhooks
 */
final class Evercall_Webhooks_Actions_Webhooks extends NF_Abstracts_Action
{
    /**
     * @var string
     */
    protected $_name  = 'webhooks';

    /**
     * @var array
     */
    protected $_tags = array();

    /**
     * @var string
     */
    protected $_timing = 'normal';

    /**
     * @var int
     */
    protected $_priority = '10';

    /**
     * @var array
     */
    protected $_debug = array();

    /**
     * Constructor
     */
    public function __construct()
	{
		parent::__construct();

		$this->_nicename = __( 'Evercall API Webhooks', 'evercall-forms-webhooks' );

		add_action( 'admin_init', array( $this, 'init_settings' ) );

		add_action( 'ninja_forms_builder_templates', array( $this, 'builder_templates' ) );

	}

    /*
    * PUBLIC METHODS
    */

    public function save( $action_settings ) {}

    public function init_settings()
    {
        $settings = Evercall_Webhooks::config( 'ActionWebhooksSettings' );
        $this->_settings = array_merge( $this->_settings, $settings );

    }

    public function builder_templates()
    {
        Evercall_Webhooks::template( 'args-repeater-row.html.php' );
    }

	/**
	 * @param $wh_args
	 * @param $formData
	 * @return mixed
	 */
    private function parseFormData( $wh_args, $formData ) {

		$args = array();

		foreach ($wh_args as $arg_data) {
			$args[$arg_data['key']] = $arg_data['value'];
		}

		foreach( $formData['fields'] as $field ) {

			$settings 		= $field['settings'];
			$field_key 		= $settings['key'];
			$field_value 	= $settings['value'];

			// Search and replace
			$args = str_replace('{field:'.$field_key.'}', $field_value, $args);
		}

		foreach ($args as $key => $value) {
			if(stripos($args[$key], ';'))
				$args[$key] = explode(';',$args[$key]);
		}

		// remove all occurences of ;
		$args = str_replace(';','',$args);

		return $args;
	}

	private function parseNinjaArgs( $wh_args ) {

		$args = array();

		foreach ($wh_args as $arg_data) {
			$args[$arg_data['key']] = $arg_data['value'];
		}

		foreach ($args as $key => $value) {
			if(stripos($args[$key], ';'))
				$args[$key] = explode(';',$args[$key]);
		}

		// remove all occurences of ;
		$args = str_replace(';','',$args);

		return $args;
	}

	/**
	 * @param $ec_method
	 * @param $args
	 * @param $sandbox
	 * @param $dev
	 * @return \Evercall\Ping|\Evercall\TelemeetingInvitationSMS
	 */
	private function getClient($ec_method, $args, $sandbox, $dev) {
		$client = new ClientFactory($ec_method, $args, $sandbox, $dev);
		return $client->create();
	}

    public function process( $action_settings, $form_id, $data ) {

		$debug 			= $action_settings['wh-debug-mode'];
		$sandbox		= ($action_settings['wh-sandbox-mode'] == 1) ? true : false;
		$dev 			= ($action_settings['wh-dev-mode'] == 1) ? true : false;
		$wh_args 		= $action_settings['wh-args'];
		$ec_method 		= $action_settings['wh-evercall-method'];

		// parse arguments
		$args = $this->parseNinjaArgs($wh_args);

		// Get client
		$client = $this->getClient($ec_method, $args, $sandbox, $dev);

		// Send request
		$client->send();

		if ( 1 == $debug ) {

			$data['debug']['form']['webhooks_response'] .= "<dt><strong>Data args: </strong></dt>";
			$data['debug']['form']['webhooks_response'] .= "<pre>" . print_r($args, true) . "</pre>";

			$data['debug']['form']['webhooks_response'] .= "<dt><strong>Full response: </strong></dt>";
			$data['debug']['form']['webhooks_response'] .= "<pre>" . print_r($client->getResponse(), true) . "</pre>";

			if( $sandbox == false ) {
				$data['debug']['form']['webhooks_response'] .= "<dt><strong>Response Body: </strong></dt>";
				$data['debug']['form']['webhooks_response'] .= "<pre>" . print_r($client->getResponseBody(true), true) . "</pre>";
			}
		}


        return $data;
    }
}
