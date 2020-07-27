<?php

error_reporting(0);
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set("PRC");

define("USER","owner");//你的GitHub的用户名

define("REPO","repo");//必须是上面用户名下的 公开仓库

define("MAIL","yumusb@foxmail.com");//邮箱无所谓，随便写

define("TOKEN","access_token");

function upload_github($filename, $content)
{   
    $url = "https://api.github.com/repos/" . USER . "/" . REPO . "/contents/" . $filename;
    $ch = curl_init();
    $defaultOptions=[
        CURLOPT_URL => $url,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST=>"PUT",
        CURLOPT_POSTFIELDS=>json_encode([
            "message"=>"uploadfile",
            "committer"=> [
                "name"=> USER,
                "email"=>MAIL,
            ],
            "content"=> $content,
        ]),
        CURLOPT_HTTPHEADER => [
            "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language:zh-CN,en-US;q=0.7,en;q=0.3",
            "User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36",
            'Authorization:token '.TOKEN,
        ],
    ];
    curl_setopt_array($ch, $defaultOptions);
    $chContents = curl_exec($ch);
    curl_close($ch);
    return $chContents;
}

function dlfile($file_url, $save_to)
{
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_POST, 0); 
 curl_setopt($ch,CURLOPT_URL,$file_url); 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
 $file_content = curl_exec($ch);
 curl_close($ch);
 $downloaded_file = fopen($save_to, 'w');
 fwrite($downloaded_file, $file_content);
 fclose($downloaded_file);
}

$filename = date('Y') . '/' . date('m') . '/' . date('d') . '/' . md5(time().mt_rand(10,1000)) . ".png";
$tmpname = md5(time().mt_rand(10,1000)).'.jpg';
$savefile = './tmp/'.$tmpname;
dlfile($imgurl,$savefile); 
$content_img = base64_encode(file_get_contents($savefile));

$res = json_decode(upload_github($filename, $content_img), true);
$remoteimg = 'https://cdn.jsdelivr.net/gh/' . USER . '/' . REPO . '@latest/' . $res['content']['path']; unlink($savefile);
unlink($savefile);
?>