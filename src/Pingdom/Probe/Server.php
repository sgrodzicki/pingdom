<?php

namespace Pingdom\Probe;

class Server
{
	protected $id, $country, $city, $name, $active, $hostname, $ip, $countryiso;

	/**
	 * @param array $attributes
	 */
	public function __construct(array $attributes)
	{
		foreach ($attributes as $key => $value) {
			if (property_exists($this, $key)) {
				$this->{$key} = $value;
			}
		}
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return (int) $this->id;
	}

	/**
	 * @return bool
	 */
	public function getActive()
	{
		return (bool) $this->active;
	}

	/**
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * @return string
	 */
	public function getCountryiso()
	{
		return $this->countryiso;
	}

	/**
	 * @return string
	 */
	public function getHostname()
	{
		return $this->hostname;
	}

	/**
	 * @return string
	 */
	public function getIp()
	{
		return $this->ip;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
}
