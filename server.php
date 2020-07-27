<?php 
require __DIR__.'/vendor/autoload.php';
require_once("vendor_lean/autoload.php");

use EasyWeChat\Factory;
use LeanCloud\Client;
use LeanCloud\LeanObject;
use LeanCloud\User;

function curl($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_REFERER, $url);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

include 'config.php';

$app = Factory::officialAccount($config);
$app->server->push(function ($message) {
    include 'config.php';
    $http_type=((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://'; 
    $openid = $message['FromUserName'];
    $arr = $db->query("SELECT * FROM `userinfo` WHERE openid='{$openid}'")->fetch();
    $url = $arr['url'];
    $address = $arr['address'];
    $class = $arr['class'];
    $AppID = $arr['AppID'];
    $AppKey = $arr['AppKey'];
    $MasterKey = $arr['MasterKey'];
    $username = $arr['username'];
    $userpass = $arr['userpass'];
    switch ($message['Content']) {
        case 'openid':
            return $message['FromUserName'];
            break;
        case "绑定":
            if (isset($url)) {
                return "<a href='".$http_type.$_SERVER["HTTP_HOST"].dirname($_SERVER['SCRIPT_NAME'])."/bind.php?openid=$openid'>您已绑定，点击查看或修改</a>";
            } else {
                return "<a href='".$http_type.$_SERVER["HTTP_HOST"].dirname($_SERVER['SCRIPT_NAME'])."/bind.php?openid=$openid'>点击绑定</a>";
            }
            break;
        case "解除绑定":case "解绑":
            if ($db->query("DELETE FROM `userinfo` WHERE openid='{$openid}'")) {
                return "已经解除绑定";
            } else {
                return "操作失败，未知错误";
            }
            break;
        case "帮助":
            return '1.发送 绑定 进行绑定或修改绑定信息。
            2.发说说
            支持文字、图片、地理位置、链接四种消息类型。
            其他消息类型等后续开发，暂不支持（如果发送了，会提示不支持该类型的，如语音消息）。
            可以发送手机输入发自带的emoji，不支持微信emoji和表情包（发送后会提示成功，但是在说说界面会显示乱码或 【收到不支持的消息类型，暂无法显示】 ）。
            如果发送的是图片会自动将图片上传到 Gitee 仓库。
            
            连续发送多条信息
            发送【开始】，开始一轮连续发送
            发送【结束】，结束当前轮的发送
            
            3.其他操作
            发送 博客/说说/微语 收到你的 博客/说说 地址的链接
            发送 解除绑定 或 解绑 可删除掉你的绑定信息
            发送 帮助 查看帮助信息
            
            4.如果你发送的信息没有触发关键词，将直接以发说说的形式发布。
            
            5.<a href=\'https://www.icene.cn/archives/WeChat-for-Artitalk\'>图文教程</a>';
            break;
        default:
            if ($url!=null && $AppID!=null && $AppKey!=null && $MasterKey!=null && $username!=null && $userpass!=null){
                switch ($message['Content']) {
                    case "博客":
                        return '<a href=\''.$url.'\'>打开博客</a>';
                        break;
                    case "说说":case "微语":
                        return '<a href=\''.$url.$address.'\'>打开说说/微语</a>';
                        break;
                    case "发图":
                        return '<a href=\'https://pic.icene.cn/\'>打开上传图片</a>';
                    default:
                        switch ($message['Content']) {
                            case "取消":
                                $db->query("update `userinfo` set msg_type='',talk='' where openid='$openid'");
                                return "已取消发送";
                                break; 
                            case '发说说':case '开始':
                                $msg_type = 'start_talk';
                                $db->query("update `userinfo` set msg_type='$msg_type',talk='' where openid='$openid'");
                                return "当前处于混合消息模式，请继续，发送『结束』结束本次发送，发送『取消』取消本次发送~";
                                break;
                            case '结束':
                                $arr = $db->query("SELECT * FROM `userinfo` WHERE openid='{$openid}'")->fetch();
                                $str = $arr['talk'];
                                if($str==null){
                                    $db->query("update `userinfo` set msg_type='',talk='' where openid='$openid'");
                                    return "已结束，本次操作未发送任何信息~";
                                    exit();
                                }
                                $msg_type = $arr['msg_type'];
                                $arr = mb_split('@',$str);
                                $m = count($arr);
                                for ($i = 0;$i < $m-1;$i++) {
                                    $con[$i] = mb_split('->',$arr[$i]);
                                }
                                $m1 = count($con);
                                for ($m = 0;$m < $m1;$m++) {
                                    $result[$m] = array('type' => $con[$m][0],'talk' => $con[$m][1]);
                                }
                                $talk = array('results' => $result);
                                $talk_post = '';
                                $m2 = count($talk['results']);
                                for ($m = 0;$m<$m2;$m++){
                                    $talk_post = $talk_post.$talk['results'][$m]['talk'];
                                }
                                Client::initialize($AppID, $AppKey, $MasterKey);
                                User::logIn($username, $userpass);
                                $testObject = new LeanObject($class);
                                $testObject->set("content", $talk_post);
                                $testObject->set("os", "WeChat");
                                $testObject->set("postion", "by WeChat");
                                        try {
                                            $testObject->save();
                                            $status = "1";
                                        } catch (Exception $ex) {
                                            $status = "0";
                                        }
                                $db->query("update `userinfo` set msg_type='',talk='' where openid='$openid'");
                                switch ($status) {
                                    case "1":
                                        return "♥biubiubiu~发送成功";
                                        break;
                                    case "0":
                                        return "🤦‍发送失败，可能是你的绑定信息有误。";
                                        break;
                                    default:
                                        return $status;
                                }

                                break;
                            default:
                                $arr = $db->query("SELECT * FROM `userinfo` WHERE openid='{$openid}'")->fetch();
                                $buffer = $arr['talk'];
                                $type = $arr['msg_type'];
                                switch ($message['MsgType']) {
                                    case "location":
                                        $content = "<p>"."📌"."#" . $message['Label'] . "</p>"."<img height=\"80.258%\" width=\"80.258%\"src=\""."https://restapi.amap.com/v3/staticmap?location=" . $message['Location_Y'] . "," . $message['Location_X'] . "&zoom=10&size=750*300&markers=mid,,A:" . $message['Location_Y'] . "," . $message['Location_X'] . "&key=5d0101d3f71377bc1bc5454ea64566e6"."\"/>";
                                        $talk = $content;
                                        $msg_type = "location";
                                        if ($type == 'start_talk') {
                                            $talk = $buffer.$msg_type."->".$talk."@";
                                        }
                                        $db->query("update `userinfo` set talk='$talk' where openid='$openid'");
                                        break;
                                    case "image":
                                        $imgurl = $message['PicUrl'];
                                        include_once 'gtimg.php';
                                    
                                        $content = "</p><a href=\"$remoteimg\" target=\"_blank\"><img class=\"shuoshuoimg gallery-group-img\" src=\"".$remoteimg."\" style=\"width: 20%\"/></a>";
                                        $talk = $content;
                                        $msg_type = "image";
                                        if ($type == 'start_talk') {
                                            $talk = $buffer.$msg_type."->".$talk."@";
                                        }
                                        $db->query("update `userinfo` set talk='$talk' where openid='$openid'");
                                        break;
                                    case "link":
                                        $content = "<p>"."#"."🔗".$message['Description']."# "."<a target=\"\_blank\" href=\"".$message['Url']."\">".$message['Title']."</a>"."<p>";
                                        $talk = $content;
                                        $msg_type = "link";
                                        if ($type == 'start_talk') {
                                            $talk = $buffer.$msg_type."->".$talk."@";
                                        }
                                        $db->query("update `userinfo` set talk='$talk' where openid='$openid'");
                                        break; 
                                    case "text":
                                        $talk = "<p>".$message['Content']."</p>";
                                        $msg_type = "text";
                                        if ($type == 'start_talk') {
                                            $talk = $buffer.$msg_type."->".$talk."@";
                                        }
                                        $db->query("update `userinfo` set talk='$talk' where openid='$openid'");
                                        break;
                                    default:
                                        return "不支持的消息类型";
                                        exit();
                                }
                                $arr = $db->query("SELECT * FROM `userinfo` WHERE openid='{$openid}'")->fetch();
                                $talk = $arr['talk'];
                                $type = $arr['msg_type'];
                                switch ($type) {
                                    case 'start_talk':
                                        return "请继续，发送『结束』结束本次发送，发送『取消』取消本次发送~";
                                        break;
                                    default:
                                        Client::initialize($AppID, $AppKey, $MasterKey);
                                        User::logIn($username, $userpass);
                                        $testObject = new LeanObject($class);
                                        $testObject->set("content", $talk);
                                        $testObject->set("os", "WeChat");
                                        $testObject->set("postion", "by WeChat");
                                        $db->query("update `userinfo` set msg_type='',talk='' where openid='$openid'");
                                        try {
                                            $testObject->save();
                                            return "♥biubiubiu~发送成功";
                                        } catch (Exception $ex) {
                                            return "🤦‍发送失败，可能是你的绑定信息有误。";
                                        }
                                        break;
                                }

                        }
                      
                }
            }
            else{
                return "<a href='".$http_type.$_SERVER["HTTP_HOST"].dirname($_SERVER['SCRIPT_NAME'])."/bind.php?openid=$openid'>您还未绑定，点击绑定</a>";
            }
    }
});

$response = $app->server->serve(); 
$response->send(); 