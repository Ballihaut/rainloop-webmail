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
class Config
{
	/**
	 * @var bool
	 */
	public static bool $ICONV = true;

	/**
	 * @var bool
	 */
	public static bool $MBSTRING = true;

	/**
	 * @var array|null
	 */
	public static $HtmlStrictAllowedTags = null;

	/**
	 * @var array|null
	 */
	public static $HtmlStrictAllowedAttributes = null;

	/**
	 * @var boolean
	 */
	public static $HtmlStrictDebug = false;

	/**
	 * @var bool
	 */
	public static bool $FixIconvByMbstring = true;

	/**
	 * @var int
	 */
	public static $MessageListFastSimpleSearch = true;

	/**
	 * @var int
	 */
	public static int $MessageListCountLimitTrigger = 0;

	/**
	 * @var bool
	 */
	public static bool $MessageListUndeletedOnly = true;

	/**
	 * @var int
	 */
	public static int $MessageListDateFilter = 0;

	/**
	 * @var string
	 */
	public static string $MessageListPermanentFilter = '';

	/**
	 * @var int
	 */
	public static int $LargeThreadLimit = 50;

	/**
	 * @var bool
	 */
	public static $MessageAllHeaders = false;

	/**
	 * @var bool
	 */
	public static bool $LogSimpleLiterals = false;

	/**
	 * @var bool
	 */
	public static $CheckNewMessages = true;

	/**
	 * @var bool
	 */
	public static bool $PreferStartTlsIfAutoDetect = true;

	/**
	 * @var string
	 */
	public static string $BoundaryPrefix = '_Part_';

	/**
	 * @var int
	 */
	public static int $ImapTimeout = 300;

	/**
	 * @var \MailSo\Log\Logger|null
	 */
	public static $SystemLogger = null;
}
