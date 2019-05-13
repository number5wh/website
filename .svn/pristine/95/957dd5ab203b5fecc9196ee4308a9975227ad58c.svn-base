<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class GameNodeAction extends PageBase
{	
	private $objMasterBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();		
	}
	public function index()
	{
		$arrNode = $this->getGameTypeList(0);
		$arrTags = array('GameNodeList'=>$arrNode);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameNodeList.html');
	}
	/**
	 * 读取节点列表
	 */
	public function getGameType()
	{
		$strNode = '';
		$iTypeID = Utility::isNumeric('TypeID',$_POST);
		$arrNode = $this->getGameTypeList($iTypeID);
		if(is_array($arrNode) && count($arrNode)>0)
		{
			foreach ($arrNode as $val)
			{
				if(!empty($val['ChildID']))
					$strNode .= '<ul class="subNode_'.$iTypeID.'"><li class="'.($val['Locked']?'locked':'').'"><span class="folder spanNode_'.$iTypeID.'" id="'.$val['TypeID'].'">'.$val['NodeName'].'</span></li></ul>';
				else 
					$strNode .= '<ul class="subNode_'.$iTypeID.'"><li class="'.($val['Locked']?'locked':'').'"><span class="file spanNode_'.$iTypeID.'" id="'.$val['TypeID'].'">'.$val['NodeName'].'</span></li></ul>';
			}
		}
		echo $strNode;
	}

	/**
	 * 读取节点列表
	 * @param $iParentID 父级ID
	 */
	private function getGameTypeList($iParentID)
	{
		$Locked = -1;
		$arrGameType = $this->objMasterBLL->getGameTypeList($Locked);
        //var_dump($arrGameType);
        //var_dump($arrGameType);
        //var_dump($iParentID);
        $arrNode = array();
		if(is_array($arrGameType) && count($arrGameType)>0)
		{
			$iCount = 0;
			foreach ($arrGameType as $val)
			{
				if($val['ParentId']==$iParentID)
				{
					$arrNode[$iCount]['TypeID']=$val['TypeID'];
					$arrNode[$iCount]['NodeName']=Utility::gb2312ToUtf8($val['NodeName']);
					$arrNode[$iCount]['ChildID']=$val['ChildID'];
                    $arrNode[$iCount]['SortID'] = $val['SortID'];
                    $arrNode[$iCount]['Locked'] = $val['Locked'];
                    $arrNode[$iCount]['ParentID'] = $val['ParentId'];
					$iCount++;
				}
			}
		}
        //var_dump($arrNode);
		if(!isset($arrNode)) $arrNode=null;

        usort($arrNode,Utility::buildSortor('SortID'));
       // var_dump($arrNode);
		return $arrNode;
	}
	/**
	 * 显示复制游戏节点弹出层
	 */
	public function showCopyGameType()
	{
		$Locked = 0;
		$ParentNode = Utility::isNullOrEmpty('ParentNode',$_POST);
		$TypeID = Utility::isNumeric('TypeID',$_POST);
		$Action = Utility::isNullOrEmpty('Action',$_POST);
		//标签列表
		$arrTagClass = $this->objMasterBLL->getTagClassList($Locked);
		//样式
		$arrStyle = $this->objMasterBLL->getStyleSheetList($Locked);
		//节点信息
		$GameNode = $this->objMasterBLL->getGameTypeInfo($TypeID);
		$GameNode['Action']=$Action;
		$GameNode['TypeID']=$TypeID;
		$arrTags = array('NodeTypeList'=>$this->arrConfig['NodeType'],
				'GameNode'=>$GameNode,
				'TagClassList'=>empty($arrTagClass) ? null : $arrTagClass,
				'StyleSheetList'=>empty($arrStyle) ? null : $arrStyle,
				'TagID'=>0,
				'GameKindClassList'=>$this->arrConfig['GameKindClass'],
		);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameNodeCopy.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
	
		echo $html;
	}
	/**
	 * 显示添加游戏节点弹出层
	 */
	public function showAddGameType()
	{
		$Locked = 0;
		$ParentNode = Utility::isNullOrEmpty('ParentNode',$_POST);
		$TypeID = Utility::isNumeric('TypeID',$_POST);
		$Action = Utility::isNullOrEmpty('Action',$_POST);
		//标签列表
		$arrTagClass = $this->objMasterBLL->getTagClassList($Locked);
		//样式
		$arrStyle = $this->objMasterBLL->getStyleSheetList($Locked);
		//节点信息
		if($Action=='Modi')
		{		
			$GameNode = $this->objMasterBLL->getGameTypeInfo($TypeID);
			$GameNode['Action']=$Action;
			$GameNode['TypeID']=$TypeID;
		}
		else
		{
			$GameNode = array('ParentNode'=>$ParentNode,'TypeID'=>$TypeID,'Action'=>$Action,'KindID'=>0);
		}
		$ParentNodeList = $this->objMasterBLL->getParentNode();
		$arrTags = array('NodeTypeList'=>$this->arrConfig['NodeType'],
						 'GameNode'=>$GameNode,
						 'TagClassList'=>empty($arrTagClass) ? null : $arrTagClass,
						 'StyleSheetList'=>empty($arrStyle) ? null : $arrStyle,
						 'TagID'=>0,
						 'GameKindClassList'=>$this->arrConfig['GameKindClass'],
		                 'ParentNodeList'=>$ParentNodeList,
						);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameNodeEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}	
	/**
	 * 添加游戏节点
	 */
	public function addGameType()
	{
		$arrParams['NodeName'] = Utility::isNullOrEmpty('NodeName',$_POST);
		$arrParams['NodeType'] = Utility::isNumeric('NodeType',$_POST);
		$arrParams['SortID'] = Utility::isNumeric('SortID',$_POST);
		$arrParams['Locked'] = Utility::isNumeric('Locked',$_POST);
		$arrParams['TagID'] = Utility::isNumeric('TagID',$_POST);
		$arrParams['StyleID'] = Utility::isNumeric('StyleID',$_POST);
		$arrParams['Action'] = Utility::isNullOrEmpty('Action',$_POST);
		$arrParams['TypeID'] = Utility::isNumeric('TypeID',$_POST);
		$arrParams['KindID'] = Utility::isNumeric('KindID',$_POST);
		$arrParams['RoomID'] = Utility::isNumeric('RoomID',$_POST) ? $_POST['RoomID'] : 0;
		$arrParams['URL'] = Utility::isNullOrEmpty('Url',$_POST);
        $arrParams['ParentId'] = Utility::isNullOrEmpty('ParentId', $_POST);
		if($arrParams['NodeName'] && $arrParams['NodeType'] && $arrParams['SortID'])
		{
			if($arrParams['NodeType']!=3 && $arrParams['NodeType']!=5) $arrParams['KindID'] = 0; 
			$iResult = $this->objMasterBLL->addGameType($arrParams);
			if($iResult==0)
				$msg='游戏节点设置成功';
			else
				$msg='游戏节点设置失败';
		}
		else
			$msg='您提交的数据异常,请重试';
		echo $msg;
	}
	/**
	 * 删除游戏节点
	 */
	public function delGameType()
	{
		$iTypeID = Utility::isNumeric('TypeID',$_POST);
		if($iTypeID)
		{
			$result = $this->objMasterBLL->delGameType($iTypeID);
			if($result==-1)
				$result=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameNode\')','游戏节点设置失败','false','GameRoom',$this->arrConfig);
			elseif($result==-3)
				$result=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameNode\')','该节点含有子节点,请先删除子节点','false','GameRoom',$this->arrConfig);
		}
		else 
			$result=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'GameNode\')','对不起,您提交的数据异常,请重试','false','GameRoom',$this->arrConfig);
		echo $result;
	}
	/**
	 * 读取游戏种类
	 */
	public function getGameKind()
	{
		$Locked = 0;
		$strRes = '';
		$iClassID = Utility::isNumeric('GameKindClass',$_POST);
		$iCurKindID = Utility::isNumeric('CurKindID',$_POST);
		$iTypeID = Utility::isNumeric('TypeID',$_POST);		
		$iRoomID = Utility::isNumeric('RoomID',$_POST);		
		//指定类型下的游戏列表
		$arrKindList = $this->objMasterBLL->getGameKindList($iClassID,$Locked);		
		if(is_array($arrKindList) && count($arrKindList)>0)
		{			
			foreach ($arrKindList as $val)
			{
				$selected = '';
				if($iCurKindID==$val['KindID']) $selected = 'selected';
				$strRes .= '<option value="'.$val['KindID'].'" '.$selected.'>'.$val['KindName'].'</option>';
			}
			if($iCurKindID==0) $iCurKindID=$arrKindList[0]['KindID'];
		}
		else 
			$strRes = '<option value="0">请选择游戏</option>';
		//指定游戏下的房间列表
		$strOption = $this->getGameRoomList($iCurKindID,$iRoomID);
		echo json_encode(array('KindList'=>$strRes,'RoomList'=>$strOption));
		//echo $strRes;
	}
	/**
	 * 读取房间列表
	 */
	public function getGameRoomList($iCurKindID,$iRoomID)
	{
		$strOption = '';
		//指定游戏下的房间列表
		if($iCurKindID>0)
		{
			$arrRoomList = $this->objMasterBLL->getGameRoomList($iCurKindID,1);
			if(is_array($arrRoomList) && count($arrRoomList)>0)
			{
				foreach ($arrRoomList as $val)
				{
					$selected = '';
					if($iRoomID==$val['RoomID']) $selected = 'selected';
					$strOption .= '<option value="'.$val['RoomID'].'" '.$selected.'>'.$val['RoomName'].'</option>';
				}
			}
		}
		if(empty($strOption)) $strOption = '<option value="0">请配置房间</option>';
		return $strOption;
	}
	/**
	 * 读取房间列表
	 */
	public function getRoomList()
	{
		$iCurKindID = Utility::isNumeric('CurKindID',$_POST);
		$iRoomID = Utility::isNumeric('RoomID',$_POST);		
		//指定游戏下的房间列表
		$strOption = $this->getGameRoomList($iCurKindID,$iRoomID);
		echo $strOption;
	}
}
?>