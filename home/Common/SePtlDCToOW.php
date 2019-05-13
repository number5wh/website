<?php
// DC TO OW

//��Ҳ������� 
//UINT32 iResult; 					//���������0�ɹ���1ʧ��
function ProcessDWOperateAckRes($out_data)
{
	echo "ProcessDWOperateAckRes: <br />";
	$out_data_array = unpack('LiResult/', $out_data);
	print_r($out_data_array);
	echo "<br />";

	$out_array = $out_data_array;
	
	return $out_array;
}

//��ѯ��ֵ��״̬ 
//UINT32 iState; 					//���������0�ɹ���1ʧ��
function ProcessDWQueryRechargeCardStateRes($out_data)
{
	echo "ProcessDWQueryRechargeCardStateRes: <br />";
	$out_data_array = unpack('LiState/', $out_data);
	print_r($out_data_array);
	echo "<br />";

	$out_array = $out_data_array;
	
	return $out_array;
}


//��ѯ��ɫ��Ϸǩ����Ϣ 
//UINT32 iResult; 				//��ѯ�����0�ɹ���1ʧ��
//UINT32 iRoleID;					//��ɫID
//UINT32 iLastSignTime;			//�ϴ�ǩ��ʱ��
//UINT32 iLxCount;				//����ǩ������
//UINT32 iGameSignType;			//����ǩ����������
//UINT32 iGameSignNeedValue;		//����ǩ����������ֵ
//UINT32 iLastDayGameSignValue;	//������Ϸ����
//UINT32 iGameSignValue;			//������Ϸ����
//UINT32 iAwardCount;				//��������
//UINT32 aiAward[1];				//��������
function ProcessDWQueryRoleGameSignInfoRes($out_data)
{
	echo "ProcessDWQueryRoleGameSignInfoRes: <br />";
	$out_data_array = unpack('LiResult/LiRoleID/LiLastSignTime/LiLxCount/LiGameSignType/LiGameSignNeedValue/LiLastDayGameSignValue/LiGameSignValue/LiAwardCount/', $out_data);
	print_r($out_data_array);
	echo "<br />";

	for ($x=0; $x<$out_data_array[iAwardCount]; $x++)
	{
		echo "AwardInfo".($x + 1).":<br />";
		$out_data_iAward_array = unpack('x32/x'.($x*4).'/LiAward/', $out_data);

		print_r($out_data_iAward_array);
		echo "<br />";

		$out_data_array["AwardInfoList"][$x] = $out_data_iAward_array;
	}
	
	$out_array = $out_data_array;
	
	return $out_array;
}

//��ѯ��ɫ��Ϸǩ����Ϣ 
//UINT32		iCount;				//����
//SeCardChargeRateToOW akCardChargeRate[1];
	//UINT32 iType;
	//UINT32 iRate;
	//char szCardType[32];
function ProcessDWGetChargeInfoRes($out_data)
{
	echo "ProcessDWGetChargeInfoRes: <br />";
	$out_data_array = unpack('LiCount/', $out_data);
	print_r($out_data_array);
	echo "<br />";

	for ($x=0; $x<$out_data_array[iCount]; $x++)
	{
		echo "ChargeInfo".($x + 1).":<br />";
		$out_data_charge_info_array = unpack('x4/x'.($x*40).'/LiType/LiRate/a32szCardType/', $out_data);

		print_r($out_data_charge_info_array);
		echo "<br />";

		$out_data_array["ChargeInfoList"][$x] = $out_data_charge_info_array;
	}
	
	$out_array = $out_data_array;
	
	return $out_array;
}

//��ȡ��Ϸ������Ϣ 
//UINT32		iCount;				//����
//SeGameConfigValueToOW akGameConfig[1];
	//UINT32 iCfgType;
	//INT64 iCfgValue;
function ProcessDWGetGameConfigInfoRes($out_data)
{
	echo "ProcessDWGetGameConfigInfoRes: <br />";
	$out_data_array = unpack('LiCount/', $out_data);
	print_r($out_data_array);
	echo "<br />";

	for ($x=0; $x<$out_data_array[iCount]; $x++)
	{
		echo "GameConfigInfo".($x + 1).":<br />";
		$out_data_gameconfig_info_array = unpack('x4/x'.($x*12).'/LiCfgType/LiCfgValueL32/LiCfgValueH32/', $out_data);

		$out_data_gameconfig_info_array[iCfgValue] = MakeINT64Value($out_data_gameconfig_info_array[iCfgValueH32],$out_data_gameconfig_info_array[iCfgValueL32]);

		print_r($out_data_gameconfig_info_array);
		echo "<br />";

		$out_data_array["GameConfigInfoList"][$x] = $out_data_gameconfig_info_array;
	}
	
	$out_array = $out_data_array;
	
	return $out_array;
}

//��ȡ��¼�������б� 
//UINT32		iCount;				//����
//SeGameServerInfoCacheToOW akServerInfo[1];
	//UINT32 iServerID;
	//UINT32 iServerType;
	//UINT32 iServID;
	//UINT32 bLocked;
	//char szServerName[20];
	//char szServerIP[256];
	//char szLANServerIP[32];
	//char szServerPort[16];
	//char szIntro[256];
	//char szLogin[32];
	//char szPass[64];
	//char szAppName[32];
	//char szIP[128];
function ProcessDWGetServerListRes($out_data)
{
	echo "ProcessDWGetServerListRes: <br />";
	$out_data_array = unpack('LiCount/', $out_data);
	print_r($out_data_array);
	echo "<br />";

	for ($x=0; $x<$out_data_array[iCount]; $x++)
	{
		echo "ServerInfo".($x + 1).":<br />";
		$out_data_server_info_array = unpack('x4/x'.($x*852).'/LiServerID/LiServerType/LiServID/LbLocked/a20szServerName/a256szServerIP/a32szLANServerIP/a16szServerPort/a256szIntro/a32szLogin/a64szPass/a32szAppName/a128szIP/', $out_data);

		print_r($out_data_server_info_array);
		echo "<br />";

		$out_data_array["ServerInfoList"][$x] = $out_data_server_info_array;
	}
	
	$out_array = $out_data_array;
	
	return $out_array;
}

//��ȡ��Ϸ�汾��Ϣ 
//UINT32		iCount;				//����
//SeGameVersionCacheToOW akGameVersion[1];
	//UINT32 iVerID;
	//UINT32 iVerType;
	//UINT32 iKindID;
	//UINT32 iServerID;
	//UINT32 iFileCategory;
	//UINT32 iVersion;
	//UINT32 iLastUpdateTime;
	//char szFileName[32];
	//char szFileURL[50];
	//char szLocalPath[50];
function ProcessDWGetGameVersionRes($out_data)
{
	echo "ProcessDWGetGameVersionRes: <br />";
	$out_data_array = unpack('LiCount/', $out_data);
	print_r($out_data_array);
	echo "<br />";

	for ($x=0; $x<$out_data_array[iCount]; $x++)
	{
		echo "GameVersion".($x + 1).":<br />";
		$out_data_game_version_info_array = unpack('x4/x'.($x*160).'/LiVerID/LiVerType/LiKindID/LiServerID/LiFileCategory/LiVersion/LiLastUpdateTime/a32szFileName/a50szFileURL/a50szLocalPath/', $out_data);

		print_r($out_data_game_version_info_array);
		echo "<br />";

		$out_data_array["GameVersionList"][$x] = $out_data_game_version_info_array;
	}
	
	$out_array = $out_data_array;
	
	return $out_array;
}

//��ȡ��׿�汾��Ϣ 
//UINT32		iCount;				//����
//SeAndroidVersionCacheToOW akAndroidVersion[1];
	//UINT32 iLowVersion;
	//UINT32 iHighVersion;
	//UINT32 iLastUpdateTime;
	//UINT32 iServerID;
	//UINT32 iVerID;
	//char szFileName[256];
	//char szFileURL[256];
function ProcessDWGetAndroidVersionRes($out_data)
{
	echo "ProcessDWGetAndroidVersionRes: <br />";
	$out_data_array = unpack('LiCount/', $out_data);
	print_r($out_data_array);
	echo "<br />";

	for ($x=0; $x<$out_data_array[iCount]; $x++)
	{
		echo "AndroidVersion".($x + 1).":<br />";
		$out_data_android_version_info_array = unpack('x4/x'.($x*532).'/LiLowVersion/LiHighVersion/LiLastUpdateTime/LiServerID/LiVerID/a256szFileName/a256szFileURL/', $out_data);

		print_r($out_data_android_version_info_array);
		echo "<br />";

		$out_data_array["AndroidVersionList"][$x] = $out_data_android_version_info_array;
	}
	
	$out_array = $out_data_array;
	
	return $out_array;
}

//��ȡ�����ʽ���Ϣ���� 
//char	szMsg[20][128];
function ProcessDWGetHomeCaijinMsgRes($out_data)
{

	for ($x=0; $x<20; $x++)
	{
		$out_data_caijin_msg_array = unpack('x'.($x*128).'/a128szMsg/', $out_data);
		fitStr($out_data_caijin_msg_array['szMsg']);
		$out_data_array["CaijinMsg"][$x] = $out_data_caijin_msg_array;
	}
	$out_array = $out_data_array;
	
	return $out_array;
}




