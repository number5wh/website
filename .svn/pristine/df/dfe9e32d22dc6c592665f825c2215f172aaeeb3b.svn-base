<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号388003002444的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
 */
$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKcNgjn0bujqHGgv
Y56I0dn/BlFfkuQgJK9UeWHq3yMIe7LZQxdYO96S5L3krEQJ0xqdBnqk6e9ZGXPH
DMKp3mGWgX17ag2MX1DuUcfHkc2UWlp+rmhuPcr8jOOXVJT/8l8wIUG5zRuBg2SG
2JgfJRSECSvveUInvrwSz+fq3iudAgMBAAECgYB9UNHvPeFxkiXLNjmJ9ONPoFMR
+htMTJyYdks0XCgVtMhuqzL8MTGATzuPBBsCU0hsv3zbxhjDd1hzlM+KvDlYT3MK
mBySpDQcJUClqlkuizFXcO7v8RCPafO2qKtscMxSaua2Z3B59Y8AXTZ5wC+qTjzX
K3rg4Urk0UkLtDN44QJBANCV0y030OFVxaLD64ZtSWScFNWGWEA5DSaKU85vsGQt
U9y4b67jk7JGRMnMSdSJH3cARfd9zzZ5wpNQPB4wMbUCQQDNBsoG12+Ia9vdHyFf
2vXguvOsJ2MNRJ2J2Ba8ptFZ6SJNy6KkF5SjylUVtMYFiLF/s3enNVh6jr51TxqY
q2NJAkBm8qup07SaotTKwtwKGwJwT8DQqmAAQqhE71zxtJpgtyZ+9+DXdqc9BAWL
e/KsnYkUGORvhH6zKmFoh99EHxFJAkEAuUB5KtVeIZXTg2itbz8ZC4XgxpyQE1z8
O6DUaoEQiHzuUfy9aCcmVxhq6MH+auGoAArZAmxhFdwEm1puXaP+gQJAOqeALONh
fJiMEuF3/IUcOLVGD+nMzPnWPaeN/cwMBcO734o3xAGZ8qJF3FCQWmTVSfX1zDYV
jc86ZAZrh/8Qnw==
-----END PRIVATE KEY-----';

//merchant_public_key,商户公钥，按照说明文档上传此密钥到商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
//demo提供的merchant_public_key已经上传到测试商家号后台
$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnDYI59G7o6hxoL2OeiNHZ/wZR
X5LkICSvVHlh6t8jCHuy2UMXWDvekuS95KxECdManQZ6pOnvWRlzxwzCqd5hloF9
e2oNjF9Q7lHHx5HNlFpafq5obj3K/Izjl1SU//JfMCFBuc0bgYNkhtiYHyUUhAkr
73lCJ768Es/n6t4rnQIDAQAB
-----END PUBLIC KEY-----';

/**
1)ddbill_public_key，公钥，每个商家对应一个固定的公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为商家后台"公钥管理"->"公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的ddbill_public_key是测试商户号388003002444的智付公钥，请自行复制对应商户号的智付公钥进行调整和替换。
3）使用公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
 */
$ddbill_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCw+m5E5fkXm+LG53YHDUxXqcKG
R6L1bF4m+Jvlz1byISxBgUxS2dz0vXv3FGC0zHLUYCbkBHLX0NdwGmSDtbatWbUF
xzqxqikXvg7CiLN+PF1hcnqnSVuqBkzu752BTzihxCMwiUFLnkLx2Wc4ALYk/tlS
ffR78kUPOSLNDGYUnwIDAQAB
-----END PUBLIC KEY-----';



?>