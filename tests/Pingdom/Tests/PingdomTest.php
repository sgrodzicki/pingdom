<?php

namespace Pingdom\Tests;

class PingdomTest extends \PHPUnit_Framework_TestCase
{
	private $username, $password, $token;

	protected function setUp()
	{
		global $username, $password, $token;

		$this->username = $username;
		$this->password = $password;
		$this->token    = $token;

		parent::setUp();
	}

	public function testChecks()
	{
		try {
			$pingdom = new \Pingdom\Client($this->username, $this->password, $this->token);
			$checks  = $pingdom->getChecks();

			$this->assertTrue(is_array($checks));
		} catch (\Exception $e) {
			$this->fail('User credentials missing');
		}
	}
}
