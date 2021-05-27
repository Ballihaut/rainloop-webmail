<?php

/*
 * This file is part of MailSo.
 *
 * (c) 2014 Usenko Timur
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MailSo\Mail;

/**
 * @category MailSo
 * @package Mail
 */
class MessageCollection extends \MailSo\Base\Collection
{
	/**
	 * @var string
	 */
	public string $FolderHash;

	/**
	 * @var int
	 */
	public int $MessageCount;

	/**
	 * @var int
	 */
	public int $MessageUnseenCount;

	/**
	 * @var int
	 */
	public int $MessageResultCount;

	/**
	 * @var string
	 */
	public string $FolderName;

	/**
	 * @var int
	 */
	public int $Offset;

	/**
	 * @var int
	 */
	public int $Limit;

	/**
	 * @var string
	 */
	public string $Search;

	/**
	 * @var string
	 */
	public string $UidNext;

	/**
	 * @var string
	 */
	public string $ThreadUid;

	/**
	 * @var array
	 */
	public array $NewMessages;

	/**
	 * @var bool
	 */
	public bool $Filtered;

	/**
	 * @access protected
	 */
	protected function __construct()
	{
		parent::__construct();

		$this->Clear();
	}

	/**
	 * @return \MailSo\Mail\MessageCollection
	 */
	public static function NewInstance()
	{
		return new self();
	}

	/**
	 * @return \MailSo\Mail\MessageCollection
	 */
	public function Clear()
	: self {
		parent::Clear();

		$this->FolderHash = '';

		$this->MessageCount = 0;
		$this->MessageUnseenCount = 0;
		$this->MessageResultCount = 0;

		$this->FolderName = '';
		$this->Offset = 0;
		$this->Limit = 0;
		$this->Search = '';
		$this->UidNext = '';
		$this->ThreadUid = '';
		$this->NewMessages = array();

		$this->Filtered = false;

		return $this;
	}
}
