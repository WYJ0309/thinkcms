(function(doc){

    clearCookie('permit_id');
    //单个删除
    $('[data-delete]').on('click',function(){
        var id=$(this).data('delete');
        layer.confirm('确认删除？', {
            btn: ['确认','取消'] //按钮
        }, function(index) {
            layer.close(index);
            $.post('/api/delete_workpermit',{id:id}).then(function(response){
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

})(document)
