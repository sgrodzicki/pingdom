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
	 * Returns a list overview of all checks
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getAllChecks()
	{
		$client = new \Guzzle\Service\Client('https://api.pingdom.com/api/2.0');

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->get('checks', array('App-Key' => $this->token));
		$request->setAuth($this->username, $this->password);
		$response = $request->send();
		$response = json_decode($response->getBody(), true);

		return $response['checks'];
	}

	/**
	 * Returns a list of all Pingdom probe servers
	 *
	 * @return Probe\Server[]
	 */
	public function getProbes()
	{
		$client = new \Guzzle\Service\Client('https://api.pingdom.com/api/2.0');

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->get('probes', array('App-Key' => $this->token));
		$request->setAuth($this->username, $this->password);
		$response = $request->send();
		$response = json_decode($response->getBody(), true);
		$probes   = array();

		foreach ($response['probes'] as $attributes) {
			$probes[] = new Probe\Server($attributes);
		}

		return $probes;
	}

	/**
	 * Return a list of raw test results for a specified check
	 *
	 * @param int        $checkId
	 * @param int        $limit
	 * @param array|null $probes
	 * @return array
	 */
	public function getResults($checkId, $limit = 100, array $probes = null)
	{
		$client = new \Guzzle\Service\Client('https://api.pingdom.com/api/2.0');

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->get('results/' . $checkId, array('App-Key' => $this->token));
		$request->setAuth($this->username, $this->password);
		$request->getQuery()->set('limit', $limit);

		if (is_array($probes)) {
			$request->getQuery()->set('probes', implode(',', $probes));
		}

		$response = $request->send();
		$response = json_decode($response->getBody(), true);

		return $response['results'];
	}

	/**
	 * Get Intervals of Average Response Time and Uptime During a Given Interval
	 *
	 * @param int $checkId
	 * @param string $resolution
	 * @return array
	 */
	public function getPerformanceSummary($checkId, $resolution = 'hour')
	{
		$client = new \Guzzle\Service\Client('https://api.pingdom.com/api/2.0');

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->get('summary.performance/' . $checkId, array('App-Key' => $this->token));
		$request->setAuth($this->username, $this->password);
		$request->getQuery()->set('resolution', $resolution);
		$request->getQuery()->set('includeuptime', 'true');

		$response = $request->send();
		$response = json_decode($response->getBody(), true);

		return $response['summary'][$resolution . 's'];
	}

	public function addHTTPCheck($name, $host, $url, $sendtoemail, $sendtoiphone, $sendtoandroid, $contactids) {
		$client = new \Guzzle\Service\Client('https://api.pingdom.com/api/2.0');

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->post('checks', array('App-Key' => $this->token));
		$request->setAuth($this->username, $this->password);

		$query = $request->getQuery();

		$query->set('name', $name);
		$query->set('host', $host);
		$query->set('type', 'http');
		$query->set('url', $url);
		$query->set('sendtoemail', $sendtoemail);
		$query->set('sendtoiphone', $sendtoiphone);
		$query->set('sendtoandroid', $sendtoandroid);
		$query->set('contactids', implode(",", $contactids));
		$query->set('use_legacy_notifications', 'true');

		$response = $request->send();
		$response = json_decode($response->getBody(), true);

		return $response;
	}

	public function updateHTTPCheck($checkId, $name, $host, $url, $sendtoemail, $sendtoiphone, $sendtoandroid, $contactids) {
		$client = new \Guzzle\Service\Client('https://api.pingdom.com/api/2.0');

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->put('checks/' . $checkId , array('App-Key' => $this->token));
		$request->setAuth($this->username, $this->password);

		$query = $request->getQuery();

		$query->set('name', $name);
		$query->set('host', $host);
		$query->set('url', $url);
		$query->set('sendtoemail', $sendtoemail);
		$query->set('sendtoiphone', $sendtoiphone);
		$query->set('sendtoandroid', $sendtoandroid);
		$query->set('contactids', implode(",", $contactids));
		$query->set('use_legacy_notifications', 'true');

		$response = $request->send();
		$response = json_decode($response->getBody(), true);

		return $response;
	}
	public function removeCheck($checkId) {
		$client = new \Guzzle\Service\Client('https://api.pingdom.com/api/2.0');

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->delete('checks/' . $checkId, array('App-Key' => $this->token));
		$request->setAuth($this->username, $this->password);

		$response = $request->send();
		$response = json_decode($response->getBody(), true);

		return $response;
	}


	public function getCheck($name)
	{
		$client = new \Guzzle\Service\Client('https://api.pingdom.com/api/2.0');

		/** @var $request \Guzzle\Http\Message\Request */
		$request = $client->get('checks', array('App-Key' => $this->token));
		$request->setAuth($this->username, $this->password);
		$response = $request->send();
		$response = json_decode($response->getBody(), true);

		foreach ($response['checks'] as $key => $value) {
            if($value['name'] == $name){
                return $value['id'];
            }
        }
	}
}
