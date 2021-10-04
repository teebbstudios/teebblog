# 《Symfony 5 全面开发》视频教程中的博客项目，欢迎学习。

### 《Symfony 5 全面开发》视频教程现已免费（视频内容未经允许禁止转载），视频地址：
|  网站   | 视频播放URL | 字幕 |
|  ----  | ---- | ---- |
| teebb  | https://www.teebb.com/course-detail/symfony5-quanmiankaifa | 有字幕 |
| bilibili | https://space.bilibili.com/452133624/channel/seriesdetail?sid=355222 | 有字幕 |
| youtube | https://www.youtube.com/playlist?list=PLmPHClpYq_IP75X-lE8qQb-0__W_nS69J | 有字幕 |
| csdn | https://edu.csdn.net/course/detail/35979 | 无字幕 |
| 51cto | https://edu.51cto.com/course/29253.html | 无字幕 |

### 代码使用方法
1. 下载后使用composer安装依赖的php包
```bash
    composer install  #将会安装所有依赖包
```
2. 安装docker容器
```bash
    docker compose up -d
```   

3. 修改.env文件中数据库和amqp队列连接信息

4. 初始化数据库
```bash
    php bin/console doctrine:migrations:migrate  #创建并变更数据表
    
    php bin/console doctrine:fixtures:load  #初始化数据库数据
```   

5. 启动webpack资源开发服务器
```bash
    yarn encore dev-server
```

6. 启动php调试服务器
```bash
    #如果没有安装symfony cli工具，在public目录中使用
    php -S 127.0.0.1:8000
    #如果安装了symfony cli工具，在项目根目录中使用
    symfony serve
```

7. 访问：
```bash
    #首页
    http://127.0.0.1:8000
    #管理端: 账号 admin 密码 admin
    http://127.0.0.1:8000/admin
```

### 协议
1. 本项目代码部分遵循MIT开源协议，详见根目录下`CODE_LICENSE`
2. 教程文本部分协议详见`Symfony 5全面开发视频教程文本/DOC_LICENSE`

### 广告
1. 本人全栈程序员，精通html5、css、js、bootstrap、react、react native、微信小程序、PHP(主用Symfony框架、其他框架也可以)。 
2. 承接各类私单、外包：Symfony开发、整站开发、Symfony组件开发、Symfony二次开发、小程序开发、手机APP开发等业务。
2. 公司资质，可签合同。技术交流(无偿)及业务联系QQ/微信：`443580003`