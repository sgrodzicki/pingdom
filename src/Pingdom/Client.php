<?php

namespace Pingdom;

/**
 * Client object for executing commands on a web service.
 */
class Client
{
	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $token;

	/**
	 * @param string $username
	 * @param string $password
	 * @param string $token
	 * @return Client
	 */
	public function __construct($username, $password, $token)
	{
		$this->username = $username;
		$this->password = $password;
		$this->token    = $token;

		return $this;
	}

	/**
	 * Return all checks
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getChecks()
	{
		$client = new \Guzzle\Service\Client('https://api.pingdom.com/api/2.0');

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->get('checks', array('App-Key' => $this->token));
		$request->setAuth($this->username, $this->password);
		$response = $request->send();

		// Execute the request and decode the json result into an associative array
		$response = json_decode($response->getBody(), true);

		// Check for errors returned by the API
		if (isset($response['error'])) {
			throw new \Exception("Error: " . $response['error']['errormessage']);
		}

		// Fetch the list of checks from the response
		$checksList = $response['checks'];

		return $checksList;
	}
}
