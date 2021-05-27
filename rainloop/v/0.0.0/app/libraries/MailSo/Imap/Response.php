<?php

/*
 * This file is part of MailSo.
 *
 * (c) 2014 Usenko Timur
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MailSo\Imap;

/**
 * @category MailSo
 * @package Imap
 */
class Response
{
	/**
	 * @var array
	 */
	public array $ResponseList;

	/**
	 * @var array | null
	 */
	public $OptionalResponse;

	/**
	 * @var string
	 */
	public string $StatusOrIndex;

	/**
	 * @var string
	 */
	public string $HumanReadable;

	/**
	 * @var bool
	 */
	public bool $IsStatusResponse;

	/**
	 * @var string
	 */
	public int $ResponseType;

	/**
	 * @var string
	 */
	public string $Tag;

	/**
	 * @access private
	 */
	private function __construct()
	{
		$this->ResponseList = array();
		$this->OptionalResponse = null;
		$this->StatusOrIndex = '';
		$this->HumanReadable = '';
		$this->IsStatusResponse = false;
		$this->ResponseType = \MailSo\Imap\Enumerations\ResponseType::UNKNOWN;
		$this->Tag = '';
	}

	/**
	 * @return \MailSo\Imap\Response
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @param string $aList
	 * 
	 * @return string
	 */
	private function recToLine($aList)
	: string {
		$aResult = array();
		if (\is_array($aList))
		{
			foreach ($aList as $mItem)
			{
				$aResult[] = \is_array($mItem) ? '('.$this->recToLine($mItem).')' : (string) $mItem;
			}
		}

		return \implode(' ', $aResult);
	}
	

	/**
	 * @return string
	 */
	public function ToLine()
	{
		return $this->recToLine($this->ResponseList);
	}
}
