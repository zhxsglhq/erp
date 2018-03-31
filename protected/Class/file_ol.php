<?php
/**
 * ���ļ������ߴ���༭�� editor.php �汾: v1.21
 * 
 * ������ܷ�ʽ: 
 * md5(��������+$ace) //$aceΪcdn�����ַ
 * 
 * ʹ�÷���: 
 * 1.ȷ�� $pwd ����ֵΪ false, �ϴ����ļ���PHP�ռ䲢����
 * 2.��һ�η�����ʾ��������,�������벢�μ�
 * 3.ʹ�õ�һ�����õ������¼��,Ĭ�ϱ༭���Ǳ�php�ļ�,
 * 4.���ļ��Ǳ༭�������ļ�,�벻Ҫ�����޸�
 * 5.����༭���ļ����� Ctrl + S �������,�ȴ�ִ�н��
 * 6.���涯��ִ�к�����صȴ�����ɹ���Ϣ����
 * 7.���ò������޸ı�������ļ������Է����˲²�·��
 * 8.ˢ�¹��ܽ���ˢ�±������ļ�������ˢ������
 * 
 * ������ chrome �������ʹ�ñ��༭��
 */
session_start();
$curr_file = __FILE__; //Ĭ�ϱ༭��ǰ�ļ�
$curr_file_path = str_replace(dirname(__FILE__), '', __FILE__);
$pwd = false; //�����ʼ��Ĭ��ֵΪ false
$ace = 'http://cdn.staticfile.org/ace/1.1.3/ace.js'; //�༭������js
$tip['core'] = 'http://cdn.staticfile.org/alertify.js/0.3.11/alertify.core.min.css';
$tip['css'] = 'http://cdn.staticfile.org/alertify.js/0.3.11/alertify.default.min.css';
$tip['js'] = 'http://cdn.staticfile.org/alertify.js/0.3.11/alertify.min.js';
$jquery = 'http://cdn.staticfile.org/jquery/2.1.1-rc2/jquery.min.js';
 
if ( false !== $pwd ) {
    define('DEFAULT_PWD', $pwd);
}
 
//�ļ���׺����Ӧ���﷨������
$lng = array(
    'as' => 'actionscript', 'js' => 'javascript',
    'php' => 'php', 'css' => 'css', 'html' => 'html',
    'htm' => 'html', 'ini' => 'ini', 'json' => 'json',
    'jsp' => 'jsp', 'txt' => 'text', 'sql' => 'mysql',
    'xml' => 'xml', 'yaml' => 'yaml', 'py' => 'python',
    'md' => 'markdown', 'htaccess' => 'apache_conf',
    'bat' => 'batchfile', 'go' => 'golang',
);
 
//�ж��û��Ƿ��¼
function is_logged() {
    $flag = false;
    if ( isset($_SESSION['pwd']) && defined('DEFAULT_PWD') ) {
        if ( $_SESSION['pwd'] === DEFAULT_PWD ) {
            $flag = true;
        }
    }
    return $flag;
}
 
//�������뵽��ҳ��
function reload() {
    $file = pathinfo(__FILE__, PATHINFO_BASENAME);
    die(header("Location: {$file}"));
}
 
//�ж������Ƿ���ajax����
function is_ajax() {
    $flag = false;
    if ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
        $flag = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    return $flag;
}
 
//����SESSION��COOKIE
function exterminate() {
    $_SESSION = array();
    foreach ( $_COOKIE as $key ) {
        setcookie($key, null);
    }
    session_destroy();
    $_COOKIE = array();
    return true;
}
 
//��ȡһ��Ŀ¼�µ��ļ��б�
function list_dir($path, $type = 'array') {
    $flag = false;
    $lst = array('dir'=>array(), 'file'=>array());
    $base = !is_dir($path) ? dirname($path) : $path;
    $tmp = scandir($base);
    foreach ( $tmp as $k=>$v ) {
        //���˵��ϼ�Ŀ¼,����Ŀ¼�ͳ��������ļ���
        if ( !in_array($v, array('.', '..')) ) {
            $file = $full_path = rtrim($base, '/').DIRECTORY_SEPARATOR.$v;
            if ( $full_path == __FILE__ ) {
                continue; //���������ļ������б����
            }
            $file = str_replace(dirname(__FILE__), '', $file);
            $file = str_replace("\\", '/', $file); //����win�µ�·��
            $file = str_replace('//', '/', $file); //����˫б��
            if ( is_dir($full_path) ) {
                if ( 'html' === $type ) {
                    $v = '<li class="dir" path="'.$file
                    .'" onclick="load();"><span>'.$v.'</span></li>';
                }
                array_push($lst['dir'], $v);
            } else {
                if ( 'html' === $type ) {
                    $v = '<li class="file" path="'.$file
                    .'" onclick="load()"><span>'.$v.'</span></li>';
                }
                array_push($lst['file'], $v);
            }
        }
    }
    $lst = array_merge($lst['dir'], $lst['file']);
    $lst = array_filter($lst);
    $flag = $lst;
    if ( 'html' === $type ) {
        $flag = '<ul>'. implode('', $lst) .'</ul>';
    }
    return $flag;
}
 
//�ݹ�ɾ��һ���ǿ�Ŀ¼
function deldir($dir) {
    $dh = opendir($dir);
    while ( $file = readdir($dh) ) {
        if ( $file != '.' && $file != '..' ) {
            $fullpath = $dir.'/'.$file;
            if ( !is_dir($fullpath) ) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
    return rmdir($dir);
}
 
//�˳���¼
if ( isset($_GET['logout']) ) {
    if ( exterminate() ) {
        reload();
    }
}
 
//ajax����ļ�����
if ( is_logged() && is_ajax() && isset($_POST['file']) ) {
    $file = dirname(__FILE__).$_POST['file'];
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $mode = isset($lng[$ext]) ? $lng[$ext] : false;
    die(json_encode(array(
        'file' => $file, 'html' => file_get_contents($file),
        'mode' => $mode,     
    )));
}
 
//ajax���Ŀ¼�б�
if ( is_logged() && is_ajax() && isset($_POST['dir']) ) {
    $dir = dirname(__FILE__).$_POST['dir'];
    $list_dir = list_dir($dir, 'html');
    die(json_encode(array(
        'dir' => $dir, 'html' => $list_dir,
    )));
}
 
//ajax�����ļ�
if ( is_logged() && is_ajax() && isset($_POST['action']) ) {
    $arr = array('result'=>'error', 'msg'=>'�ļ�����ʧ�ܣ�');
    $content = $_POST['content'];
    if ( 'save_file' === $_POST['action'] ) {
        if ( isset($_POST['file_path']) ) {
            $file = dirname(__FILE__).$_POST['file_path'];
        } else {
            $file = __FILE__;
        }
        file_put_contents($file, $content);
        $arr['result'] = 'success';
        $arr['msg'] = '����ɹ���';
    }
    die(json_encode($arr));
}
 
//ajaxɾ���ļ����ļ���
if ( is_logged() && is_ajax() && isset($_POST['del']) ) {
    $path = dirname(__FILE__).$_POST['del'];
    $arr = array('result'=>'error', 'msg'=>'ɾ������ʧ�ܣ�');
    if ( $_POST['del'] && $path ) {
        $flag = is_dir($path) ? deldir($path) : unlink($path);
        if ( $flag ) {
           $arr['msg'] = 'ɾ�������ɹ���';
           $arr['result'] = 'success';
        }
    }
    die(json_encode($arr));
}
 
//ajax�½��ļ����ļ���
if ( is_logged() && is_ajax() && isset($_POST['create']) ) {
    $flag = false;
    $arr = array('result'=>'error', 'msg'=>'����ʧ�ܣ�');
    if ( isset($_POST['target']) ) {
        $target = dirname(__FILE__).$_POST['target'];
        $target = is_dir($target) ? $target : dirname($target);
    }
    if ( $_POST['create'] && $target ) {
        $base_name = pathinfo($_POST['create'], PATHINFO_BASENAME);
        $exp = explode('.', $base_name);
        $full_path = $target.'/'.$base_name;
        $new_path = str_replace(dirname(__FILE__), '', $full_path);
        if ( count($exp) > 1 && isset($lng[array_pop($exp)]) ) {
            file_put_contents($full_path, '');
            $arr['result'] = 'success';
            $arr['msg'] = '�½��ļ��ɹ���';
            $arr['type'] = 'file';
        } else {
            mkdir($full_path, 0777, true);
            $arr['result'] = 'success';
            $arr['msg'] = '�½�Ŀ¼�ɹ���';
            $arr['type'] = 'dir';
        }
        if ( $base_name && $new_path ) {
            $arr['new_name'] = $base_name;
            $arr['new_path'] = $new_path;
        }
    }
    die(json_encode($arr));
}
 
//ajax�������ļ����ļ���
if ( is_logged() && is_ajax() && isset($_POST['rename']) ) {
    $arr = array('result'=>'error', 'msg'=>'����������ʧ�ܣ�');
    if ( isset($_POST['target']) ) {
        $target = dirname(__FILE__).$_POST['target'];
    }
    if ( $_POST['rename'] ) {
        $base_name = pathinfo($_POST['rename'], PATHINFO_BASENAME);
        if ( $base_name ) {
            $rename = dirname($target).'/'.$base_name;
            $new_path = str_replace(dirname(__FILE__), '', $rename);
        }
    }
    if ( $rename && $target && rename($target, $rename) ) {
       $arr['new_name'] = $base_name;
       $arr['new_path'] = $new_path;
       $arr['msg'] = '�����������ɹ���';
       $arr['result'] = 'success';
    }
    if ( $target == __FILE__ ) {
        $arr['redirect'] = $new_path;
    }
    die(json_encode($arr));
}
 
//��ȡ�����ļ�����
$code = file_get_contents($curr_file);
$tree = '<ul id="dir_tree">
    <li class="dir" path="/" onclick="load()">ROOT'.list_dir($curr_file, 'html').'</li>
</ul>';
 
//��½���������빲��ģ��
$first = <<<HTMLSTR
<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<title>�����⡿</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<style type="text/css" media="screen">
body {
    overflow: hidden; background-color: #2D2D2D; color: #CCCCCC; font-size: 12px; margin: 0;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', 'source-code-pro', monospace;
}
form { display: none; position: absolute; }
form h5 { font-size: 14px; font-weight: normal; margin: 0; line-height: 2em; }
form input {
    color: #fff; border: 1px solid #369; border-radius: 3px; background: #333; height: 22px;
    line-height: 1.6em; width: 125px; margin-right: 5px; vertical-align: middle;
}
form button {
    line-height: 1.6em; border: 1px solid #369; border-radius: 3px;
    background: #369; color: #fff; vertical-align: middle;
}
</style>
<link rel="stylesheet" href="{$tip['core']}" />
<link rel="stylesheet" href="{$tip['css']}" />
</head>
<body>
    <form method="post">
        <input name="pwd" type="password" /><button type="submit">��������</button>
    </form>
    <script src="{$jquery}" type="text/javascript" charset="utf-8"></script>
    <script src="{$ace}" type="text/javascript" charset="utf-8"></script>
    <script src="{$tip['js']}" type="text/javascript"></script>
    <script type="text/javascript">
    var editor = false;
    $(function(){
        $('form').prepend('<h5>'+ document.title +'</h5>');
        $('form').css({
            left: ($(window).width()-$('form').width())/2,
            top: ($(window).height()-$('form').height())/2
        });
        $('form').show();
    });
    </script>
</body></html>
HTMLSTR;
 
//�ж��Ƿ��һ�ε�¼
if ( false === $pwd && empty($_POST) ) {
    die(str_replace(
        array('�����⡿', '��������'),
        array('��һ��ʹ�ã������������룡', '����'),
        $first
    ));
}
 
//��һ�����õ�¼����
if ( false === $pwd && !empty($_POST) ) {
    if ( isset($_POST['pwd']) && strlen($_POST['pwd']) ) {
        $pwd = $_SESSION['pwd'] = md5($_POST['pwd'].$ace);
        $code = preg_replace('#\$pwd = false;#', '$pwd = "'.$pwd.'";', $code, 1);
        file_put_contents($curr_file, $code);
    } else {
        reload();
    }
}
 
//�û���¼��֤
if ( false !== $pwd && !empty($_POST) ) {
    $tmp = md5($_POST['pwd'].$ace);
    if ( $tmp && $pwd && $tmp === $pwd ) {
        $_SESSION['pwd'] = $pwd;
        reload();
    }
}
 
//����һ��htmlʵ��
$code = htmlspecialchars($code);
 
$dir_icon = str_replace(array("\r\n", "\r", "\n"), '',
'data:image/jpg;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAANCAYAAACgu+4kAAAAGXRFWHRTb2Z0d2
FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAQVJREFUeNqkkk1uwjAQhd84bsNP1FUXLCtu0H3XPSoX4Qrd9wR
sCjQEcIY3DiiJUYiqRhp5Mra/92YSUVVgLSW49B7H+NApRh75XkHfFoCG+02tyflUeQTw2y9UYYP8cCStc9SM
PeVA/Sy6Dw555q3au1z+EhBYk1cgO7OSNdaFNT0x5sCkYDha0WPiHZgVqPzLO+8seai6E2jed42bCL06tNyEH
AX9kv3jh3HqH7BctFWLMOmAbcg05mHK5+sQpd1HYijN47zcDUCShGEHtzxtwQS9WTcAQmJROrJDLXQB9s1Tu6
MtRED4bwsHLnUzxEeKac3+GeP6eo8yevhjC3F1qC4CDAAl3HwuyNAIdwAAAABJRU5ErkJggg==');
 
$file_icon = str_replace(array("\r\n", "\r", "\n"), '',
'data:image/jpg;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAQCAYAAADJViUEAAAAGXRFWHRTb2Z0d2
FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAS1JREFUeNqMU01KxkAMTaez7aYbNwreQdBzeopS6EXEW+jug7Z
C6X+/iUloSr6xioFHJkPee5mUJgBwT7gjpPB3XAgfiBjs5dOyLF/btl0pkEFngdbzPGNRFK/U+0hwJAAMjmcm
DsOA4zge6Pseu67DpmlEqK5rLMvyRkDJor6uq2SGktu2FfdpmpANqqoSASYnO/kthABJkoCOxCASkCBkWSYuQ
qCeNE1fqHz3fMkXzjnJ2sRinL33QBNIzWJ5nh/L8npQohVTJwYTyfFm/d6Oo2HGE8ffwseuZ1PEjhrOutmsRF
0iC8QmPibEtT4hftrhHI95JqJT/HC2JOt0to+zN6MVsZ/oZKqwmyCTA33DkbN1sws0i+Pega6v0kd42H9JB/8
LJl5I6PNbgAEAa9MP7QWoNLoAAAAASUVORK5CYII=');
 
$loading = str_replace(array("\r\n", "\r", "\n"), '',
'data:image/gif;base64,R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAA
AAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMteb
TMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxG
g9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQI
Dodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAA
ROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4
HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ
3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAA
QASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEox
kqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltM
JYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUK
AAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg
8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQ
f2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0
IADs=');
 
//�༭��ģ��
$html = <<<HTMLSTR
<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<title>ACE����༭��</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<style type="text/css" media="screen">
a { text-decoration: none; }
body {
    overflow: hidden; background-color: #2D2D2D; font-size: 12px;
    font-family: 'Consolas', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    scrollbar-arrow-color: #ccc; scrollbar-base-color: #333;
    scrollbar-dark-shadow-color: #00ffff; scrollbar-track-color: #272822;
    scrollbar-highlight-color: #272822; scrollbar-3d-light-color: #272822;
    scrollbar-face-color: #2D2D2D; scrollbar-shadow-color: #333;
}
::-webkit-scrollbar { width:5px; height:6px; background-color:#444; }
::-webkit-scrollbar:hover { background-color:#444; }
::-webkit-scrollbar-thumb:hover { min-height:5px; min-width:5px; background-color: #AAA; }
::-webkit-scrollbar-thumb:active { -webkit-border-radius:20px; background-color: #AAA; }
::-webkit-scrollbar-thumb {
    min-height:5px; min-width:5px; -webkit-border-radius:20px; 
    ::-webkit-border-radius:1px; background-color: #AAA;
}
body > pre { color: #666; }
#sider { margin: 0; position: absolute; top:  25px; bottom: 0; left: 0; right: 85%; }
#editor { margin: 0; position: absolute; top: 0; bottom: 0; left: 15%; right: 0; }
#dir_tree { margin:0; padding: 0; height: 100%; overflow: auto; position: relative; left: 5px; } 
#dir_tree, #dir_tree ul, #dir_tree li { margin: 0; padding: 0; list-style: none inside; }
#dir_tree ul { padding-left: 20px; position: relative; }
#dir_tree li { text-indent: 2em; line-height: 1.6em; cursor: default; color: #ccc; }
#dir_tree li.hover > span, #dir_tree li:hover > span { color: #66D9EF; }
#dir_tree li#on > span { color: red; }
#dir_tree li.dir { background: url({$dir_icon}) no-repeat 3px 3px; }
#dir_tree li.file { background: url({$file_icon}) no-repeat 3px 0; }
#dir_tree li.loading { background: url({$loading}) no-repeat 3px 0; }
#logout { position: absolute; top: 0; left: 0; }
#logout a { display: inline-block; color: #aaa; line-height: 25px; padding: 0 4px; }
#logout a:hover { background: #000; color: #ddd; }
#contextmenu { position: absolute; top: 0; left: 0; background: #fff; color: #333; border: 1px solid #000; padding: 1px; }
#contextmenu span { display: block; line-height: 24px; text-indent: 20px; width: 80px; cursor: default; }
#contextmenu span:hover { background-color: #369; color: #fff; }
#alertify .alertify-message, #alertify .alertify-message {
    text-align: left !important; text-indent: 0; font-weight: bold; font-size: 16px;
}
#alertify .alertify-dialog, #alertify .alertify-dialog {
    font-family: 'Consolas'; padding: 10px !important; color: #333 !important;
}
#alertify .alertify-button { 
    border-radius: 3px !important; font-weight: normal !important; 
    font-size: 14px !important; padding: 3px 15px !important;
}
.alertify-buttons { text-align: right !important; }
</style>
<link rel="stylesheet" href="{$tip['core']}" />
<link rel="stylesheet" href="{$tip['css']}" />
</head><body>
<div id="logout">
    <a href="javascript:void(0);">����</a>
    <a href="javascript:void(0);">ˢ��</a>
    <a href="javascript:void(0);">����</a>
    <a href="?logout">�˳�</a>
</div>
<div id="sider">{$tree}</div><pre id="editor">{$code}</pre>
<script src="{$jquery}" type="text/javascript" charset="utf-8"></script>
<script src="{$ace}" type="text/javascript" charset="utf-8"></script>
<script src="{$tip['js']}" type="text/javascript"></script>
<script type="text/javascript">
var load = false;
var curr_file = false;
window.location.hash = '';
alertify.set({delay: 1000}); //n����Զ���ʧ
alertify.set({labels: {ok:'ȷ��',cancel:'ȡ��'}});
var editor = false;
$(function(){
    //ʵ��������༭��
    editor = ace.edit("editor");
    //���ñ༭�����﷨�͸���
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/php");
    //���ñ༭���Զ�����
    editor.getSession().setWrapLimitRange(null, null);
    editor.getSession().setUseWrapMode(true);
    //����ʾ��ֱ����
    editor.renderer.setShowPrintMargin(false);
    //editor.setReadOnly(true); //���ñ༭��Ϊֻ��
    //editor.gotoLine(325); //��ת��ָ����
    //ʹ�༭��������뽹��
    editor.focus();
    //����ϰ���
    var commands = editor.commands;
    commands.addCommand({
        name: "save",
        bindKey: {win: "Ctrl-S", mac: "Command-S"},
        exec: save_file
    });
    //���涯��
    function save_file() {
        if ( false == editor ) { return false; }
        var obj = {
            content: editor.getValue(),
            action: 'save_file'
        };
        if ( false !== curr_file ) {
            obj.file_path = curr_file;
        }
        alertify.log('���ڱ���...');
        $.post(window.location.href, obj, function(data){
            if ( data.msg && 'success' == data.result ) {
                alertify.success(data.msg);
            } else {
                alertify.error(data.msg);
            }
        }, 'json');
    }
    //����Ŀ¼�б���ļ�
    load = function(ele) {
        var curr = $(event.srcElement);
        if ( ele ) { curr = ele; }
        if ( curr.is('span') ) { curr = curr.parent('li'); }
        $('#dir_tree #on').removeAttr('id');
        curr.attr('id', 'on');
        var type = curr.attr('class');
        var path = curr.attr('path');
        window.location.hash = path;
        if ( 'file' === type ) {
            alertify.log('���ڼ���...');
            curr.addClass('loading');
            $.post(window.location.href, {file:path}, function(data){
                curr.removeClass('loading');
                if ( data.mode ) {
                    editor.getSession().setMode("ace/mode/"+data.mode);
                }
                //ע�⣬���ļ�Ӧ������༭
                if ( true || data.html ) {
                    curr.attr('disabled', 'disabled');
                    curr_file = path; //��ǰ�༭���ļ�·��
                    //��̬��ֵ�༭���е�����
                    editor.session.doc.setValue(data.html);
                    editor.renderer.scrollToRow(0); //��������һ��
                    editor.focus(); //�༭����ý���
                    setTimeout(function(){
                        editor.gotoLine(0);
                    }, 800);
                }
            }, 'json');
            event.stopPropagation();
            event.preventDefault();
            return false;
        }
        if ( 'dir' === type ) {
            if ( curr.attr('loaded') ) {
                curr.children('ul').toggle();
                event.stopPropagation();
                event.preventDefault();
                return false;
            } else {
                curr.attr('loaded', 'yes');
            }
            alertify.log('���ڼ���...');
            curr.addClass('loading');
            $.post(window.location.href, {dir:path}, function(data){
                curr.find('ul').remove();
                curr.removeClass('loading');
                if ( data.html ) {
                    curr.append(data.html);
                }
            }, 'json');
        }
        return false;
    }
    //���Ҽ��˵�
    $('#sider').bind('contextmenu', function(e){
        var path = false;
        var target = $(event.srcElement);
        if ( target.is('span') ) {
            target = target.parent('li');
        }
        if ( target.attr('path') ) {
            path = target.attr('path');
        } else {
            return false;
        }
        target.addClass('hover');
        var right_menu = $('#contextmenu');
        if ( !right_menu.get(0) ) {
            var timer = false;
            right_menu = $('<div id="contextmenu"></div>');
            right_menu.hover(function(){
                if ( timer ) { clearTimeout(timer); }
            }, function(){
                timer = setTimeout(function(){
                    hide_menu(right_menu);
                }, 500);
            });
            $('body').append(right_menu);
        }
        if ( path ) {
            right_menu.html('');
            var menu = $('<span>�½�</span><span>���</span><span>������</span><span>ɾ��</span>');
            right_menu.append(menu);
            menu_area(right_menu, {left: e.pageX, top: e.pageY});
            right_menu.find('span').click(function(){
                switch ( $(this).text() ) {
                    case '�½�' : create_new(target, path); break;
                    case '���' : preview(target, path); break;
                    case '������' : re_name(target, path); break;
                    case 'ɾ��' : del_file(target, path); break;
                }
                hide_menu(right_menu);
            });
        }
        path ? right_menu.show() : hide_menu(right_menu);
        return false;
    });
    //�����Ҽ��˵�
    function hide_menu(menu) {
        $('#sider li.hover').removeClass('hover');
        if ( menu ) {
            menu.hide();
        }
    }
    //�Ҽ��˵�����
    function menu_area(menu, cfg) {
        if ( menu && cfg ) {
            var w = $('#sider').width() - menu.width();
            var h = $('#sider').height() - menu.height();
            if ( cfg.left > w ) { cfg.left = w; }
            if ( cfg.top > h ) { cfg.top = h; }
            menu.css(cfg);
        }
    }
    //���水ť
    $('#logout>a:contains("����")').click(function(){
        save_file();
        return false;
    });
    //ˢ�°�ť
    $('#logout>a:contains("ˢ��")').click(function(){
        window.location.href = window.location.pathname;
        return false;
    });
    //���ð�ť
    $('#logout>a:contains("����")').click(function(){
        alertify.confirm('�Ƿ��޸� {$curr_file_path} �����ļ�����', function (e) {
            if ( !e ) { return 'cancel'; }
            re_name($('<a>'), '{$curr_file_path}');
        });
        return false;
    });
    //�½�����
    function create_new(obj, path) {
        if ( !obj || !path ) { return false; }
        alertify.prompt('�������½��ļ����ļ�������', function (e, str) {
            if ( !e || !str ) { return false; }
            alertify.log('���ڲ�����...');
            $('#dir_tree #on').removeAttr('loaded').removeAttr('id');
            $.post(window.location.href, {create:str,target:path}, function(data){
                if ( data.msg && 'success' == data.result ) {
                    alertify.success(data.msg);
                    if ( obj.attr('class') == 'dir' ) {
                        load(obj); //���¼����ӽڵ�
                    } else {
                        load(obj.parent().parent());
                    }
                } else {
                    alertify.error(data.msg);
                }
            }, 'json');
        });
    }
    //�������
    function preview(obj, path) {
        if ( !obj || !path ) { return false; }
        window.open(path, '_blank');
    }
    //������
    function re_name(obj, path) {
        if ( !obj || !path ) { return false; }
        alertify.prompt('������ '+path+' Ϊ��', function (e, str) {
            if ( !e || !str ) { return false; }
            alertify.log('���ڲ�����...');
            $.post(window.location.href, {rename:str,target:path}, function(data){
                if ( data.msg && 'success' == data.result ) {
                    alertify.success(data.msg);
                    if ( data.redirect ) {
                        window.location.href = data.redirect;
                    }
                    if ( data.new_name ) {
                        obj.children('span').first().text(data.new_name);
                        obj.attr('path', data.new_path);
                    }
                } else {
                    alertify.error(data.msg);
                }
            }, 'json');
        });
    }
    //ɾ���ļ�����
    function del_file(obj, path) {
        if ( !obj || !path ) { return false; }
        alertify.confirm('��ȷ��Ҫɾ����'+path+' ��', function (e) {
            if ( !e ) { return 'cancel'; }
            alertify.log('����ɾ����...');
            $.post(window.location.href, {del:path}, function(data){
                if ( data.msg && 'success' == data.result ) {
                    alertify.success(data.msg);
                    obj.remove();
                } else {
                    alertify.error(data.msg);
                }
            }, 'json');
        });
    }
});
</script>
</body></html>
HTMLSTR;
 
//�ж��Ƿ��Ѿ���¼
if ( !is_logged() ) {
    die(str_replace(
        array('�����⡿', '��������'),
        array('����������һ�����õ����룡', '��¼'),
        $first
    )); 
} else {
    echo $html;
}