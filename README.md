# 《Symfony 5 全面开发》视频教程中的博客项目，欢迎学习。

### 《Symfony 5 全面开发》视频教程现已免费（视频内容未经允许禁止转载），视频地址：
|  网站   | 视频播放URL  |
|  ----  | ----  |
| teebb  | https://www.teebb.com/course-detail/symfony5-quanmiankaifa |
| bilibili | https://www.bilibili.com/medialist/play/452133624?business=space_series&business_id=355222 |
| youtube | https://www.youtube.com/playlist?list=PLmPHClpYq_IP75X-lE8qQb-0__W_nS69J |

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

## 本项目代码遵循MIT开源协议，欢迎下载学习。