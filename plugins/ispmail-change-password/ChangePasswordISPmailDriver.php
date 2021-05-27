<?php

class ChangePasswordISPmailDriver implements \RainLoop\Providers\ChangePassword\ChangePasswordInterface
{
	/**
	 * @var string
	 */
	private string $sHost = '127.0.0.1';

	/**
	 * @var int
	 */
	private int $iPort = 3306;

	/**
	 * @var string
	 */
	private string $sDatabase = 'mailserver';

	/**
	* @var string
	*/
	private string $sTable = 'virtual_users';

	/**
	* @var string
	*/
	private string $sUsercol = 'email';

	/**
	* @var string
	*/
	private string $sPasscol = 'password';

	/**
	 * @var string
	 */
	private string $sUser = 'mailuser';

	/**
	 * @var string
	 */
	private string $sPassword = '';

	/**
	 * @var string
	 */
	private string $sEncrypt = '';

	/**
	 * @var string
	 */
	private string $sAllowedEmails = '';

	/**
	 * @var \MailSo\Log\Logger
	 */
	private $oLogger = null;

	/**
	 * @param string $sHost
	 *
	 * @return \ChangePasswordISPmailDriver
	 */
	public function SetHost($sHost)
	: self {
		$this->sHost = $sHost;
		return $this;
	}

	/**
	 * @param int $iPort
	 *
	 * @return \ChangePasswordISPmailDriver
	 */
	public function SetPort(int $iPort)
	: self {
		$this->iPort = (int) $iPort;
		return $this;
	}

	/**
	 * @param string $sDatabase
	 *
	 * @return \ChangePasswordISPmailDriver
	 */
	public function SetDatabase($sDatabase)
	: self {
		$this->sDatabase = $sDatabase;
		return $this;
	}

	/**
	* @param string $sTable
	*
	* @return \ChangePasswordISPmailDriver
	*/
	public function SetTable($sTable)
	: self {
		$this->sTable = $sTable;
		return $this;
	}

	/**
	* @param string $sUsercol
	*
	* @return \ChangePasswordISPmailDriver
	*/
	public function SetUserColumn($sUsercol)
	: self {
		$this->sUsercol = $sUsercol;
		return $this;
	}

	/**
	* @param string $sPasscol
	*
	* @return \ChangePasswordISPmailDriver
	*/
	public function SetPasswordColumn($sPasscol)
	: self {
		$this->sPasscol = $sPasscol;
		return $this;
	}

	/**
	 * @param string $sUser
	 *
	 * @return \ChangePasswordISPmailDriver
	 */
	public function SetUser($sUser)
	: self {
		$this->sUser = $sUser;
		return $this;
	}

	/**
	 * @param string $sPassword
	 *
	 * @return \ChangePasswordISPmailDriver
	 */
	public function SetPassword($sPassword)
	: self {
		$this->sPassword = $sPassword;
		return $this;
	}

	/**
	 * @param string $sEncrypt
	 *
	 * @return \ChangePasswordISPmailDriver
	 */
	public function SetEncrypt($sEncrypt)
	: self {
		$this->sEncrypt = $sEncrypt;
		return $this;
	}

	/**
	 * @param string $sAllowedEmails
	 *
	 * @return \ChangePasswordISPmailDriver
	 */
	public function SetAllowedEmails($sAllowedEmails)
	: self {
		$this->sAllowedEmails = $sAllowedEmails;
		return $this;
	}

	/**
	 * @param \MailSo\Log\Logger $oLogger
	 *
	 * @return \ChangePasswordISPmailDriver
	 */
	public function SetLogger($oLogger)
	: self {
		if ($oLogger instanceof \MailSo\Log\Logger)
		{
			$this->oLogger = $oLogger;
		}

		return $this;
	}

	/**
	 * @param \RainLoop\Model\Account $oAccount
	 *
	 * @return bool
	 */
	public function PasswordChangePossibility($oAccount)
	: bool {
		return $oAccount && $oAccount->Email() &&
			\RainLoop\Plugins\Helper::ValidateWildcardValues($oAccount->Email(), $this->sAllowedEmails);
	}

	/**
	 * @param \RainLoop\Model\Account $oAccount
	 * @param string $sPrevPassword
	 * @param string $sNewPassword
	 *
	 * @return bool
	 */
	public function ChangePassword(\RainLoop\Account $oAccount, $sPrevPassword, $sNewPassword)
	{
		if ($this->oLogger)
		{
			$this->oLogger->Write('ISPmail: Try to change password for '.$oAccount->Email());
		}

		unset($sPrevPassword);

		$bResult = false;

		if (0 < \strlen($sNewPassword))
		{
			try
			{
				$sDsn = 'mysql:host='.$this->sHost.';port='.$this->iPort.';dbname='.$this->sDatabase;

				$oPdo = new \PDO($sDsn, $this->sUser, $this->sPassword);
				$oPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

				$sUpdatePassword = $this->cryptPassword($sNewPassword, $oPdo);
				if (0 < \strlen($sUpdatePassword))
				{
					$oStmt = $oPdo->prepare("UPDATE {$this->sTable} SET {$this->sPasscol} = ? WHERE {$this->sUsercol} = ?");
					$bResult = (bool) $oStmt->execute(array($sUpdatePassword, $oAccount->Email()));
				}
				else
				{
					if ($this->oLogger)
					{
						$this->oLogger->Write('ISPmail: Encrypted password is empty',
							\MailSo\Log\Enumerations\Type::ERROR);
					}
				}

				$oPdo = null;
			}
			catch (\Exception $oException)
			{
				if ($this->oLogger)
				{
					$this->oLogger->WriteException($oException);
				}
			}
		}

		return $bResult;
	}

	/**
	 * @param string $sPassword
	 * @param \PDO $oPdo
	 *
	 * @return string
	 */
	private function cryptPassword(string $sPassword, $oPdo)
	{
		$sResult = '';
		$sSalt = substr(str_shuffle('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 16);
		switch (strtolower($this->sEncrypt))
		{
                        default:
			case 'plain-md5':
				$sResult = '{PLAIN-MD5}' . md5($sPassword);
				break;

			case 'sha256-crypt':
				$sResult = '{SHA256-CRYPT}' . crypt($sPassword,'$5$'.$sSalt);
				break;
		}

		return $sResult;
	}
}
