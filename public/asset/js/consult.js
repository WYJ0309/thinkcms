(function(doc){
    var delete_select=$('#delete_select');

    //编辑
    $('[data-edit]').on('click',function(){
        var id=$(this).data('edit');
        setCookie('consult_id',id);
        location.href=config.admin_consultsEdit_router;
    })
    //单个删除
    $('[data-delete]').on('click',function(){
        var id=$(this).data('delete');
        layer.confirm('确认删除？', {
            btn: ['确认','取消'] //按钮
        }, function(index) {
            layer.close(index);
            $.post('/api/delete_consult',{id:id}).then(function(response){
                if(response.success){
                    location.reload();
                }
                if(response.fail){
                    layer.msg(response.msg, {icon: 2});
                }
            },function(){
                layer.msg('服务器繁忙，请稍候再试', {icon: 2});
            })
        });
    })

    //监听checkbox
    $('table input[type=checkbox]').on('change',function () {
        var checkbox=$('table input[type=checkbox]:checked');
        if(checkbox.length){
            delete_select.removeClass('disabled');
        }else{
            delete_select.addClass('disabled');
        }
    })

    //批量删除
    delete_select.on('click',function(){
        if($(this).hasClass('disabled')){
            return ;
        }
        var arr=[];
        $('table td input[type=checkbox]:checked').each(function(){
            arr.push(this.value);
        })
        arr=arr.join(',');
        console.log(arr);

        layer.confirm('确认删除？', {
            btn: ['确认','取消'] //按钮
        }, function(index) {
            layer.close(index);
            $.post('/api/delete_consult',{id:arr}).then(function(response){
                if(response.success){
                    layer.msg(response.msg, {icon: 1});
                    setTimeout(function () {
                        location.reload();
                    },1000)
                    return ;
                }
                if(response.fail){
                    layer.msg(response.msg, {icon: 2});
                }
            },function(){
                layer.msg('服务器繁忙，请稍候再试', {icon: 2});
            })
        });

    })

    //全选
    $('#selectall').on('change',function(){
        if(this.checked){
            $('table td input[type=checkbox]').attr('checked',true);
        }else{
            $('table td input[type=checkbox]').attr('checked',false);
        }
    })

})(document)
