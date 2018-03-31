<?php
return array (
 'DB' =>
  array (
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_USER' => 'root',
    'DB_PWD' => 'root',
    'DB_PORT' => '3306',
    'DB_NAME' => 'WebData',
    'DB_CHARSET' => 'UTF8',//数据库编码
	  'DB_log' => 0,//开启日志
	  'DB_logfilepath' => './',//开启日志
    'DB_PREFIX' => 'tk_',
    'DB_CACHE_ON' => false,
    'DB_CACHE_PATH' => ROOT_PATH . 'cache/db_cache/',
    'DB_CACHE_TIME' => 600,
    'DB_PCONNECT' => false,//开启持久连
    'DB_CACHE_CHECK' => true,
    'DB_CACHE_FILE' => 'cachedata',
    'DB_CACHE_SIZE' => '15M',
    'DB_CACHE_FLOCK' => true,
  ),
);
?>
