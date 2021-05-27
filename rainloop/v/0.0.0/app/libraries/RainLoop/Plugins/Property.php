<?php

namespace RainLoop\Plugins;

class Property
{
	/**
	 * @var string
	 */
	private $sName;
	
	/**
	 * @var string
	 */
	private string $sLabel;

	/**
	 * @var string
	 */
	private string $sDesc;
	
	/**
	 * @var int
	 */
	private int $iType;

	/**
	 * @var bool
	 */
	private $bAllowedInJs;
	
	/**
	 * @var mixed
	 */
	private string $mDefaultValue;

	/**
	 * @var string
	 */
	private string $sPlaceholder;
	
	private function __construct($sName)
	{
		$this->sName = $sName;
		$this->iType = \RainLoop\Enumerations\PluginPropertyType::STRING;
		$this->mDefaultValue = '';
		$this->sLabel = '';
		$this->sDesc = '';
		$this->bAllowedInJs = false;
		$this->sPlaceholder = '';
	}
	
	/**
	 * @param string $sName
	 * 
	 * @return \RainLoop\Plugins\Property
	 */
	public static function NewInstance($sName)
	{
		return new self($sName);
	}
	
	/**
	 * @param int $iType
	 * 
	 * @return \RainLoop\Plugins\Property
	 */
	public function SetType(int $iType)
	: self {
		$this->iType = (int) $iType;
		
		return $this;
	}
	
	/**
	 * @param mixed $mDefaultValue
	 * 
	 * @return \RainLoop\Plugins\Property
	 */
	public function SetDefaultValue($mDefaultValue)
	: self {
		$this->mDefaultValue = $mDefaultValue;
		
		return $this;
	}

	/**
	 * @param string $sPlaceholder
	 *
	 * @return \RainLoop\Plugins\Property
	 */
	public function SetPlaceholder($sPlaceholder)
	: self {
		$this->sPlaceholder = $sPlaceholder;

		return $this;
	}
	
	/**
	 * @param string $sLabel
	 * 
	 * @return \RainLoop\Plugins\Property
	 */
	public function SetLabel($sLabel)
	: self {
		$this->sLabel = $sLabel;
		
		return $this;
	}

	/**
	 * @param string $sDesc
	 *
	 * @return \RainLoop\Plugins\Property
	 */
	public function SetDescription($sDesc)
	: self {
		$this->sDesc = $sDesc;

		return $this;
	}

	/**
	 * @param bool $bValue = true
	 * @return \RainLoop\Plugins\Property
	 */
	public function SetAllowedInJs(bool $bValue = true)
	: self {
		$this->bAllowedInJs = !!$bValue;

		return $this;
	}
	
	/**
	 * @return string
	 */
	public function Name()
	{
		return $this->sName;
	}

	/**
	 * @return bool
	 */
	public function AllowedInJs()
	{
		return $this->bAllowedInJs;
	}
	
	/**
	 * @return string
	 */
	public function Description()
	{
		return $this->sDesc;
	}

	/**
	 * @return string
	 */
	public function Label()
	{
		return $this->sLabel;
	}
	
	/**
	 * @return int
	 */
	public function Type()
	{
		return $this->iType;
	}
	
	/**
	 * @return mixed
	 */
	public function DefaultValue()
	{
		return $this->mDefaultValue;
	}

	/**
	 * @return string
	 */
	public function Placeholder()
	{
		return $this->sPlaceholder;
	}
	
	/**
	 * @return array
	 */
	public function ToArray()
	: array {
		return array(
			 '',
			 $this->sName,
			 $this->iType,
			 $this->sLabel,
			 $this->mDefaultValue,
			 $this->sDesc,
			 $this->sPlaceholder
		);
	}
}
