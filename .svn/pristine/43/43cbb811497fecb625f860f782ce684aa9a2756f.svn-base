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