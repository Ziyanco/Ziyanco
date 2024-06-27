<?php

use Hyperf\Context\ApplicationContext;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

/**
 * 注入类
 */
if (!function_exists('di')) {
    /**
     * Finds an entry of the container by its identifier and returns it.
     * @param null|mixed $id
     * @return mixed|\Psr\Container\ContainerInterface
     */
    function di($id = null)
    {
        $container = ApplicationContext::getContainer();
        if ($id) {
            return $container->get($id);
        }
        return $container;
    }
}
/**
 * 返回的数据进行加密
 */
if (!function_exists('successRsa')) {
    function successRsa($data)
    {
        if (\Hyperf\Support\env('APP_ENV') == 'release' && $data['code'] == 200) {  //进行数据加密
            $commonKeyArr = getZiyancoRsaKey();    //获取加密串
            $enData = $data['data'];
            $jsonData = json_encode($enData);
            $rsaPublic = $commonKeyArr['common_public_key'];  //获取公钥
            $key_len = 2048;  //openssl_pkey_get_details($rsa_public)['bits']; // 获取私钥的生成的长度
            $encrypted = '';
            $part_len = $key_len / 8 - 11;
            $parts = str_split($jsonData, $part_len);
            foreach ($parts as $part) {
                $encrypted_temp = '';
                openssl_public_encrypt($part, $encrypted_temp, $rsaPublic);
                $encrypted .= $encrypted_temp;
            }
            $encodedEncryptedData = base64_encode($encrypted);

            $data || $data === 0 ? $data['data'] = $encodedEncryptedData : $data['data'] = [];

        }
        return $data;
    }
}

if (!function_exists('responseSuccess')) {
    function responseSuccess($data = [], int $code = 200, $message = '')
    {
        $content = ['code' => $code];
        $message ? $content['message'] = $message : $content['message'] = \App\Constants\UsCode::getMessage($code);
        $data || $data === 0 ? $content['data'] = $data : $content['data'] = [];
        return $content;
    }
}

if (!function_exists('responseError')) {
    function responseError($message = '', int $code = 500, $data = [])
    {
        $content = ['code' => $code];
        $message ? $content['message'] = $message : $content['message'] = \App\Constants\UsCode::getMessage($code);
        $data ? $content['data'] = $data : $content['data'] = [];
        return $content;
    }
}
/**
 * 注入日志类
 */
if (!function_exists('logger')) {
    /**
     * 获取日志实例.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function logger(string $name = 'Log'): LoggerInterface
    {
        return container()->get(LoggerFactory::class)->get($name);
    }
}


/**
 * 写入日志
 */
if (!function_exists('writeLog')) {
    function writeLog($message, $params = [])
    {
        return logger(\Hyperf\Support\env('APP_NAME'))->info($message, $params);
    }
}


/**
 * 格式化私钥
 */
if (!function_exists('ziyancoPrivateKey')) {
    /**
     * @param $key
     * @return string
     */
    function ziyancoPrivateKey($key)
    {
        $key = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($key, 64, PHP_EOL, true) .
            "\n-----END RSA PRIVATE KEY-----";

        return $key;
    }
}

/**
 * 格式化公钥
 */
if (!function_exists('ziyancoPublicKey')) {
    /**
     * @param $key
     * @return string
     */
    function ziyancoPublicKey($key)
    {
        $key = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($key, 64, PHP_EOL, true) .
            "\n-----END PUBLIC KEY-----";

        return $key;
    }
}


if (!function_exists('getZiyancoRsaKey')) {
    /**
     * 事件调度快捷方法
     * @param object $dispatch
     * @return object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    function getZiyancoRsaKey(): array
    {

        return [
            'common_private_key' => \Hyperf\Support\env('SUCCESS_RSA_PRIVATE_KEY', '-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCzNO10mBvIShx9
SujypESdVf2i58434aAulviM6YgxTrhesFVsHBBDkowfEZuTdjnGLE2Sekp6n/4D
v23qgaDPsTY/n3KzKVjBnm538sF77XiuMExl9q3rB+5BEv6d0+XG03haaOf1aZDj
3tB8XPNkBVoXKGH7jGtbH4tYFPCkkhDvIQ43z8UWWgn0sBndHW6GSDRFRuA/xNNp
JTVNKcuTxjsJYFYjYMYvZ+gAIaDwZcm/8XuOvvrxzv8kndqhwJgTv6N3F5NqX7Ry
xyv0ZBnBvNKrVoUm5P90erbIJnTdDZQ0zRe+isC058js/ZC/ViqlUaZ//YxDKwvC
y9soBfbDAgMBAAECggEADaeHPj6Gw2SONie6hx5oEfrm1/5EkrJw04vyRT5X/7XJ
S4GxCIs83AEzBvz+9yXncgeXI4WI3j4JizyPQfJ9j8WnZgKeqTMJzbrmHOKUxn4K
F2+bqps4ljNk/nVVjC4EkHk1buIWZncUMKCeDxPAAct8mU4c5R9GYMuPAPebfFZ5
3ggJusYgxByZwQzi/6E28VmgSTYqH+ZJ7kb8AiApzdZy7lWRRNzn0S0LAnUr09v5
8JMOl+b9DwGH3NzkiWMwtGZh+z70LXzVD3dorh2EciBsBY9tXN+KqM/GZZkYecD4
2JZFhwgJnfHn52YjpDZS9Q1upf+u7Gy9H2Afd2pwbQKBgQD1Spse96RlV7VRnd9e
FNOrXLKgAZJmHROTGvJpFAdj0malsC0GeyZdmO2Voh/iPtOSjUXqFkiiadI7TYLA
R9hSKS4z7oHUFAWUlO9VpZKKFsGiS2x7pvcVJeJJCfgkh+8vZkNbKgKW9XFV+0qv
sdO/iNjVeoXZ2hGKOKsllt8zHQKBgQC7B8EveMZtjHxhTmaBq7jAPKbmepal9xg0
DKH6/5QrahrFECvSKPMjgHdJmb+y5pdGk0hIIr2BhOgjDEGb/XzQQTRKTq6+auJC
tmuuwSIShMTJlgDNrokflKEH4fCVwVhlmTBmDV0xq2QAMZm0gnnfsqnKndc17fl2
QUp5WjLLXwJ/f29DJ9OAlSX8DWr3oOkxDtYZ9wbaCDMS+FoHlo/RRwsISQw+WB+i
zv2kcRjJd8EsKfc7W2I4WiigEC80vrCUshqy5kgDKys7uvWlYdmjSBqKfsawlY0o
0NPdvBRx1ojIutdmJvasYcEiIIltQ9ofKGLwQ/hjkcq5UhYe1GiNjQKBgQCu+Fc7
0RJZpCcsZUsynnMNrPA7CI5tH5gOAY98IKP+ZZo7p6BHUTX9jY6LVYHonR8C0IC7
s3x0ceE2AJ5Uj7W3oniH18pUq+uWm9BrwZTdnEX5jRKdWV5BJCvuEuqfPy0yhzin
EHOSJxQ7titkmvfnVd7Qhg+zDTnm7rf8hWylowKBgQCH1nNDn43pfEO4PTqhUEgs
M0AEjWo9I9KxQa2tKRdBJP44C5mARZosPWGiXEeZ+nYBCtuax5Lj/aJTAxAHs2JJ
ntaRVb8fDJvA+PpXLQYH8C2vZ6Le0RZzVrn/b0j8rGN4KdxFvRS7DkAEQ22bTAik
ECc5LiV3xGJFtTw4sZzekw==
-----END PRIVATE KEY-----'),
            'common_public_key' => \Hyperf\Support\env('SUCCESS_RSA_COMMON_KEY', '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAszTtdJgbyEocfUro8qRE
nVX9oufON+GgLpb4jOmIMU64XrBVbBwQQ5KMHxGbk3Y5xixNknpKep/+A79t6oGg
z7E2P59ysylYwZ5ud/LBe+14rjBMZfat6wfuQRL+ndPlxtN4Wmjn9WmQ497QfFzz
ZAVaFyhh+4xrWx+LWBTwpJIQ7yEON8/FFloJ9LAZ3R1uhkg0RUbgP8TTaSU1TSnL
k8Y7CWBWI2DGL2foACGg8GXJv/F7jr768c7/JJ3aocCYE7+jdxeTal+0cscr9GQZ
wbzSq1aFJuT/dHq2yCZ03Q2UNM0XvorAtOfI7P2Qv1YqpVGmf/2MQysLwsvbKAX2
wwIDAQAB
-----END PUBLIC KEY-----')
        ];
    }
}



