<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号388003002444的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
		$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAKXyHZG8TFqZvl2e
p+6+2ZG3W22I2L3VHrosb4LVEwhDa7Z+tA9s08xXE89UEkemxA4/PVjaHVy6Ubl8
QJ2U/OhhKr+V4wYc+pAQAZSafqBfOkLx5sCzc5YiCeP0ZSfziQU9B9u+4dz9EWCQ
3brLmsbwzXoTXF8PghSmy3wyXqm3AgMBAAECgYAYPqpxS2iAaCv286IncHzLHFXg
R/kaqxOFMc7M96KDN456KELYahb7qNE9HlRZYZUNW9HztFJL5PDhv7SVEVptwOJT
ssiTIESLNn8oLW5ZEH7BINEhZejg/tRTHyG/F7tOaqtoucM4SdLi5cpgA7QE7ZF+
4PghgAP5DZEFN4/M8QJBAM6rb5hIkw5Cmo/wNXCjnYCjZoi1OMhQybKRys14WsWk
CxgeExXcK/OhDRSAeHQC7w0NHo0XOzo7+19BLTtYMa8CQQDNjj4TCdyriShaAyFH
rd36RCYiY4x9ROPPjQHhF+T2OAQeEslnfjenJsgyx1qd2u890Iw5wCQfj7OxoL75
dTJ5AkEAq+cNO62iJApiZbd3u+lb6NQBRVT3liccnduGxMGHPz3jvHvHhDdOl6cu
Kg9yIY0PKdvvvYvFR/r8a47bALGrIQJBAK5ULWb+HS1JhHRaZTYiSbj/ZQwTO0ne
TApw/yAEoMUEmtFag38HN3HGXVFbawmnbPES1mn//2LY/7/soSp1b5kCQCYuzbar
7vNHiWh4gNCD+efkiJiVZUUhE6CdrWZ5Z/5O73D039z/fTSVofZDOrftM/C+upPv
PIHR7FVEPfailXM=
-----END PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	//demo提供的merchant_public_key已经上传到测试商家号后台
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDxQu4dPNVysp8K2/rzV5aJeklV
Vk85cbUwrzH16WLLt70RfSPrpa748iRi6xfAgvkK9ZBcDGLE+6s00VU4/Qtux8Us
lIkoeIAou6HaxBBfRvRtpD0q8ay8PMRrKN7U+b71hTfZswckJrzRsBTedy1FO0Bz
ANTP6rFFFJwApOG2awIDAQAB
-----END PUBLIC KEY-----';
	
/**
1)ddbill_public_key，公钥，每个商家对应一个固定的公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为商家后台"公钥管理"->"公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的ddbill_public_key是测试商户号388003002444的智付公钥，请自行复制对应商户号的智付公钥进行调整和替换。
3）使用公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
	$ddbill_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCimaFfd9INyIy9HulNndpzHyRs
C9hqTVtukM+uPxzYnkcfy5cLKa62MKJ0FL7HZEhcr+zQy2apcatB/PbhDVQWeBRC
aacxMWPnmXzapMXv1487vssJmbKwZ9SlCqlsxPCUz8zecn5SSde23i7JM+JROOp/
ErQdXaz40P5zVVNeTQIDAQAB 
-----END PUBLIC KEY-----';





	



?>