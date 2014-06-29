<?php include "header.php";?>
<style>
    .btn-sm{
        padding: 4px 10px;
    }
    .navbar{
        margin-bottom: 5px;
    }
    .success{
        color: #3c763d;
    }
    .failed{
        color: #a94442;
    }
    .normal{
        color: #333;
    }
    .action-btn-group .dropdown-menu{
        right: 0px;
        left: auto;
    }
    #filter-top-widget{
        clear: both;
        overflow: hidden;
        height: auto;
        padding: 5px 15px;
    }
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td{
        vertical-align: middle;
    }
    .pagination{
        margin: 0px;
    }
    #actions{
        float: left;
    }
    #actions select{
        float: left;
    }
    #actions .submit-action{
        float: left;
        margin-left: 3px;
    }
    #data-list{
        float: left;
        padding: 0px;
    }
    #filter-right-widget{
        float: left;
        padding-right: 0px
    }
    .search-field-btn{
        cursor: pointer;
    }
    .clear-search-btn{
        border-left: none;
    }
</style>
<div class="row" style="margin: 0px;padding: 0px;">
<div class="col-md-10 panel panel-default" id="data-list">
    <div id="filter-top-widget" class="panel-heading">
        <div id="actions">
            <?php include "actions.php" ?>
        </div>
        <?php if($display_search_form):?>
        <div id="search-btn" class="col-md-3">
            <form method="get" action="" class="search-field-form">
                <div class="input-group">
                    <input type="text" name="_s" class="form-control input-sm" value="<?php echo $_s;?>">
                    <?php if(!empty($_s)):?>
                    <a class="input-group-addon clear-search-btn" href="<?php echo $current_url;?>"><span class="glyphicon glyphicon-ban-circle"></span></a>
                    <?php endif;?>
                    <span class="input-group-addon search-field-btn"><span class="glyphicon glyphicon-search"></span></span>
                  </div>
              </form>
        </div>
        <?php endif;?>
        <div id="buttons" class="pull-right">
            <?php
            foreach ($buttons as $name => $button):$class = "";
                $url = is_string($button) ? $button : $button[0];
                $class = $button[1];
                ?>
                <a href="<?php echo $url ?>" class="btn btn-sm btn-default <?php echo $class ?>"><?php echo $name; ?></a>
            <?php endforeach; ?>
            <?php if (!$disable_add): ?>
                <a href="<?php echo $current_url ?>/add" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;添加<?php echo $verbose_name ?></a>
            <?php endif ?>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr class="list-item-header">
                <th><input type="checkbox" class="selected-all" name="selected-all" value=""></th>
                <?php foreach ($field_info as $field => $field_obj): ?>
                    <th title="<?php echo $field ?>" id="th_<?php echo $field ?>" <?php
                    if ($field_obj['editable']) {
                        echo 'editable';
                    }
                    ?> field-type="<?php echo $field_obj["field_type"] ?>"><?php echo $field_obj["label"] ?></th>
                    <?php endforeach ?>
                    <th style="width:90px;">操作</th>
            </tr>
        </thead>
        <tbody class="list-item-data">
            <?php foreach ($data as $item): ?>

                <tr>
                    <td><input type="checkbox" class="checked-item" name="checked-item[]" value="<?php echo $item[$primary_field] ?>"></td>
                    <?php foreach ($item as $field => $value):if ($field == $primary_field) continue; ?>
                        <?php if (substr($field, 0, 2) != "#!"): ?>
                            <td data-value="<?php echo $value; ?>">
                                <?php if (in_array($field, $list_display_links)): ?>
                                    <a target="_blank" href="<?php echo current_url() . "/edit/" . $item[$primary_field]; ?>"><?php echo $value ?></a></td>
                            <?php else: ?>
                                <?php
                                if (!isset($field_info[$field])) {
                                    continue;
                                }
                                if ($field_info[$field]["field_type"] == "FileSizeField") {
                                    echo $item["#!" . $field . "!#"];
                                } elseif ($field_info[$field]["field_type"] == "BoolField") {
                                    echo $value ? '<span class="success glyphicon glyphicon-ok-sign"></span>' : '<span class="failed glyphicon glyphicon-remove-sign"></span>';
                                } elseif ($field_info[$field]["field_type"] == "ImageField") {
                                    $width = $field_info[$field]["field_object"]->args["width"];
                                    $height = $field_info[$field]["field_object"]->args["height"];
                                    $width_str = 'width="' . $width . '"';
                                    if ($height) {
                                        $width_str.=' height="' . $height . '"';
                                    }
                                    if (substr($value, 0, 4) == "http") {
                                        echo '<img ' . $width_str . ' src="' . $value . '">';
                                    } else {
                                        echo '<img ' . $width_str . ' src="' . base_url() . $value . '">';
                                    }
                                } elseif ($field_info[$field]["field_type"] == "URLField") {
                                    echo '<a href="' . $value . '" target="_blank">' . $value . '</a>';
                                } elseif ($field_info[$field]["field_type"] == "EmailField") {
                                    echo '<a class="normal" href="mailto:' . $value . '">' . $value . '</a>';
                                } else {
                                    echo $value;
                                }
                                ?>
                                </td>
                            <?php endif ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td>
                        <div class="btn-group action-btn-group">
                            <a href="#" action="delete_selected" title="删除此行" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-trash"></span></a>
                            <a target="_blank" action="edit" href="<?php echo current_url() . "/edit/" . $item[$primary_field]; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-edit"></span></a>
                            <?php if($actions):?>
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">&nbsp;&nbsp;<span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php foreach ($actions as $key => $value): ?>
                                        <li><a title="<?php echo is_int($key) ? $value : $key; ?>" action="<?php echo $value ?>" href="#"><?php echo is_int($key) ? $value : $key; ?></a></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                            <?php endif;?>
                        </div>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th><input type="checkbox" class="selected-all" name="selected-all" value=""></th>
                <th colspan="<?php echo sizeof($field_info) + 1; ?>">
                    <div class="pull-right" style="height: 34px;">
                        <?php echo $pages ?>
                    </div>
        </th>
            </tr>
        </tfoot>
    </table>
    
    
</div>
<div id="filter-right-widget" class="col-md-2">
    <div class="panel panel-default">
        <div class="panel-heading">过滤器</div>
        <div class="panel-body">
          <div class="list-group">
            <a href="#" class="list-group-item active">
              Cras justo odio
            </a>
            <a href="#" class="list-group-item">Dapibus ac facilisis in</a>
            <a href="#" class="list-group-item">Morbi leo risus</a>
            <a href="#" class="list-group-item">Porta ac consectetur ac</a>
            <a href="#" class="list-group-item">Vestibulum at eros</a>
          </div>
            <div class="list-group">
            <a href="#" class="list-group-item active">
              Cras justo odio
            </a>
            <a href="#" class="list-group-item">Dapibus ac facilisis in</a>
            <a href="#" class="list-group-item">Morbi leo risus</a>
            <a href="#" class="list-group-item">Porta ac consectetur ac</a>
            <a href="#" class="list-group-item">Vestibulum at eros</a>
          </div>
        </div>
    </div>
</div>
</div>
<script>
    var field_info = <?php echo json_encode($field_info, JSON_UNESCAPED_UNICODE); ?>;
    //选中全部
    $(".selected-all").click(function() {
        var _checked = $(this).is(":checked");
        $('.select-all').attr("checked", _checked);
        $('.checked-item').attr("checked", _checked);
        return true;
    });

    //action 处理
    function post_action(action, action_name, id_list) {
        if (action === "") {
            alert("请选择你要进行的操作！");
            return false;
        }
        if (confirm("你确定执行  " + action_name + " 操作？")) {
            $.post("<?php echo $current_url ?>?_admin_opt=action&action=" + action, {"model_class": "<?php echo $model_class ?>", "id_list": id_list}, function() {

            });
        }
    }
    // 点击执行按钮
    $(".submit-action").click(function() {
        var select_action = $("select[name=actions]"),
                action = select_action.val(),
                action_name = select_action.find(":selected").text(),
                checked_item = $('input.checked-item:checked');
        if (!checked_item.length) {
            alert("请选中你要操作的数据行！");
            return false;
        }
        var id_list = [];
        checked_item.each(function() {
            id_list.push(this.value);
        });
        post_action(action, action_name, id_list);

        return false;
    });

    $(".action-btn-group a").click(function() {
        var action = $(this).attr('action'),
                action_name = $(this).attr("title");
        if (action == "edit")
            return true;
        var id = $(this).parents("td").siblings(0).children("input").val();
        post_action(action, action_name, [id]);
    });

    //可编辑列处理
    $(".list-item-data td").click(function(e) {
        if (e.target.tagName == 'A') {
            return true;
        }
        var _this = $(this),
                show_edit_value = "false",
                show_edit = _this.attr("show-edit") == "true";
        if (show_edit)
            return true;
        var value = _this.attr("data-value"),
                _index = _this.index(),
                field = $(".list-item-header th:eq(" + _index + ")"),
                editable = field.attr("editable") != undefined,
                field_type = field.attr("field-type"),
                _html = "";
        if (!editable)
            return true;
        if (field_type == "CharField") {
            _html = '<input type="text" name="" class="form-control" value="' + value + '" />';
            show_edit_value = "true";
        } else if (field_type == "BoolField") {
            if (value == "1") {
                _this.attr("data-value", '0');
                _html = '<span class="failed glyphicon glyphicon-remove-sign"></span>';
            } else {
                _this.attr("data-value", '1');
                _html = '<span class="success glyphicon glyphicon-ok-sign"></span>';
            }
        }
        _this.html(_html);
        _this.attr("show-edit", show_edit_value);
        return true;
    }).blur(function() {
        alert(2);
    });
    //点击搜索按钮提交表单
    $('.search-field-btn').click(function(){
        $('.search-field-form').submit();
    });
    $('.search-field-form').on('submit',function(){
        
    });
</script>