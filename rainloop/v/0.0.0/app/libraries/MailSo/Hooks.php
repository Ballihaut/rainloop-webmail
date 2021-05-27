<?php

/*
 * This file is part of MailSo.
 *
 * (c) 2014 Usenko Timur
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MailSo;

/**
 * @category MailSo
 */
class Hooks
{
	/**
	 * @var array
	 */
	static array $aCallbacks = array();

	/**
	 * @param string $sName
	 * @param array $aArg
	 */
	public static function Run($sName, array $aArg = array())
	{
		if (isset(\MailSo\Hooks::$aCallbacks[$sName]))
		{
			foreach (\MailSo\Hooks::$aCallbacks[$sName] as $mCallback)
			{
				\call_user_func_array($mCallback, $aArg);
			}
		}
	}

	/**
	 * @param string $sName
	 * @param mixed $mCallback
	 */
	public static function Add($sName, $mCallback)
	{
		if (\is_callable($mCallback))
		{
			if (!isset(\MailSo\Hooks::$aCallbacks[$sName]))
			{
				\MailSo\Hooks::$aCallbacks[$sName] = array();
			}

			\MailSo\Hooks::$aCallbacks[$sName][] = $mCallback;
		}
	}
}
