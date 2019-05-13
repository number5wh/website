<?php
// AS TO OW

//��ѯ��ҷ���
//char		szLoginName[64]; 			//��ɫ��string  (length=3)
//char		szMobilePhone[12]; 		//�ֻ���� string '0' (length=1)
//char		szQQ[12]; 				//QQ�� string '' (length=0)
//UINT32		iAddTime; 			//ע��ʱ�� string '2015-08-31 10:01:20' (length=10)
function ProcessAWGetAccountInfoRes($out_data)
{
	//echo "ProcessAWGetAccountInfoRes: <br />";
	$out_data_array = unpack('a64szLoginName/a12szMobilePhone/a12szQQ/LiAddTime/', $out_data);
	/*print_r($out_data_array);
	echo "<br />";*/

	$out_array = $out_data_array;
	fitStr($out_array['szLoginName']);
	
	return $out_array;
}

//��Ҳ������� 
//UINT32 iResult; 					//�������0�ɹ���1ʧ��
function ProcessAWOperateAckRes($out_data)
{
	//echo "ProcessAWOperateAckRes: <br />";
	$out_data_array = unpack('LiResult/', $out_data);
	/*print_r($out_data_array);
	echo "<br />";*/

	$out_array = $out_data_array;
	
	return $out_array;
}
/* //获取验证码返回
//UINT32 iCode; 						//验证码，[1000,9999]有验证码，0无验证码
function ProcessAWGetSecCodeRes($out_data)
{
    //echo "ProcessAWGetSeccodeRes: <br />";
    $out_data_array = unpack('LiCode/', $out_data);
    //print_r($out_data_array);
    //echo "<br />";

    $out_array = $out_data_array;

    return $out_array;
} */
//��ȡ��֤�뷵��
//UINT32			 iCodeCount;		//��֤������
//SeAccountSecCode akAccountCode[10];		//�ʺ���֤��
//	char szLoginCode[32];				//����˺�
//	UINT32 iCode; 						//��֤�룬[1000,9999]����֤�룬0����֤��
function ProcessAWGetSeccodeRes($out_data)
{
   // echo "ProcessAWGetSeccodeRes: <br />";
    $out_data_array = unpack('LiCodeCount/', $out_data);
    //print_r($out_data_array);
    //echo "<br />";

    for ($x=0; $x<$out_data_array['iCodeCount']; $x++)
    {
      //  echo "CountInfo".($x + 1).":<br />";
        $out_data_Count_array = unpack('x4/x'.($x*36).'/a32szLoginCode/LiCode', $out_data);

        //print_r($out_data_Count_array);
        //echo "<br />";

		$out_data_array["CodeInfoList"][$x] = $out_data_Count_array;
    }

    $out_array = $out_data_array;

	return $out_array;
}