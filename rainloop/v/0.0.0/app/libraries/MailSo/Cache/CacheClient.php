<?php

/*
 * This file is part of MailSo.
 *
 * (c) 2014 Usenko Timur
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MailSo\Cache;

/**
 * @category MailSo
 * @package Cache
 */
class CacheClient
{
	/**
	 * @var \MailSo\Cache\DriverInterface
	 */
	private $oDriver;

	/**
	 * @var string
	 */
	private string $sCacheIndex;

	/**
	 * @access private
	 */
	private function __construct()
	{
		$this->oDriver = null;
		$this->sCacheIndex = '';
	}

	/**
	 * @return \MailSo\Cache\CacheClient
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return bool
	 */
	public function Set(string $sKey, $sValue)
	: string {
		return $this->oDriver ? $this->oDriver->Set($sKey.$this->sCacheIndex, $sValue) : false;
	}

	/**
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function SetTimer(string $sKey)
	{
		return $this->Set($sKey.'/TIMER', time());
	}

	/**
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function SetLock(string $sKey)
	{
		return $this->Set($sKey.'/LOCK', '1');
	}

	/**
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function RemoveLock(string $sKey)
	{
		return $this->Set($sKey.'/LOCK', '0');
	}

	/**
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function GetLock(string $sKey)
	: bool {
		return '1' === $this->Get($sKey.'/LOCK');
	}

	/**
	 * @param string $sKey
	 * @param string $bClearAfterGet = false
	 *
	 * @return string
	 */
	public function Get(string $sKey, bool $bClearAfterGet = false)
	{
		$sValue = '';

		if ($this->oDriver)
		{
			$sValue = $this->oDriver->Get($sKey.$this->sCacheIndex);
		}

		if ($bClearAfterGet)
		{
			$this->Delete($sKey);
		}

		return $sValue;
	}

	/**
	 * @param string $sKey
	 *
	 * @return int
	 */
	public function GetTimer(string $sKey)
	{
		$iTimer = 0;
		$sValue = $this->Get($sKey.'/TIMER');
		if (0 < strlen($sValue) && is_numeric($sValue))
		{
			$iTimer = (int) $sValue;
		}

		return $iTimer;
	}

	/**
	 * @param string $sKey
	 *
	 * @return \MailSo\Cache\CacheClient
	 */
	public function Delete(string $sKey)
	: self {
		if ($this->oDriver)
		{
			$this->oDriver->Delete($sKey.$this->sCacheIndex);
		}

		return $this;
	}

	/**
	 * @param \MailSo\Cache\DriverInterface $oDriver
	 *
	 * @return \MailSo\Cache\CacheClient
	 */
	public function SetDriver(\MailSo\Cache\DriverInterface $oDriver)
	: self {
		$this->oDriver = $oDriver;

		return $this;
	}

	/**
	 * @param int $iTimeToClearInHours = 24
	 *
	 * @return bool
	 */
	public function GC(int $iTimeToClearInHours = 24)
	{
		return $this->oDriver ? $this->oDriver->GC($iTimeToClearInHours) : false;
	}

	/**
	 * @return bool
	 */
	public function IsInited()
	{
		return $this->oDriver instanceof \MailSo\Cache\DriverInterface;
	}

	/**
	 * @param string $sCacheIndex
	 *
	 * @return \MailSo\Cache\CacheClient
	 */
	public function SetCacheIndex(string $sCacheIndex)
	: self {
		$this->sCacheIndex = 0 < \strlen($sCacheIndex) ? "\x0".$sCacheIndex : '';

		return $this;
	}

	/**
	 * @param bool $bCache = false
	 *
	 * @return bool
	 */
	public function Verify(bool $bCache = false)
	{
		if ($this->oDriver)
		{
			$sCacheData = \gmdate('Y-m-d-H');
			if ($bCache && $sCacheData === $this->Get('__verify_key__'))
			{
				return true;
			}

			return $this->Set('__verify_key__', $sCacheData);
		}

		return false;
	}
}
