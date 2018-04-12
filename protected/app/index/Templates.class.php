<?php
class Templates {
private $_tpl;
private $tplconfig;
private $_php;
private $_vars=array();//存放模板引擎注入的普通变量
private $_configs=array();//载入的系统变量
private $tpl_file;//模板文件路径
private $parse_file;//编译文件路径
private $cache_file;//缓存文件路径
private $TPL_INDEX;
private $TPL_CACHE;
private $DEFAULT_TPL;
private $HTML_CACHE;
private $IS_CACHE;
//模板构造方法,主要完成相关目录是否存在的检测,以及将系统变量的值读入到$_configs变量中
public function __construct(){
$tplconfig=config('TPL');
$this->TPL_INDEX=$tplconfig['TPL_INDEX'];
$this->TPL_CACHE=$tplconfig['TPL_CACHE'];
$this->DEFAULT_TPL=$tplconfig['DEFAULT_TPL'];
$this->HTML_CACHE=$tplconfig['HTML_CACHE'];
$this->IS_CACHE=$tplconfig['IS_CACHE'];
$qiniu=config('qiniu');
$this->qiniuClose=$qiniu['qiniuClose'];
$this->clouddn=$qiniu['clouddn'];
$sysinfo=config('sysinfo');
$this->siteurl=$sysinfo['siteurl'];
$this->is_dir_exists();
}
//display()方法：完成与编译,缓存相关的一些功能
public function display($files){
$this->tpl_file=$this->TPL_INDEX.'/'.$this->DEFAULT_TPL.$files;//设置模板文件路径
if(!file_exists($this->tpl_file)){
	exit($this->tpl_file.'模板文件不存在，错误：0030！');
}
$this->tpl_html();
$this->parse_file=$this->TPL_CACHE.md5($files).'.'.$files.'.php';//设置编译文件路径
$this->compile($this->parse_file,$this->tpl_file);//解析静态模板文件,生成编译文件
$this->cache($files);
}

public function infile($files){
$this->tpl_file=$this->TPL_INDEX.'/'.$this->DEFAULT_TPL.$files;//设置模板文件路径
if(!file_exists($this->tpl_file)){
	exit($this->tpl_file.'模板文件不存在，错误：0041！');
}
$this->tpl_html();
$this->parse_file=$this->TPL_CACHE.md5($files).'.'.$files.'.php';//设置编译文件路径
$this->compile($this->parse_file,$this->tpl_file);//解析静态模板文件,生成编译文件
include $this->parse_file;//载入编译文
}

public function tpl_html(){
if(!$this->_tpl=file_get_contents($this->tpl_file)){
	exit('模板内容读取失败，错误：0051!');
}
$this->parseForeach();
}

private function parseForeach()
{
	$str=$this->_tpl;
	$str = preg_replace("/\<\!\-\-\{@info\.([\w]+)}\-\-\>/","<?php echo \$this->_config['$1'];?>",$str);
	$str = preg_replace("/\{#(.*)=(.*)}/","<?php \$\\1=\\2;?>",$str);
	$str = preg_replace("/\<\!\-\-\{@VerifyCode\}\-\-\>/","<img style='cursor: pointer; vertical-align:middle; width:50px;' title='点击刷新' src='<?php echo siteurl;?>/index.php?r=apps/code/' onclick=\"this.src='<?php echo siteurl;?>/index.php?r=apps/code/'+Math.random();\">",$str);
	$str = preg_replace("/\<\!\-\-\{@addget\}\-\-\>/","<?php echo \$_SESSION[\"addget\"];?>",$str);
	$str = preg_replace("/\<\!\-\-\{@startpage\}\-\-\>/","<?php echo \$_SESSION[\"startpage\"];?>",$str);
	$str = preg_replace('/\<\!\-\-\{@config\.([\w]+)\}\-\-\>/',"<?php echo \$this->_configs['$1'];?>",$str);
	$str = preg_replace("/\<\!\-\-\{@include\s+file=\"(.+)\"\}\-\-\>/","\n<?php \$this->infile(\"\\1\");?>\n",$str);
if ($this->qiniuClose){
	$str = preg_replace("/\<\!\-\-\{@style}\-\-\>/","<?php echo \$this->clouddn;?>/tpl_styles/".$this->DEFAULT_TPL."",$str);
}else{
	$str = preg_replace("/\<\!\-\-\{@style}\-\-\>/","<?php echo \$this->siteurl;?>/tpl_styles/".$this->DEFAULT_TPL."",$str);
}
	$str = preg_replace("/\<\!\-\-\{@if\s+(\S+)!=(\S+)\}\-\-\>/","<?php if(\\1!=\\2) { ?>",$str);
	$str = preg_replace("/\<\!\-\-\{@if\s+(\S+)=(\S+)\}\-\-\>/","<?php if(\\1==\\2) { ?>",$str);
	$str = preg_replace("/\<\!\-\-\{@elseif\s+(\S+)=(\S+)\}\-\-\>/","<?php } elseif(\\1==\\2) { ?>",$str);
	$str = preg_replace("/\<\!\-\-\{@if\s+(\S+)\}\-\-\>/","<?php if(\\1) { ?>",$str);
	$str = preg_replace("/\<\!\-\-\{@else\}\-\-\>/","<?php } else { ?>",$str);
	$str = preg_replace("/\<\!\-\-\{@elseif\s+(.+?)\}\-\-\>/","<?php } elseif (\\1) { ?>",$str);
	$str = preg_replace("/\<\!\-\-\{@endif\}\-\-\>/","<?php }; ?>",$str);
	$str = preg_replace("/\<\!\-\-\{@loop\s+(\S+)\s+(\S+)\}\-\-\>/","<?php \$i=0; foreach(\\1 AS \\2) { \$i=\$i+1;?>",$str);
	$str = preg_replace("/\<\!\-\-\{@\/loop\}\-\-\>/","\n<?php } ?>\n",$str);
	$str = preg_replace("/\<\!\-\-\{@([\w]+)\}\-\-\>/","<?php echo \\1;?>",$str);

	$str = preg_replace("/\<\!\-\-\{@(\S+):one{(.*)}}\-\-\>/i","<?php $$1=one(\"$2\");?> ",$str);
	$str = preg_replace("/\<\!\-\-\{@(\S+):all{(.*)}}\-\-\>/i","<?php $$1=all(\"$2\");\$i=0; if(!empty($$1)) foreach($$1 as $$1){  \$i=\$i+1; ?> ",$str);
	$str = preg_replace("/\<\!\-\-\{@(\S+):startpage{(.*)}}\-\-\>/i","<?php $$1=startpage(\"$2\");\$i=0; if(!empty($$1)) foreach($$1 as $$1){  \$i=\$i+1; ?> ",$str);
	$str = preg_replace("/\<\!\-\-\{@(\S+):page{(.*)}}\-\-\>/i","<?php $$1=page(\"$2\");\$i=0; if(!empty($$1)) foreach($$1 as $$1){  \$i=\$i+1; ?> ",$str);
	$str = preg_replace("/\<\!\-\-\{@(\S+):{(.*)}}\-\-\>/i","<?php $$1=menu($2);\$i=0; if(!empty($$1)) foreach($$1 as $$1){  \$i=\$i+1; ?> ",$str);
	$str = preg_replace("/\{#([\w]+)\.(.*)\}/","\$\\1['\\2']",$str);
	$str = preg_replace("/\{#([\w]+)\:(.*)\}/","\$\\1($2)",$str);
	$str = preg_replace("/\<\!\-\-\{@\/([a-zA-Z_]+)}\-\-\>/i", "<?php }; ?>", $str);
	$str = preg_replace("/\<\!\-\-\{@([\w]+)\.([\w]+)\}\-\-\>/","<?php echo \$\\1['\\2'];?>",$str);
	$this->_tpl=$str;
}



//判断是否需要重新生成缓存文件
public function compile($parse_file,$tpl_file){
$this->parseForeach();
if(!file_exists($parse_file)||filemtime($tpl_file)>=filemtime($parse_file)){
if(!file_put_contents($parse_file,$this->_tpl)){
	exit($files.'编译文件生成失败，错误：0108!');
}
}
}



//assign()方法：接收从index.php文件分配过来的变量的值,将它们保存在$_vars变量中

public function assign($var,$value){
if(isset($var)&&!empty($var)){//判断模板变量是否有设置,且不能为空
$this->_vars[$var]=$value;//接收从index.php文件分配过来的变量的值,将它们保存在$_vars变量中
}else{
	exit($files.'请设置模板变量，错误：0121!');
}
}

public function start($var){
if(isset($var)&&!empty($var)){//判断模板变量是否有设置,且不能为空
$this->_config=$var;//接收从index.php文件分配过来的变量的值,将它们保存在$_vars变量中
$this->display($this->_config['sitetpl'].'.html');//显示模板文件
}else{
	exit($files.'请设置模板变量，错误：0129!');
}
}


//is_dir_exists()方法：相关目录是否存在的检测
private function is_dir_exists(){

if(!is_dir($this->TPL_INDEX)){//检测是否存在模板文件夹
exit('模板文件夹不存在!');
}
if(!is_dir($this->TPL_CACHE)){//检测是否存在编译文件夹
exit('编译文件夹不存在!');
}
if(!is_dir($this->HTML_CACHE)){//检测是否存在缓存文件夹
exit('缓存文件夹不存在!');
}
}

//cache()方法：完成与缓存相关的一些功能

private function cache($files){
if($this->IS_CACHE){
if ($_GET['page']){
	$page='_page_'.$_GET['page'];
}
if ($_GET['i']){
	$i='_'.$_GET['i'];
}
$this->cache_file=$this->HTML_CACHE.md5($files).'.'.$files.$i.$page.'.html';//设置缓存文件路径
if(file_exists($this->cache_file)&&filemtime($this->cache_file)>=filemtime($this->parse_file)){
include $this->cache_file;
}else{
$this->IS_CACHE? ob_start():null;
include $this->parse_file;//载入编译文件
file_put_contents($this->cache_file,ob_get_contents());//生成静态html缓存文件
ob_end_clean();
include $this->cache_file;//载入静态html缓存文件
}
}else{
include $this->parse_file;//载入编译文件
return ;
}
}



}
?>
