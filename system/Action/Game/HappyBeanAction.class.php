<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
require_once ROOT_PATH . 'Link/QuerySuperUserList.php';
class HappyBeanAction extends PageBase {

    private $objMasterBLL = null;
    public function __construct() {
        $this->arrConfig = unserialize(SYS_CONFIG);
    }

    public function getPagerHappyBeanSortTop100() {

        $roleid = $_GET['roleid'];
        if($roleid== ''){
            $roleid =0;
        }
        $arrUserList = null;
        $this->objMasterBLL = new MasterBLL();
        $arrUserList = $this->objMasterBLL->getUserGameRank($roleid);
        //var_dump($arrUserList);
//		$res1 = array();
//		$res2 = array();
//		$roleIdArray = array();
//		if ($arrUserList) {
//			foreach ($arrUserList as $val) {
//				//if ($val['RoleID'] != 1039187 && $val['RoleID'] != 800036 && $val['RoleID'] != 1303689) {
//					if ($val['TotalMoney'] >=0 ) {//6000000000
//						$val['TotalMoney'] = 0;
//						array_push($res1, array($val['RoleID'], Utility::gb2312ToUtf8($val['RoleName']), $val['TotalMoney']));
//					} else {
//						array_push($res2, array($val['RoleID'], Utility::gb2312ToUtf8($val['RoleName']), $val['TotalMoney']));
//					}
//
//					array_push($roleIdArray, $val['RoleID']);
//
//				//}
//			}
//		}

//		$cnt = count($res1);
//		if ($cnt > 0) {
//			shuffle($res1);
//			$res = array_merge($res1, $res2);
//		} else {
//			$res = $res2;
//		}



        //$out_array = DSQuerySuperRoleList($roleIdArray);

        $ret = array();
        $self = array_pop($arrUserList);
        $selfRank = '未上榜';

        array_multisort(Utility::array_column($arrUserList,'totalmoney'),SORT_DESC,$arrUserList);
        $index = 0;

        foreach ($arrUserList as &$v) {
            $v['rank'] = ++$index;
            if ($self['RoleID'] == $v['RoleID']) {
                $selfRank = $index;
            }
        }
        unset($v);
        $self['rank'] = $selfRank;
        $arrUserList[] = $self;
        if ($arrUserList) {
            foreach ($arrUserList as $value) {
                $arrTemp = [];
                foreach ($value as $k=> $v) {
//                    if ($k != "RoleID") {
                        array_push($arrTemp, $v);
//                    }
                }
                array_push($ret, $arrTemp);
            }
        }

        //$ret = [[1,341102,"肖晗昱",3],[2,341102,"肖晗昱",3]];

        echo  str_replace('null','1',json_encode($ret, JSON_UNESCAPED_UNICODE));
    }
}
?>