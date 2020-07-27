## 🤔WeChat-for-Artitalk

**🤖微信公众号服务端 for Artitalk**🤖

### 📚说明

------

1. 使用 [LeanCloud PHP SDK](https://github.com/leancloud/php-sdk) 和 [EasyWeChat](https://www.easywechat.com/) 开发。
2.  最重要的是使用了 [iLay1678](https://github.com/iLay1678) 开发的 [wechat_for_handsome](https://github.com/iLay1678/wechat_for_handsome) 项目中的EasyWeChat的代码。非常感谢他的开源。
3. 用于在微信公众号端发送由 [Uncle_drew](https://cndrew.cn/) 开发的 [Artitalk](https://artitalk.js.org/) 的说说/微语。

### 👉环境需求

------

- PHP >= 7.1
- PHP cURL 扩展
- PHP OpenSSL 扩展
- PHP SimpleXML 扩展
- PHP fileinfo 扩展
- PHP PDO_MYSQL 扩展

### 👾使用方法

------

#### 😀安装

- 注册微信公众号，在【开发】-【基本配置】中获取你的 开发者ID(AppID) 和 开发者密码(AppSecret)，启用服务器配置，获取 令牌(Token) 和 消息加解密密钥(EncodingAESKey)。

- [下载](https://github.com/orzchen/WeChat-for-Artitalk/releases) WeChat.for.Artitalkzip。
- 上传解压到你的服务器。
- 打开 ./install.php 按照提示进行安装，安装完成后会在目录下生成 config.php 和 install.lock 两个文件，请注意这两个文件的权限为644。

#### 😃使用

- 打开【开发】-【基本配置】-【服务器配置】配置服务器地址(URL)为 ./server.php ，和消息加解密方式。
- 在公众号中回复【绑定】，按提示绑定你的 LeanCloud 应用信息。 
- 回复【帮助】查看使用方法。

### 👻更新日志

------

- v1.0：支持发送 文本、图片、链接、地理位置。图片使用URL为微信系统生成的URL，HTTP头，没有防盗链，但是对配置SSL的站点会出现浏览器的警告。更换为 HTTPS 头则会防盗链。/(ㄒoㄒ)/~~


### 👽效果

------

**扫码体验**	[【**Demo**】](https://orzchen.github.io/demo.html)（扒的Uncle_drew的Demo）

![扫码体验](https://cdn.jsdelivr.net/gh/orzchen/Blog/images/20200726215129.jpg)



