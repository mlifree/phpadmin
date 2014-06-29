<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>php admin system</title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/bootstrap.min.css">
        <script src="<?php echo base_url(); ?>static/js/jquery.1.7.2.min.js"></script>
        <script src="<?php echo base_url(); ?>static/js/bootstrap.min.js"></script>
        <?php if(!empty($extra_header)){echo $extra_header;} ?>
        <style>
            #codeigniter_profiler code{
                word-break: break-all;
                white-space: normal;
            }
            #header-navbar{
                border-radius: 0px;
            }
            #content{
                padding: 0px 5px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-default" role="navigation" id="header-navbar">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">phpAdmin</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php foreach ($register_menu as $name => $menu): ?>
                        <?php if (is_int($name)): ?>
                            <li class="activex"><a href="<?php echo $menu["url"]; ?>"><?php echo $menu["text"]; ?></a></li>
                        <?php else: ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $name ?> <b class="caret"></b></a>
                                <ul class="dropdown-menu" role="menu">
                                    <?php foreach ($menu as $menu2): ?>
                                        <li class="activex"><a href="<?php echo $menu2["url"]; ?>"><?php echo $menu2["text"]; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <!--                <form class="navbar-form navbar-left" role="search">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Search">
                                    </div>
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </form>-->
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> 权限管理 <b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#"><span class="glyphicon glyphicon-user"></span> 用户</a></li>
                            <li><a href="#"><span class="glyphicon glyphicon-plus-sign"></span> 添加用户</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><span class="glyphicon glyphicon-tower"></span> 用户组</a></li>
                            <li><a href="#"><span class="glyphicon glyphicon-plus-sign"></span> 添加用户组</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><span class="glyphicon glyphicon-th"></span> 权限列表</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">用户昵称 <b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#"><span class="glyphicon glyphicon-info-sign"></span> 个人信息</a></li>
                            <li><a href="#"><span class="glyphicon glyphicon-lock"></span> 修改密码</a></li>
                            <li><a href="#"><span class="glyphicon glyphicon-log-out"></span> 退出</a></li>
                            <li class="divider"></li>
                            <li><a href="#" target="_blank"><span class="glyphicon glyphicon-home"></span> 网站主页</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <div id="content">