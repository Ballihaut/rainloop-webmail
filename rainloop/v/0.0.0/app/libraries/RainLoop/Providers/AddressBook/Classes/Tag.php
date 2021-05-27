<?php

namespace RainLoop\Providers\AddressBook\Classes;

class Tag
{
	/**
	 * @var string
	 */
	public string $IdContactTag;

	/**
	 * @var string
	 */
	public string $Name;

	/**
	 * @var bool
	 */
	public bool $ReadOnly;

	public function __construct()
	{
		$this->Clear();
	}

	public function Clear()
	{
		$this->IdContactTag = '';
		$this->Name = '';
		$this->ReadOnly = false;
	}
}
