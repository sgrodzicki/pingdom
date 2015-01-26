<?php

namespace Pingdom\Tests;

class PingdomTest extends \PHPUnit_Framework_TestCase
{
	protected $username, $password, $token;

	protected function setUp()
	{
		global $username, $password, $token;

		$this->username = $username;
		$this->password = $password;
		$this->token    = $token;

		parent::setUp();
	}

	public function testCredentials()
	{
		if (is_null($this->username) || is_null($this->password) || is_null($this->token)) {
			$this->markTestSkipped('User credentials missing');
		}
	}

	/**
	 * @depends testCredentials
	 */
	public function testChecks()
	{
		$pingdom = new \Pingdom\Client($this->username, $this->password, $this->token);
		$checks  = $pingdom->getChecks();

		$this->assertTrue(is_array($checks));

		return $checks;
	}

	/**
	 * @depends testCredentials
	 */
	public function testProbes()
	{
		$pingdom    = new \Pingdom\Client($this->username, $this->password, $this->token);
		$probes     = $pingdom->getProbes();
		$attributes = array('id', 'country', 'city', 'name', 'active', 'hostname', 'ip', 'countryiso');

		$this->assertTrue(is_array($probes));

		foreach ($probes as $probe) {
			$this->assertInstanceOf('Pingdom\Probe\Server', $probe);

			foreach ($attributes as $attribute) {
				$this->assertObjectHasAttribute($attribute, $probe);
			}

			$this->assertTrue(is_int($probe->getId()));
			$this->assertTrue(is_bool($probe->getActive()));

			$this->assertTrue(is_string($probe->getCity()));
			$this->assertStringMatchesFormat('%s', $probe->getCity());

			$this->assertTrue(is_string($probe->getCountry()));
			$this->assertStringMatchesFormat('%s', $probe->getCountry());

			$this->assertTrue(is_string($probe->getCountryiso()));
			$this->assertStringMatchesFormat('%c%c', $probe->getCountryiso());

			$this->assertTrue(is_string($probe->getHostname()));
			$this->assertStringMatchesFormat('s%d.pingdom.com', $probe->getHostname());

			$this->assertTrue(is_string($probe->getIp()));
			$this->assertStringMatchesFormat('%d.%d.%d.%d', $probe->getIp());

			$this->assertTrue(is_string($probe->getName()));
			$this->assertStringMatchesFormat('%s', $probe->getName());

			$this->assertTrue(is_string((string) $probe));
			$this->assertEquals($probe->getName(), (string) $probe);
		}
	}

	/**
	 * @depends testChecks
	 */
	public function testResults(array $checks)
	{
		$keys = array(
			'id',
			'created',
			'name',
			'hostname',
			'resolution',
			'type',
			'lastresponsetime',
			'status',
		);

		foreach ($checks as $check) {
			foreach ($keys as $key) {
				$this->assertArrayHasKey($key, $check);
			}
		}
	}

	/**
	 * @depends testChecks
	 */
	public function testPerformanceSummary(array $checks)
	{
		$pingdom = new \Pingdom\Client($this->username, $this->password, $this->token);
		$keys = array(
			'unmonitored',
			'uptime',
			'avgresponse',
			'starttime',
			'downtime',
		);

		foreach ($checks as $check) {
			foreach (array('hour', 'day', 'week') as $resolution) {
				foreach ($pingdom->getPerformanceSummary($check['id'], $resolution) as $summary) {
					foreach ($keys as $key) {
						$this->assertArrayHasKey($key, $summary);
					}
				}
			}
		}
	}
}
