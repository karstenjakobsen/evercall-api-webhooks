<?php

class ClientFactory {

	const TELEMEETING_INVITATION_SMS = 'telemeeting-invitation-sms';

	const PING = 'ping';

	public function __construct($type, $args, $sandbox, $dev)
	{
		$this->type 	= $type;
		$this->args 	= $args;
		$this->sandbox 	= $sandbox;
		$this->dev 		= $dev;
	}

	/**
	 * @return \Evercall\Ping|\Evercall\TelemeetingInvitationSMS
	 * @throws Exception
	 */
	public function create()
	{
		$httpClient = new \Evercall\SimpleJsonHttp('evercall WP plugin', null, $this->sandbox);

		switch ($this->type) {

			case self::TELEMEETING_INVITATION_SMS:

				$client = new \Evercall\TelemeetingInvitationSMS($httpClient);

				foreach ($this->args['phoneNumber'] as $key => $phoneNumber){
					if($phoneNumber != '') {
						$client->addInvitationSMS($this->args['countryCode'][$key], $phoneNumber, $this->args['sender'], $this->args['meetingPin'], $this->args['meetingTime'], $this->args['executionTime']);
					}
				}

				break;

			case self::PING:

				$client = new \Evercall\Ping($httpClient);
				$client->setPayload($this->args);

				break;

			default:
				throw new Exception('Unknown client type');

		}

		// Set environment
		if( $this->dev == true ) $client->setEnv(\Evercall\EvercallPublicAPI::ENV_DEV);

		return $client;

	}

}