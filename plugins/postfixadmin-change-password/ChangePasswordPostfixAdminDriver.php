<?php

class ChangePasswordPostfixAdminDriver implements \RainLoop\Providers\ChangePassword\ChangePasswordInterface
{
  /**
	* @var string
	*/
	private string $sEngine = 'MySQL';

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
	private string $sDatabase = 'postfixadmin';

	/**
	* @var string
	*/
	private string $sTable = 'mailbox';

	/**
	* @var string
	*/
	private string $sUsercol = 'username';

	/**
	* @var string
	*/
	private string $sPasscol = 'password';

	/**
	 * @var string
	 */
	private string $sUser = 'postfixadmin';

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
	 * @param string $sEngine
	 *
	 * @return \ChangePasswordPostfixAdminDriver
	 */
	 public function SetEngine($sEngine)
	 : self {
		 $this->sEngine = $sEngine;
		 return $this;
	 }

	/**
	 * @param string $sHost
	 *
	 * @return \ChangePasswordPostfixAdminDriver
	 */
	public function SetHost($sHost)
	: self {
		$this->sHost = $sHost;
		return $this;
	}

	/**
	 * @param int $iPort
	 *
	 * @return \ChangePasswordPostfixAdminDriver
	 */
	public function SetPort(int $iPort)
	: self {
		$this->iPort = (int) $iPort;
		return $this;
	}

	/**
	 * @param string $sDatabase
	 *
	 * @return \ChangePasswordPostfixAdminDriver
	 */
	public function SetDatabase($sDatabase)
	: self {
		$this->sDatabase = $sDatabase;
		return $this;
	}

	/**
	* @param string $sTable
	*
	* @return \ChangePasswordPostfixAdminDriver
	*/
	public function SetTable($sTable)
	: self {
		$this->sTable = $sTable;
		return $this;
	}

	/**
	* @param string $sUsercol
	*
	* @return \ChangePasswordPostfixAdminDriver
	*/
	public function SetUserColumn($sUsercol)
	: self {
		$this->sUsercol = $sUsercol;
		return $this;
	}

	/**
	* @param string $sPasscol
	*
	* @return \ChangePasswordPostfixAdminDriver
	*/
	public function SetPasswordColumn($sPasscol)
	: self {
		$this->sPasscol = $sPasscol;
		return $this;
	}

	/**
	 * @param string $sUser
	 *
	 * @return \ChangePasswordPostfixAdminDriver
	 */
	public function SetUser($sUser)
	: self {
		$this->sUser = $sUser;
		return $this;
	}

	/**
	 * @param string $sPassword
	 *
	 * @return \ChangePasswordPostfixAdminDriver
	 */
	public function SetPassword($sPassword)
	: self {
		$this->sPassword = $sPassword;
		return $this;
	}

	/**
	 * @param string $sEncrypt
	 *
	 * @return \ChangePasswordPostfixAdminDriver
	 */
	public function SetEncrypt($sEncrypt)
	: self {
		$this->sEncrypt = $sEncrypt;
		return $this;
	}

	/**
	 * @param string $sAllowedEmails
	 *
	 * @return \ChangePasswordPostfixAdminDriver
	 */
	public function SetAllowedEmails($sAllowedEmails)
	: self {
		$this->sAllowedEmails = $sAllowedEmails;
		return $this;
	}

	/**
	 * @param \MailSo\Log\Logger $oLogger
	 *
	 * @return \ChangePasswordPostfixAdminDriver
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
			$this->oLogger->Write('Postfix: Try to change password for '.$oAccount->Email());
		}

		unset($sPrevPassword);

		$bResult = false;

		if (0 < \strlen($sNewPassword))
		{
			try
			{
				$sDsn = '';
				switch($this->sEngine){
					case 'MySQL':
				  		$sDsn = 'mysql:host='.$this->sHost.';port='.$this->iPort.';dbname='.$this->sDatabase;
						break;
				  	case 'PostgreSQL':
				 		$sDsn = 'pgsql:host='.$this->sHost.';port='.$this->iPort.';dbname='.$this->sDatabase;
						break;
				  	default:
				    		$sDsn = 'mysql:host='.$this->sHost.';port='.$this->iPort.';dbname='.$this->sDatabase;
					  	break;
				}


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
						$this->oLogger->Write('Postfix: Encrypted password is empty',
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
		if (function_exists('random_bytes')) {
			$sSalt = substr(base64_encode(random_bytes(32)), 0, 16);
		} else {
			$sSalt = substr(str_shuffle('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 16);	
		}
		switch (strtolower($this->sEncrypt))
		{
			default:
			case 'plain':
			case 'cleartext':
				$sResult = '{PLAIN}' . $sPassword;
				break;

			case 'md5crypt':
				include_once __DIR__.'/md5crypt.php';
				$sResult = '{MD5-CRYPT}' . md5crypt($sPassword);
				break;

			case 'md5':
				$sResult = '{PLAIN-MD5}' . md5($sPassword);
				break;

			case 'system':
				$sResult = '{CRYPT}' . crypt($sPassword);
				break;

			case 'sha256-crypt':
				$sResult = '{SHA256-CRYPT}' . crypt($sPassword,'$5$'.$sSalt);
				break;

			case 'sha512-crypt':
				$sResult = '{SHA512-CRYPT}' . crypt($sPassword,'$6$'.$sSalt);
				break;

			case 'mysql_encrypt':
			  if($this->sEngine == 'MySQL'){
			  	$oStmt = $oPdo->prepare('SELECT ENCRYPT(?) AS encpass');
				if ($oStmt->execute(array($sPassword)))
				{
					$aFetchResult = $oStmt->fetchAll(\PDO::FETCH_ASSOC);
					if (\is_array($aFetchResult) && isset($aFetchResult[0]['encpass']))
					{
						$sResult = $aFetchResult[0]['encpass'];
					}
				}
			}else{
				throw new \RainLoop\Exceptions\ClientException(\RainLoop\Notifications::CouldNotSaveNewPassword);
			}
			break;
		}

		return $sResult;
	}
}
