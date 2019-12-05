(function(doc){
    //是否存在分类id
        var cate=$('#Category_edit')[0],
            name=cate.name,
            sort=cate.sort,
            parent_id=cate.parent_id,
            category_id=cate.category_id;
            save_route =cate.save_route;
        $("input[type=button]").on('click',function(){
            var is_zh_check=doc.querySelector('input[name=is_zh]:checked'),
                is_zh_value;
            if(is_zh_check){
                is_zh_value=is_zh_check.value;
            }else{
                is_zh_value=0;
            }
            var value={
                name:$.trim(name.value),
                is_zh:$.trim(is_zh_value),
                sort:$.trim(sort.value),
                parent_id:$.trim(parent_id.value),
                category_id:$.trim(category_id.value)
            };
            if(value.name){
                $.post(save_route?save_route.value:'/api/edit_category',value).then(function (data) {
                    if(data.success){
                        layer.msg(data.msg, {icon: 1});
                        return ;
                    }
                    if(data.fail){
                        layer.msg(data.msg, {icon: 2});
                        return ;
                    }
                },function(){
                    layer.msg('服务器繁忙，请稍候再试', {icon: 2});
                })
            }
        })


    
})(document)
