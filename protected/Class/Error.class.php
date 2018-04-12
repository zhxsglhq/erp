<?php
  function Error($StrFiltKey,$StrFiltValue,$errtype) {
    $msg="操作IP: ".$_SERVER["REMOTE_ADDR"]."操作时间: ".strftime("%Y-%m-%d %H:%M:%S")."操作页面:".$_SERVER["PHP_SELF"]."提交方式: ".$_SERVER["REQUEST_METHOD"]."提交参数: ".$StrFiltKey."提交数据: ".$StrFiltValue."错误类型：".$errtype;
    exit($msg);
  }
?>
