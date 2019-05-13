<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/StagePropertyBLL.class.php';


class GameMatchAction extends PageBase
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
		//游戏种类列表
		$arrResult = $this->getPagerMatch();
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'MatchList'=>$arrResult['MatchList']); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameMatchList.html');
	}	 
	
	/**
	 * 分页
	 * @author xlj
	 */
	public function getPagerMatch()
	{
		$strWhere = '';		
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrParam['fields']='MatchTypeID,MatchName,TypeID';
		$arrParam['tableName']='T_GameMatch';
		$arrParam['where']=$strWhere;
		$arrParam['order']='MatchTypeID DESC';
		$arrParam['pagesize']=20;
		
		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrMatchList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		
		if(is_array($arrMatchList) && count($arrMatchList)>0)
		{
			$iCount = 0;
			foreach ($arrMatchList as $val)
			{
				$arrMatchList[$iCount]['MatchName']=Utility::gb2312ToUtf8($val['MatchName']);
				$arrTypeList = $this->arrConfig['MatchMode'];
				foreach ($arrTypeList as $list) {
					if($list['TypeID'] == $val['TypeID']){
						$arrMatchList[$iCount]['MatchTpye']=$list['MatchModeName'];
						break;
					}
				}
				$iCount++;
			}
		}
		$arrResult = array('Page'=>$Page,'MatchList'=>$arrMatchList);
		return $arrResult;
		
	}
	/**
	 * 分页读取比赛类型
	 * @author xlj
	 */
	public function getPagerMatchList()
	{
		$arrResult = $this->getPagerMatch();
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'MatchList'=>$arrResult['MatchList']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameMatchListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 设置比赛规则
	 */
	public function showSetMatchRuleHtml()
	{
		$MatchTypeID = Utility::isNumeric('MatchTypeID',$_GET);
		$TypeID = Utility::isNumeric('TypeID',$_GET);
		if($this->arrConfig['MatchMode'][0]['TypeID'] == $TypeID)//66人斗地主模式
			$this->showSetMatchRuleHtml1($MatchTypeID);
	}
	
	/**
	 * 显示设置"66人斗地主模式"比赛规则页面
	 */
	public function showSetMatchRuleHtml1($MatchTypeID)
	{		
		$arrMatchInfo = $this->objMasterBLL->getGameMatchInfo1($MatchTypeID);
		if(!$arrMatchInfo) $arrMatchInfo['MatchTypeID'] = $MatchTypeID;
		$arrTags=array('Match'=>$arrMatchInfo); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameMatchRuleEdit.html');
	}	
	/**
	 * 配置比赛规则信息(66人斗地主模式的比赛)
	 */
	public function addGameMatch1()
	{		
		$iResult = -1;
		//比赛规则
		$arrParams['MatchTypeID']=Utility::isNumeric('MatchTypeID',$_POST);		
		$arrParams['MaxNumber']=Utility::isNumeric('MaxNumber',$_POST);
		$arrParams['StopNumber']=Utility::isNumeric('StopNumber',$_POST);
		$arrParams['TopNumber']=Utility::isNumeric('TopNumber',$_POST);
		$arrParams['BaseScore1']=Utility::isNumeric('BaseScore1',$_POST);
		$arrParams['BaseBumber1']=Utility::isNumeric('BaseBumber1',$_POST);
		$arrParams['BaseBumberIncrease']=Utility::isNumeric('BaseBumberIncrease',$_POST);
		$arrParams['GameOverNumber']=is_numeric($_POST['GameOverNumber']) || is_float($_POST['GameOverNumber']) ? $_POST['GameOverNumber'] : 0;
		$arrParams['BaseScore2']=Utility::isNumeric('BaseScore2',$_POST);
		$arrParams['BaseBumber2']=Utility::isNumeric('BaseBumber2',$_POST);
		$arrParams['GameRounds']=Utility::isNumeric('GameRounds',$_POST);
		$arrParams['GameTimes']=Utility::isNumeric('GameTimes',$_POST);
		$arrParams['GameTitle']=Utility::isNullOrEmpty('GameTitle',$_POST) ? $_POST['GameTitle'] : '';
		$arrParams['PageUrl']=Utility::isNullOrEmpty('PageUrl',$_POST) ? $_POST['PageUrl'] : '';
		$arrParams['MatchRule1']=Utility::isNullOrEmpty('MatchRule1',$_POST) ? $_POST['MatchRule1'] : '';
		$arrParams['MatchRule2']=Utility::isNullOrEmpty('MatchRule2',$_POST) ? $_POST['MatchRule2'] : '';
		$iResult = $this->objMasterBLL->addGameMatch1($arrParams);
		echo $iResult;
	}
	
	/**
	 * 获取添加赛事页面
	 * @author blj
	 */
	public function showAddGameMatchHtml()
	{
		$MatchTypeID = Utility::isNumeric('MatchTypeID',$_POST);
		$MatchInfo = null;
		if($MatchTypeID>0)
		{
			$arrReturn = $this->objMasterBLL->getGameMatchByID($MatchTypeID);
		
			if(is_array($arrReturn) && count($arrReturn)>0){
				$MatchInfo['MatchName'] = Utility::gb2312ToUtf8($arrReturn['MatchName']);
				$MatchInfo['MatchType'] = $arrReturn['TypeID'];
				$MatchInfo['MatchTypeID'] = $MatchTypeID;
				$MatchInfo['MID'] = $arrReturn['MID'];;
			}
		}
		
		$arrTags=array('MatchMode'=>$this->arrConfig['MatchMode'],
					   'MatchInfo'=>$MatchInfo);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameMatchEdit.html');
		echo $html;
	}
	
	/**
	 * 添加赛事
	 * @author blj
	 */
	public function addGameMatch()
	{
		$iResult = -99;
		$MatchName = Utility::isNullOrEmpty('MatchName',$_POST);
		$TypeID = Utility::isNumeric('TypeID',$_POST);
		$MatchTypeID = Utility::isNumeric('MatchTypeID',$_POST);
		$MID = Utility::isNumeric('MID',$_POST)?$_POST['MID']:0;
		if($MatchName && $TypeID && $MatchTypeID && $TypeID>0 && $MatchTypeID>0)
		{
			$iResult = -3;
			$arrResult = $this->objMasterBLL->addGameMatch($MatchTypeID,$MatchName,$TypeID,$MID);
			if(is_array($arrResult) && count($arrResult)>0)
			{
				$iResult = $arrResult['iResult'];
			}
		}
		echo $iResult;
	}
	
	/**
	 * 删除比赛信息
	 * @author blj
	 */
	public function deleteGameMatch()
	{
		$iResult = -99;
		$MatchTypeID = Utility::isNumeric('MatchTypeID',$_POST);
		if($MatchTypeID>0)
		{
			$iResult = -2;
			$arrResult = $this->objMasterBLL->deleteGameMatch($MatchTypeID);
			if(is_array($arrResult) && count($arrResult)>0)
			{
				$iResult = $arrResult['iResult'];
			}
		}
		echo $iResult;
	}
	
	/**
	 * 设置比赛奖品
	 * @author blj
	 */
	public function showSetMatchPrizeHtml()
	{
		$nextLevel = 1;
		$matchPrizeList = null;
		$MatchTypeID = Utility::isNumeric('MatchTypeID',$_GET);
		$arrReturn = $this->objMasterBLL->getGameMatchPrize($MatchTypeID);
		if(is_array($arrReturn) && count($arrReturn)>0)
		{
			$stagePropertyBLL = new StagePropertyBLL();
			for($i=0;$i<count($arrReturn);$i++)
			{
				foreach ($arrReturn as $val)
				{
					if($val['Level'] == $i+1){				
						$matchPrizeList[$i]['Level'] = $i+1;																	
						$matchPrizeList[$i]['LevelName'] = $this->getChineseNumber($i+1);
						$key = trim($val['WinType']);
						$matchPrizeList[$i]['PrizeType'] = $this->arrConfig['PrizeType'][$key];
						if($val['SpID']>0){				
							//获取道具名称						
							$arrResult = $stagePropertyBLL->getSPDetailInfo($val['SpID']);
							$matchPrizeList[$i]['Rank'] = $val['RankStart']==$val['RankEnd']?$val['RankStart']:$val['RankStart'].'~'.$val['RankEnd'];
							if(isset($matchPrizeList[$i]['Prize'])){
								$matchPrizeList[$i]['Prize'] .= '<br/>'.Utility::gb2312ToUtf8($arrResult['GoodsName'])."*".$val['iNumber'];
							}else{
								$matchPrizeList[$i]['Prize'] = Utility::gb2312ToUtf8($arrResult['GoodsName'])."*".$val['iNumber'];
							}
						}elseif($val['SpID']==-1){
							$matchPrizeList[$i]['PrizeName'] = Utility::gb2312ToUtf8($val['PrizeName']);
						}elseif($val['SpID']==0){
							$matchPrizeList[$i]['ScoreRank'] = $val['RankStart']==$val['RankEnd']?$val['RankStart']:$val['RankStart'].'~'.$val['RankEnd'];
							$matchPrizeList[$i]['ScoreNumber'] = $val['iNumber'];
						}
						$nextLevel = $i+2;
					}
				}				
			}
		}
		$arrTags=array('matchPrizeList'=>$matchPrizeList,
					   'matchTypeID'=>$MatchTypeID,
					   'nextLevel'=>$nextLevel);
		
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/GameMatchPrizeManage.html');
	}
	
	/**
	 * 获取奖品编辑页面
	 * @author blj
	 */
	public function showAddMatchPrizeHtml()
	{
		$MatchTypeID = Utility::isNumeric('MatchTypeID',$_POST);
		$Level = Utility::isNumeric('Level',$_POST);
		$flag = Utility::isNumeric('flag',$_POST);
		$MatchLevelInfo = null;
		$arrSpList = null;
		if($flag){
			$arrReturn = $this->objMasterBLL->getMatchPrizeByLevel($MatchTypeID,$Level);
			if(is_array($arrReturn) && count($arrReturn)>0)
			{
				$i = 0;
				$stagePropertyBLL = new StagePropertyBLL();
				foreach ($arrReturn as $val)
				{					
					if($val['SpID']>0)
					{
						//获取道具名称						
						$arrResult = $stagePropertyBLL->getSPDetailInfo($val['SpID']);
						$MatchLevelInfo['RankStart'] = $val['RankStart'];
						$MatchLevelInfo['RankEnd'] = $val['RankEnd'];
						$arrSpList[$i]['SpID'] = $val['SpID'];
						$arrSpList[$i]['Number'] = $val['iNumber'];
						$arrSpList[$i]['GoodsName'] = Utility::gb2312ToUtf8($arrResult['GoodsName']);
						$i++;
					}elseif($val['iNumber']>0){
						$MatchLevelInfo['ScoreRankStart'] = $val['RankStart'];
						$MatchLevelInfo['ScoreRankEnd'] = $val['RankEnd'];
						$MatchLevelInfo['ScoreNumber'] = $val['iNumber'];
					}elseif($val['PrizeName'] != ''){
						$MatchLevelInfo['PrizeName'] = Utility::gb2312ToUtf8($val['PrizeName']);
					}
					$MatchLevelInfo['PrizeType'] = $val['WinType'];
				}
			}
		}
		$arrTags=array('MatchLevelInfo'=>$MatchLevelInfo,
					   'MatchTypeID'=>$MatchTypeID,
					   'Level'=>$Level,
					   'SpTypeList'=>$this->arrConfig['Category'],
					   'GiftSpList'=>$arrSpList,
					   'LevelName'=>$this->getChineseNumber($Level),
					   'PrizeTypeList'=>$this->arrConfig['PrizeType']);
		
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/GameMatchPrizeEdit.html');
		echo $html;
	}
	
	/**
	 * 根据等级添加比赛奖品
	 * @author blj
	 */
	public function addGameMatchPrize()
	{
		$iResult = -99;
		$MatchTypeID = Utility::isNumeric('matchTypeID',$_POST);
		$Level = Utility::isNumeric('level',$_POST);
		$RankStart = Utility::isNumeric('rkBegin',$_POST);
		$RankEnd = Utility::isNumeric('rkEnd',$_POST);
		$SpIDList = Utility::isNullOrEmpty('SpIDList',$_POST);
		$NumberList = Utility::isNullOrEmpty('spNumList',$_POST);
		
		$sRankStart = Utility::isNumeric('srBegin',$_POST);
		$sRankEnd = Utility::isNumeric('srEnd',$_POST);
		$sNumber = Utility::isNumeric('sNumber',$_POST);
		
		$prizeType = isset($_POST['prizeType'])?$_POST['prizeType']:'';
		$prizeName = Utility::isNullOrEmpty('prizeName',$_POST);
		
		$arrSpID = null;
		$arrNumber = null;
		if($SpIDList)$arrSpID = explode(',',$SpIDList);
		if($NumberList)$arrNumber = explode(',',$NumberList);
		
		if($MatchTypeID>0 && $Level>0 && $prizeType!='')
		{
			$this->objMasterBLL->deleteGameMatchPrize($MatchTypeID,$Level);
			if($RankStart && $RankEnd && $RankStart<=$RankEnd && is_array($arrSpID) && is_array($arrNumber) && count($arrSpID)==count($arrNumber))
			{
				$i = 0;
				foreach ($arrSpID as $v)
				{
					$arrReturn = $this->objMasterBLL->addGameMatchPrize($MatchTypeID,$RankStart,$RankEnd,$Level,$v,$arrNumber[$i],$prizeType);
					$i++;
				}
			}
			
			if($sRankStart && $sRankEnd && $sRankStart<=$sRankEnd && $sNumber && $sNumber>0)
			{		
				$arrReturn = $this->objMasterBLL->addGameMatchPrize($MatchTypeID,$sRankStart,$sRankEnd,$Level,0,$sNumber,$prizeType);
			}
			
			if($prizeName)
			{
				$arrReturn = $this->objMasterBLL->addGameMatchPrize($MatchTypeID,0,0,$Level,-1,0,$prizeType,$prizeName);
			}
			
			$iResult = isset($arrReturn['iResult'])?$arrReturn['iResult']:-2;
		}
		
		echo $iResult;
	}
	
	/**
	 * 删除比赛奖品
	 * @author blj
	 */
	public function deleteMatchPrize()
	{
		$iResult = -99;
		$MatchTypeID = Utility::isNumeric('MatchTypeID',$_POST);
		$Level = Utility::isNumeric('Level',$_POST);
		
		if($MatchTypeID>0 && $Level>0)
		{
			$iResult = -2;
			$arrReturn = $this->objMasterBLL->deleteGameMatchPrize($MatchTypeID,$Level);
			if(is_array($arrReturn) && count($arrReturn)>0){
				$iResult = $arrReturn['iResult'];
			}
		}
		
		echo $iResult;
	}
	
	public function getChineseNumber($i)
	{
		$arrNum = array("零","一","二","三","四","五","六","七","八","九","十");
		if($i<11){
			return $arrNum[$i]."等奖";
		}elseif($i<20){
			return $arrNum[10].$arrNum[$i-10]."等奖";
		}
	}
	
	/**
	 * 读取指定类别下的道具
	 */
	public function getSpPublicList()	
	{
		$strSpList = '';
		$ClassID = Utility::isNumeric('ClassID',$_POST);	
		if($ClassID && $ClassID>0)
		{
			$arrSpList = $this->objStagePropertyBLL->getSpPublicList($ClassID,1);
			if(is_array($arrSpList) && count($arrSpList)>0)
			{
				foreach ($arrSpList as $val)
				{
					if($val['Locked']) continue;
					$strSpList .= '<div class="left" id="'. $val['SpID'] .'" TypeID="" title="点击选择">' . $val['GoodsName'] . '</div>';
				}
			}
		}
		echo $strSpList;
	}
	
}
?>