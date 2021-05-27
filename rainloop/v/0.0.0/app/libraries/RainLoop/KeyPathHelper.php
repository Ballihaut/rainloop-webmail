<?php

namespace RainLoop;

class KeyPathHelper
{
	/**
	 * @param string $sHash
	 *
	 * @return string
	 */
	public static function PublicFile(string $sHash)
	: string {
		return '/Public/Files/'.sha1($sHash).'/Data/';
	}

	/**
	 * @param string $sSsoHash
	 *
	 * @return string
	 */
	public static function SsoCacherKey(string $sSsoHash)
	: string {
		return '/Sso/Data/'.$sSsoHash.'/Login/';
	}

	/**
	 * @param string $sHash
	 *
	 * @return string
	 */
	public static function RsaCacherKey(string $sHash)
	: string {
		return '/Rsa/Data/'.$sHash.'/';
	}

	/**
	 * @param string $sDomain
	 *
	 * @return string
	 */
	public static function LicensingDomainKeyValue(string $sDomain)
	: string {
		return '/Licensing/DomainKey/Value/'.$sDomain;
	}

	/**
	 * @param string $sDomain
	 *
	 * @return string
	 */
	public static function LicensingDomainKeyOtherValue(string $sDomain)
	: string {
		return '/Licensing/DomainKeyOther/Value/'.$sDomain;
	}

	/**
	 * @param string $sRepo
	 * @param string $sRepoFile
	 *
	 * @return string
	 */
	public static function RepositoryCacheFile(string $sRepo, string $sRepoFile)
	: string {
		return '/RepositoryCache/Repo/'.$sRepo.'/File/'.$sRepoFile;
	}

	/**
	 * @param string $sRepo
	 *
	 * @return string
	 */
	public static function RepositoryCacheCore(string $sRepo)
	{
		return '/RepositoryCache/CoreRepo/'.$sRepo;
	}

	/**
	 * @param string $sEmail
	 * @param string $sFolderFullName
	 * @param string $sUid
	 *
	 * @return string
	 */
	public static function ReadReceiptCache(string $sEmail, string $sFolderFullName, string $sUid)
	: string {
		return '/ReadReceipt/'.$sEmail.'/'.$sFolderFullName.'/'.$sUid;
	}

	/**
	 * @param string $sLanguage
	 * @param bool $bAdmim
	 * @param string $sPluginsHash
	 *
	 * @return string
	 */
	public static function LangCache(string $sLanguage, $bAdmim, string $sPluginsHash)
	: string {
		return '/LangCache/'.$sPluginsHash.'/'.$sLanguage.'/'.($bAdmim ? 'Admin' : 'App').'/'.APP_VERSION.'/';
	}

	/**
	 * @param bool $bAdmin
	 * @param string $sPluginsHash
	 *
	 * @return string
	 */
	public static function TemplatesCache($bAdmin, string $sPluginsHash)
	: string {
		return '/TemplatesCache/'.$sPluginsHash.'/'.($bAdmin ? 'Admin' : 'App').'/'.APP_VERSION.'/';
	}

	/**
	 * @param string $sPluginsHash
	 *
	 * @return string
	 */
	public static function PluginsJsCache(string $sPluginsHash)
	: string {
		return '/PluginsJsCache/'.$sPluginsHash.'/'.APP_VERSION.'/';
	}

	/**
	 * @param string $sTheme
	 * @param string $sHash
	 * @param string $sPublickHash
	 *
	 * @return string
	 */
	public static function CssCache(string $sTheme, string $sHash)
	: string {
		return '/CssCache/'.$sHash.'/'.$sTheme.'/'.APP_VERSION.'/';
	}

	/**
	 * @param string $sRand
	 *
	 * @return string
	 */
	public static function SessionAdminKey(string $sRand)
	: string {
		return '/Session/AdminKey/'.\md5($sRand).'/';
	}
}
