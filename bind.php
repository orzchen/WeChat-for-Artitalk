<?php
include 'config.php';
$openid = isset($_GET['openid'])?$_GET['openid']:$_POST['openid'];
$arr = $db->query("SELECT * FROM `userinfo` WHERE openid='{$openid}'")->fetch();
$AppID = $arr['AppID'];
$AppKey = $arr['AppKey'];
$MasterKey = $arr['MasterKey'];
$username = $arr['username'];
$userpass = $arr['userpass'];
$url = $arr['url'];
$class = $arr['class'];
$address = $arr['address'];
$id = $arr['id'];
if ($_POST['openid']!=null):
    $url = $_POST['url'];
    $address = $_POST['address'];
    $class = $_POST['class'];
    $AppID = $_POST['AppID'];
    $AppKey = $_POST['AppKey'];
    $MasterKey = $_POST['MasterKey'];
    $username = $_POST['username'];
    $userpass = $_POST['userpass'];
    if (!isset($id)) {
        if ($db->query("INSERT INTO `userinfo` (openid, url,address,class,AppID,AppKey,MasterKey,username,userpass) VALUES ('{$openid}', '{$url}','{$address}','{$class}','{$AppID}', '{$AppKey}','{$MasterKey}','{$username}','{$userpass}')")) {
            die('1');
        }
    } else {
        if ($db->query("update `userinfo` set url='$url', address='$address', class='$class', AppID='$AppID', AppKey='$AppKey', MasterKey='$MasterKey', username='$username', userpass='$userpass' where openid='$openid'")) {
             die('2');
        }
    }
    die();
else:
?>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
    <title>LeanCloud绑定</title>
    <link rel="stylesheet" href="https://res.wx.qq.com/open/libs/weui/2.1.3/weui.css">
    <script src="./zepto.min.js"></script>
</head>
<body ontouchstart="">
    <div class="page">
        <form class="weui-form" id="form">
            <div class="weui-form__text-area">
                <h2 class="weui-form__title">LeanCloud绑定</h2>
                <div class="weui-form__desc">
                    绑定你的LeanCloud应用信息😀
                </div>
            </div>
            <div class="weui-form__control-area">
                <div class="weui-cells__group weui-cells__group_form">

                    <div class="weui-cells weui-cells_form">

                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">网址</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="url" class="weui-input" placeholder="你的博客网址" value="<?php echo $url;
                                ?>">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">路径</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="address" class="weui-input" placeholder="你的 说说/微语 的路径" value="<?php echo $address;
                                ?>">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">openid</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="openid" readonly class="weui-input" value="<?php echo $openid;
                                ?>">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">Class</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="class" class="weui-input" placeholder="应用 Class" value="<?php echo $class;
                                ?>">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">AppID</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="AppID" class="weui-input" placeholder="应用 Keys-AppID" value="<?php echo $AppID;
                                ?>">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">AppKey</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="AppKey" class="weui-input" placeholder="应用 Keys-AppKey" value="<?php echo $AppKey;
                                ?>">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">MasterKey</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="MasterKey" class="weui-input" placeholder="应用 Keys-MasterKey" value="<?php echo $MasterKey;
                                ?>">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">用户名</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="username" class="weui-input" placeholder="用户名" value="<?php echo $username;
                                ?>">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">密码</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="userpass" class="weui-input" placeholder="用户密码" value="<?php echo $userpass;
                                ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="weui-form__opr-area">
                <a href="javascript:;" class="weui-btn weui-btn_primary" id="bind">绑定</a>
            </div>

            <div class="weui-form__extra-area">
                <div class="weui-footer">
                    <p class="weui-footer__links">
                        <p class="weui-footer__links"> <a href="https://www.icene.cn/" class="weui-footer__link">搭建教程</a>
                    </p>
                    <p class="weui-footer__text">
                         Copyright © 2020-<?php echo date("Y",time());?> 闲-客
                    </p>
                </div>
            </div>
        </form>
        <div class="js_dialog" id="Dialog" style="opacity: 0; display: none;">
            <div class="weui-mask"></div>
            <div class="weui-dialog">
                <div class="weui-dialog__bd" id="msg">
                    提示
                </div>
                <div class="weui-dialog__ft">
                    <a href="javascript:$('#Dialog').fadeOut(200);" class="weui-dialog__btn weui-dialog__btn_primary">知道了</a>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        $(function() {
            var $toast = $('#js_toast');
            var $Dialog = $('#Dialog');
            var $msg = $('#msg');
            $('#bind').on('click', function() {
                $('#bind').addClass('weui-btn_loading');
                $.post('<?php echo  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';echo $_SERVER["HTTP_HOST"].dirname($_SERVER['SCRIPT_NAME']);?>/bind.php', $('#form').serialize(), function(response) {
                    if (response == '1') {
                        $msg.html('绑定成功');
                    } else if (response == '2') {
                        $msg.html('修改成功');
                    } else {
                        $msg.html('失败，请检查输入参数是否正确');
                    }
                    $Dialog.fadeIn(200);
                    $('#bind').removeClass('weui-btn_loading');
                })
            });
            function onBridgeReady() {
                WeixinJSBridge.call('hideOptionMenu');
            }

            if (typeof WeixinJSBridge == "undefined") {
                if (document.addEventListener) {
                    document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                } else if (document.attachEvent) {
                    document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                    document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                }
            } else {
                onBridgeReady();
            }
        });
    </script>
</body>
</html>
<?php endif;?>
