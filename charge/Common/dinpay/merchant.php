<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号1118004517的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAMAWNd8tBzBjlUYG
jEcI+0eKMUDvAru29SvHUTFGE1Ioe8+xwdCdr83R89+53ss95YxeRs9+66Yr3UVY
TRfEpGa1ZqwC7LGP9R5X7O6p2kn+SRBq7v5n7+UHNsKdskip4SxcLeDajoGB5Xxg
smuPT9P0YTBDSvepEbLDDWBQm91ZAgMBAAECgYEAkstmeBvNNqmj1lQCt/ahMdbm
NR1zFUmIq+AyqFlJQqw1kk/iMi+PvtcIbJ17Kg8vccpKiyAPrnovK2OD5vocOB3J
ApW4gH690YDDlRiRzuHG2GKPilMzt7BoGEWrSz+dUoczsCKxEvVXBNQRQJc/cy6u
abzvmJbF4ObDxUI54UECQQDvwyMod38RXR4KV1aOj1T7t8WCHNRvcnOOKH+CniRY
3dz2l/Ola783d9zEzmFoscpmFtv1VY5wMVE0nlSUTYOtAkEAzRiAlFA9mbFffBz0
j6JYNuZLadVZVEfR6hSTjObe2QO1RlqThVFDrc3ujeUzoezC2AeXslxD6s1ov/2X
wfQV3QJBAMVpjaQ/J8/bNOXc3bcJyzZrLOOh60RMH1s4eyzIGzNUkiA0IvfcTJhW
R99/8nJqmTUPs1JAfMRhxvQlPYzSeg0CQEUccJeJwng78PUdvLt59UfqqpbrMcLY
wL+kV2QvmACA42DlvLg7/hZfQnfGOHPkGHQ7er67oJdKyHWJus28tnECQEQXR+7X
R7r5O9oggxAuKeqd3oj9EB3T3A8QXxaj2xjjeFPT+IawL478DlolTy5MIER8A/IK
A5lGWyOOBHfQRUs=
-----END PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到智付商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDAFjXfLQcwY5VGBoxHCPtHijFA
7wK7tvUrx1ExRhNSKHvPscHQna/N0fPfud7LPeWMXkbPfuumK91FWE0XxKRmtWas
Auyxj/UeV+zuqdpJ/kkQau7+Z+/lBzbCnbJIqeEsXC3g2o6BgeV8YLJrj0/T9GEw
Q0r3qRGyww1gUJvdWQIDAQAB
-----END PUBLIC KEY-----';
	
/**
1)dinpay_public_key，智付公钥，每个商家对应一个固定的智付公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为智付商家后台"公钥管理"->"智付公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的dinpay_public_key是测试商户号1118004517的智付公钥，请自行复制对应商户号的智付公钥进行调整和替换。
3）使用智付公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
	$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCYP7EUxtWa8Zkjd9Y3
GkIBWZKbeiIgVtEb32vygpchryVqPD2vwlrCbiGszG7VTDiWWZ4pSpTv
NTfzwAco5NY9WcR83wR70QtOHmpPHD0FGV6L9gS8OXgD9rj/Dr9/wwXP
dp9RB7NavuPnlY7Thc5AVl1JoAfF/31irWt8XSytLwIDAQAB
-----END PUBLIC KEY-----'; 	


	



?>