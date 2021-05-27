<?php

namespace RainLoop\Plugins;

abstract class AbstractPlugin
{
	/**
	 * @var \RainLoop\Plugins\Manager
	 */
	private $oPluginManager;

	/**
	 * @var \RainLoop\Config\Plugin
	 */
	private $oPluginConfig;

	/**
	 * @var bool
	 */
	private bool $bLangs;

	/**
	 * @var string
	 */
	private string $sName;

	/**
	 * @var string
	 */
	private string $sPath;

	/**
	 * @var string
	 */
	private string $sVersion;

	/**
	 * @var array
	 */
	private $aConfigMap;

	/**
	 * @var bool
	 */
	private $bPluginConfigLoaded;

	public function __construct()
	{
		$this->sName = '';
		$this->sPath = '';
		$this->sVersion = '0.0';
		$this->aConfigMap = null;

		$this->oPluginManager = null;
		$this->oPluginConfig = null;
		$this->bPluginConfigLoaded = false;
		$this->bLangs = false;
	}

	/**
	 * @return \RainLoop\Config\Plugin
	 */
	public function Config()
	{
		if (!$this->bPluginConfigLoaded && $this->oPluginConfig)
		{
			$this->bPluginConfigLoaded = true;
			if ($this->oPluginConfig->IsInited())
			{
				if (!$this->oPluginConfig->Load())
				{
					$this->oPluginConfig->Save();
				}
			}
		}

		return $this->oPluginConfig;
	}

	/**
	 * @return \RainLoop\Plugins\Manager
	 */
	public function Manager()
	{
		return $this->oPluginManager;
	}

	/**
	 * @return string
	 */
	public function Path()
	{
		return $this->sPath;
	}

	/**
	 * @return string
	 */
	public function Name()
	{
		return $this->sName;
	}

	/**
	 * @return string
	 */
	public function Version()
	{
		return $this->sVersion;
	}

	/**
	 * @param bool | null $bLangs = null
	 * @return bool
	 */
	public function UseLangs($bLangs = null)
	{
		if (null !== $bLangs)
		{
			$this->bLangs = (bool) $bLangs;
		}

		return $this->bLangs;
	}

	/**
	 * @return array
	 */
	protected function configMapping()
	{
		return array();
	}

	/**
	 * @return string
	 */
	public function Hash()
	: string {
		return \md5($this->sName.'@'.$this->sVersion);
	}

	/**
	 * @return string
	 */
	public function Supported()
	{
		return '';
	}

	/**
	 * @final
	 * @return array
	 */
	final public function ConfigMap()
	{
		if (null === $this->aConfigMap)
		{
			$this->aConfigMap = $this->configMapping();
			if (!is_array($this->aConfigMap))
			{
				$this->aConfigMap = array();
			}
		}

		return $this->aConfigMap;
	}

	/**
	 * @param string $sPath
	 *
	 * @return self
	 */
	public function SetPath($sPath)
	: self {
		$this->sPath = $sPath;

		return $this;
	}

	/**
	 * @param string $sName
	 *
	 * @return self
	 */
	public function SetName($sName)
	: self {
		$this->sName = $sName;

		return $this;
	}

	/**
	 * @param string $sVersion
	 *
	 * @return self
	 */
	public function SetVersion(string $sVersion)
	: self {
		if (0 < \strlen($sVersion))
		{
			$this->sVersion = $sVersion;
		}

		return $this;
	}

	/**
	 * @param \RainLoop\Plugins\Manager $oPluginManager
	 *
	 * @return self
	 */
	public function SetPluginManager(\RainLoop\Plugins\Manager $oPluginManager)
	: self {
		$this->oPluginManager = $oPluginManager;

		return $this;
	}

	/**
	 * @param \RainLoop\Config\Plugin $oPluginConfig
	 *
	 * @return self
	 */
	public function SetPluginConfig(\RainLoop\Config\Plugin $oPluginConfig)
	: self {
		$this->oPluginConfig = $oPluginConfig;

		return $this;
	}

	/**
	 * @return void
	 */
	public function PreInit()
	{

	}

	/**
	 * @return void
	 */
	public function Init()
	{

	}

	/**
	 * @param bool $bAdmin
	 * @param bool $bAuth
	 * @param array $aConfig
	 *
	 * @return void
	 */
	public function FilterAppDataPluginSection($bAdmin, $bAuth, &$aConfig)
	{

	}

	/**
	 * @param string $sHookName
	 * @param string $sFunctionName
	 *
	 * @return self
	 */
	protected function addHook($sHookName, $sFunctionName)
	{
		if ($this->oPluginManager)
		{
			$this->oPluginManager->AddHook($sHookName, array(&$this, $sFunctionName));
		}

		return $this;
	}

	/**
	 * @param string $sFile
	 * @param bool $bAdminScope = false
	 *
	 * @return self
	 */
	protected function addJs(string $sFile, bool $bAdminScope = false)
	{
		if ($this->oPluginManager)
		{
			$this->oPluginManager->AddJs($this->sPath.'/'.$sFile, $bAdminScope);
		}

		return $this;
	}

	/**
	 * @param string $sFile
	 * @param bool $bAdminScope = false
	 *
	 * @return self
	 */
	protected function addTemplate(string $sFile, bool $bAdminScope = false)
	{
		if ($this->oPluginManager)
		{
			$this->oPluginManager->AddTemplate($this->sPath.'/'.$sFile, $bAdminScope);
		}

		return $this;
	}

	/**
	 * @param string $sFile
	 * @param bool $bAdminScope = false
	 *
	 * @return self
	 */
	protected function replaceTemplate(string $sFile, bool $bAdminScope = false)
	: self {
		if ($this->oPluginManager)
		{
			$this->oPluginManager->AddTemplate($this->sPath.'/'.$sFile, $bAdminScope);
		}

		return $this;
	}

	/**
	 * @param string $sActionName
	 * @param string $sFunctionName
	 *
	 * @return self
	 */
	protected function addPartHook($sActionName, $sFunctionName)
	: self {
		if ($this->oPluginManager)
		{
			$this->oPluginManager->AddAdditionalPartAction($sActionName, array(&$this, $sFunctionName));
		}

		return $this;
	}

	/**
	 * @param string $sActionName
	 * @param string $sFunctionName
	 *
	 * @return self
	 */
	protected function addAjaxHook($sActionName, $sFunctionName)
	{
		if ($this->oPluginManager)
		{
			$this->oPluginManager->AddAdditionalAjaxAction($sActionName, array(&$this, $sFunctionName));
		}

		return $this;
	}

	/**
	 * @param string $sName
	 * @param string $sPlace
	 * @param string $sHtml
	 * @param bool $bPrepend = false
	 *
	 * @return self
	 */
	protected function addTemplateHook($sName, $sPlace, string $sLocalTemplateName, bool $bPrepend = false)
	{
		if ($this->oPluginManager)
		{
			$this->oPluginManager->AddProcessTemplateAction($sName, $sPlace,
				'<!-- ko template: \''.$sLocalTemplateName.'\' --><!-- /ko -->', $bPrepend);
		}

		return $this;
	}

	/**
	 * @param string $sName
	 * @param string $sPlace
	 * @param string $sHtml
	 * @param bool $bPrepend = false
	 *
	 * @return self
	 */
	protected function ajaxResponse(string $sFunctionName, $aData)
	{
		if ($this->oPluginManager)
		{
			return $this->oPluginManager->AjaxResponseHelper(
				$this->oPluginManager->convertPluginFolderNameToClassName($this->Name()).'::'.$sFunctionName, $aData);
		}

		return \json_encode($aData);
	}

	/**
	 * @param string $sKey
	 * @param mixed $mDefault = null
	 *
	 * @return mixed
	 */
	public function ajaxParam($sKey, $mDefault = null)
	{
		if ($this->oPluginManager)
		{
			return $this->oPluginManager->Actions()->GetActionParam($sKey, $mDefault);
		}

		return '';
	}

	/**
	 * @return array
	 */
	public function getUserSettings()
	{
		if ($this->oPluginManager)
		{
			return $this->oPluginManager->GetUserPluginSettings($this->Name());
		}

		return array();
	}

	/**
	 * @param array $aSettings
	 *
	 * @return bool
	 */
	public function saveUserSettings($aSettings)
	{
		if ($this->oPluginManager && \is_array($aSettings))
		{
			return $this->oPluginManager->SaveUserPluginSettings($this->Name(), $aSettings);
		}

		return false;
	}
}
