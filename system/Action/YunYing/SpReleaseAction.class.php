<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class SpReleaseAction extends PageBase
{	
	private $objStagePropertyBLL = null;
	private $objMasterBLL = null;
	private $iVerType = 0;
	private $SearchType = 1;//按TypeID搜索道具分类
	private $SpClassLocked = 0;//道具分类锁定状态
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objStagePropertyBLL = new StagePropertyBLL();
		$this->objMasterBLL = new MasterBLL();
	}
	public function index()
	{		
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/SpList.html');
	}	 
	
	/**
	 * 分页
	 */
	public function getPagerSp()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='PublicSpID,ImgPath,GoodsName,ResourceID,SpNumber,Place,CateName,TypeID,SP.Locked,ServerID';
		$arrParam['tableName']='T_StageProperty AS Sp INNER JOIN T_StagePropertyPublic AS SpPublic ON Sp.SpID=SpPublic.SpID LEFT JOIN T_Class C ON SpPublic.ClassID=C.ClassID';
		$arrParam['where']=' WHERE SpPublic.Locked=0';
		$arrParam['order']='Sp.PublicSpID';
		$arrParam['pagesize']=15;
		
		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['StageProperty']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);	

		if(is_array($arrResult) && count($arrResult)>0)
		{	
			$iCount = 0;
			foreach ($arrResult as $val)
			{
				$arrResult[$iCount]['Place']='';
				$arrResult[$iCount]['iCount']=$iCount+1;
				$arrResult[$iCount]['GoodsName']=Utility::gb2312ToUtf8($val['GoodsName']);
				foreach($this->arrConfig['Place'] as $v)
				{
					if($val['Place'] & $v['TypeID'])
						$arrResult[$iCount]['Place'] .= $v['TypeName'].',';
				}
				if(!empty($arrResult[$iCount]['Place'])) $arrResult[$iCount]['Place'] = substr($arrResult[$iCount]['Place'], 0,strlen($arrResult[$iCount]['Place'])-1);

				$arrSpInfo = $this->objStagePropertyBLL->getSpInfo($val['PublicSpID']);
				if(is_array($arrSpInfo) && count($arrSpInfo)>0)
					$arrResult[$iCount]['Locked']=$arrSpInfo['Locked'];
				else
					$arrResult[$iCount]['Locked']=1;
				//图片服务器地址
				$ServerIP = '';
				$arrServerInfo = $this->objMasterBLL->getServerInfo($arrResult[$iCount]['ServerID'],42);
				if(is_array($arrServerInfo) && count($arrServerInfo)>0 && !empty($arrServerInfo['ServerIP']))
				{
					$arrServer = explode(',', $arrServerInfo['ServerIP']);
					if(is_array($arrServer) && count($arrServer)>0) 
						$ServerIP = $arrServer[0];
				}
				$arrResult[$iCount]['ImgPath']='http://'.$ServerIP.$arrResult[$iCount]['ImgPath'];
				$iCount++;
			}
		}
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$Page,'SpList'=>$arrResult); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/SpListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 设置上架或下架商城道具,如果不存在,则插入
	 * $iResult=0:成功,-1:失败
	 */
	public function setSpRelease()
	{
		$iResult = -9999;
		$PublicSpID = Utility::isNumeric('PublicSpID',$_POST);	
		if($PublicSpID && $PublicSpID>0)
		{			
			$iResult = $this->objStagePropertyBLL->setSpReleaseLocked($PublicSpID);
		}
		if($iResult==0)
	 		$msg='道具设置成功';
	 	elseif($iResult==-1)		 	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpRelease\')','道具状态设置失败','false','SpRelease',$this->arrConfig);
	 	else		 	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpRelease\')','对不起,您提交的数据异常,请重试','false','SpRelease',$this->arrConfig);
		echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult));
	}	
	/**
	 * 显示编辑道具信息页面
	 */
	public function showAddSpPublicHtml()
	{
		$PublicSpID = Utility::isNumeric('PublicSpID',$_GET);	
		//商城道具信息
		if($PublicSpID && $PublicSpID>0)			
			$arrSpInf = $this->objStagePropertyBLL->getSpInfo($PublicSpID);			
		if(empty($arrSpInf))
			$arrSpInf=Array('PublicSpID'=>0,'Locked'=>0,'Price'=>0,'Sex'=>-1,'Level'=>0,'VipID'=>0,'IsRecommend'=>0,
							'SortID'=>1,'IconID'=>0,'PublicSpID'=>0,'GoodsName'=>'','SpNumber'=>'','ResourceID'=>'','ClassID'=>'',
							'EffectiveType'=>'','Unit'=>'','Number'=>0,'Intro'=>'','ImgPath'=>'','ImgPath1'=>'','ImgPath2'=>'',
							'ServerID'=>0,'Target'=>0,'KindID'=>0,'Place'=>0,'TypeID'=>0,'CustomField'=>'','KeyID'=>0
							);
		//替换标签,显示页面 
		$arrTags=array( 'RecList'=>$this->arrConfig['RecList'],
						'SpClass'=>$this->arrConfig['SpClass'],
						'Sp'=>$arrSpInf
						); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/SpEdit.html');
	}
	/**
	 * 公库道具详细信息
	 */
	public function getSpPublicInfo()
	{
		$ClassName = '';//游戏种类
		$Place = '';//道具使用场景
		$Title = '';
		$ServerIP = '';
		$SpID = Utility::isNumeric('SpID',$_POST);
		if($SpID)
		{
			$arrSpInfo = $this->objStagePropertyBLL->getSpPublicInfo($SpID);			
			if(is_array($arrSpInfo) && count($arrSpInfo)>0)
			{
				//图片服务器地址
				$arrServerInfo = $this->objMasterBLL->getServerInfo($arrSpInfo['ServerID'],42);
				if(is_array($arrServerInfo) && count($arrServerInfo)>0 && !empty($arrServerInfo['ServerIP']))
				{
					$arrServer = explode(',', $arrServerInfo['ServerIP']);
					if(is_array($arrServer) && count($arrServer)>0) 
						$ServerIP = $arrServer[0];
				}
				$arrSpInfo['ImgPath']='http://'.$ServerIP.$arrSpInfo['ImgPath'];
				$arrSpInfo['ImgPath1']='http://'.$ServerIP.$arrSpInfo['ImgPath1'];
				$arrSpInfo['ImgPath2']='http://'.$ServerIP.$arrSpInfo['ImgPath2'];
				//读取道具应用目标游戏
				if($arrSpInfo['KindID']>0 && $arrSpInfo['Target'])
				{
					$arrKindInfo = $this->objMasterBLL->getGameKindInfo($arrSpInfo['KindID']);
					if(is_array($arrKindInfo) && count($arrKindInfo)>0)
					{ 
						$GameClassID = $arrKindInfo['ClassID'];
						foreach ($this->arrConfig['GameKindClass'] as $val)
						{
							if($val['ClassID']==$arrKindInfo['ClassID'])
							{
								$ClassName = $val['ClassName'].'--'.$arrKindInfo['KindName'];
								break;
							}
						}
					}
				}
				$arrSpInfo['GameKind'] = $ClassName;
				$arrSpInfo['Display'] = $arrSpInfo['Target'] ? '' : 'hide';
				//道具使用场景
				foreach ($this->arrConfig['Place'] as $val)
				{					
					if($arrSpInfo['Place'] & $val['TypeID'])
						$Place .= $val['TypeName'].',';						
				}
				$arrSpInfo['Place'] = empty($Place) ? '' : substr($Place, 0,strlen($Place)-1);				
			}

			$SpInfo = join('|@|', $arrSpInfo);
			echo $SpInfo;
		}
	}
	/**
	 * 获取道具分类
	 */
	public function getSpClass()
	{		
		$TypeID = Utility::isNumeric('TypeID',$_POST);	
		$ClassID = Utility::isNumeric('ClassID',$_POST);	
		if($TypeID && $TypeID>0)
		{		
			$arrClass = $this->objStagePropertyBLL->getSpClass($TypeID,$this->SearchType,$this->SpClassLocked);
			if(is_array($arrClass) && count($arrClass)>0)
			{
				$strOption = '';				
				foreach ($arrClass as $val)
				{
					$selected = '';
					if($ClassID==$val['ClassID']) $selected = 'selected';						
					$strOption .= '<option value="'.$val['ClassID'].'" '.$selected.'>'.$val['CateName'].'</option>';
				}				
			}
		}	
		if(empty($strOption)) $strOption='<option value="0">请添加子类</option>';
		echo $strOption;
	}
	/**
	 * 读取指定分类下的道具
	 */
	public function getSpPublicList()
	{
		$ClassID = Utility::isNumeric('ClassID',$_POST);
		$SpID = Utility::isNumeric('SpID',$_POST);
		if($ClassID)
		{
			$arrSpPublicList = $this->objStagePropertyBLL->getSpPublicList($ClassID,1);
			if(is_array($arrSpPublicList) && count($arrSpPublicList)>0)
			{
				$strOption = '';
				foreach ($arrSpPublicList as $val)
				{
					if($val['Locked']) continue;
					$selected = '';
					if($SpID==$val['SpID']) $selected = 'selected';	
					$strOption .= '<option value="'.$val['SpID'].'" '.$selected.'>'.$val['GoodsName'].'</option>';
				}
			}
		}
		if(empty($strOption)) $strOption='<option value="0">暂无道具</option>';
		echo $strOption;
	}	
	
	/**
	 * 发布道具
	 */
	public function releaseSp()
	{
		$iResult = -9999;
		$arrParams['PublicSpID'] = Utility::isNumeric('PublicSpID',$_POST);
		$arrParams['Sex'] = Utility::isNumeric('Sex',$_POST);
		$arrParams['Level'] = Utility::isNumeric('Level',$_POST);
		$arrParams['VipID'] = Utility::isNumeric('VipID',$_POST);		
		$arrParams['SortID'] = Utility::isNumeric('SortID',$_POST);
		$arrParams['IsRecommend'] = Utility::isNumeric('IsRecommend',$_POST);
		$arrParams['Price'] = Utility::isNumeric('Price',$_POST);	
		$arrParams['SpID'] = Utility::isNumeric('SpID',$_POST);
		$arrParams['IconID'] = Utility::isNumeric('IconID',$_POST);
		$arrParams['StartTime'] = Utility::isNullOrEmpty('StartTime',$_POST);
		$arrParams['EndTime'] = Utility::isNullOrEmpty('EndTime',$_POST);
		$arrParams['MaxStockNum'] = $_POST['MaxStockNum'];//库存
		$arrParams['MaxBuyNum'] = Utility::isNumeric('MaxBuyNum',$_POST);
		if(!is_numeric($arrParams['MaxStockNum'])) $arrParams['MaxStockNum'] = -1;//如果库存未填写,默认-1(不限库存)

		if($arrParams['SpID'])
			$iResult = $this->objStagePropertyBLL->setSpRelease($arrParams);

		if($iResult==0)
			$msg='道具信息发布成功';
		elseif($iResult==-1)
			$msg='道具信息发布失败';
		else
			$msg='对不起,您提交的数据异常,请重试';
		
		echo $msg;
	}
	/**
	 * 删除游戏道具
	 * $iResult=0:成功;-1:失败;-9999:您提交的数据异常,请重试;
	 */
	public function delSpRelease()
	{
		$iResult = -9999;
		$PublicSpID = Utility::isNumeric('PublicSpID',$_POST);	
		if($PublicSpID && $PublicSpID>0)		
			$iResult = $this->objStagePropertyBLL->delSpRelease($PublicSpID);			

		if($iResult==0)
	 		$msg=$iResult;
	 	elseif($iResult==-1)	
	 		$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpRelease\')','道具删除失败','false','SpRelease',$this->arrConfig);	
		else 	
		 	$msg=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SpRelease\')','对不起,您提交的数据异常,请重试','false','SpRelease',$this->arrConfig);
	 	echo $msg;
	}
}
?>