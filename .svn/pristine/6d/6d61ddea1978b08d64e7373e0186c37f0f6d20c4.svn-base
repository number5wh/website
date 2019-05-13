<?php
// DC TO OW

//��Ҳ������� 
//UINT32 iResult; 					//�������0�ɹ���1ʧ��
function ProcessDWOperateAckRes($out_data)
{
	//echo "ProcessDWOperateAckRes: <br />";
	$out_data_array = unpack('LiResult/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;
	
	return $out_array;
}

// 查询充值卡状态返回
//UINT32		iState; 				//操作结果，0成功，
function ProcessDWQueryRechargeCardRes($out_data)
{
    //echo "ProcessDMRoleRightAckRes: <br />";
    $out_data_array = unpack('LiState/', $out_data);
    //print_r($out_data_array);
    //echo "<br />";

    $out_array = $out_data_array;

    return $out_array;
}


//查询充值比例信息
//UINT32		iCount;				//数量
//SeCardChargeRateToOW akCardChargeRate[1];
//UINT32 iType;
//UINT32 iRate;
//char szCardType[32];
function ProcessDWGetChargeInfoRes($out_data)
{
    //echo "ProcessDWGetChargeInfoRes: <br />";
    $out_data_array = unpack('LiCount/', $out_data);
    //print_r($out_data_array);
    //echo "<br />";

    for ($x=0; $x<$out_data_array['iCount']; $x++)
    {
       // echo "ChargeInfo".($x + 1).":<br />";
        $out_data_charge_info_array = unpack('x4/x'.($x*40).'/LiType/LiRate/a32szCardType/', $out_data);

        //print_r($out_data_charge_info_array);
      //  echo "<br />";
        fitStr($out_data_charge_info_array['szCardType']);
		$out_data_array["ChargeInfoList"][$x] = $out_data_charge_info_array;
    }
    $out_array = $out_data_array;
	return $out_array;
}

//获取游戏配置信息
//UINT32		iCount;				//数量
//SeGameConfigValueToOW akGameConfig[1];
//UINT32 iCfgType;
//INT64 iCfgValue;
function ProcessDWGetGameConfigInfoRes($out_data)
{
    //echo "ProcessDWGetGameConfigInfoRes: <br />";
    $out_data_array = unpack('LiCount/', $out_data);
    //print_r($out_data_array);
    //echo "<br />";

    for ($x=0; $x<$out_data_array['iCount']; $x++)
    {
        //echo "GameConfigInfo".($x + 1).":<br />";
        $out_data_gameconfig_info_array = unpack('x4/x'.($x*12).'/LiCfgType/LiCfgValueL32/LiCfgValueH32/', $out_data);

        $out_data_gameconfig_info_array['iCfgValue'] = MakeINT64Value($out_data_gameconfig_info_array['iCfgValueH32'],$out_data_gameconfig_info_array['iCfgValueL32']);

        //print_r($out_data_gameconfig_info_array);
        //echo "<br />";

		$out_data_array["GameConfigInfoList"][$x] = $out_data_gameconfig_info_array;
}

	$out_array = $out_data_array;

	return $out_array;
}