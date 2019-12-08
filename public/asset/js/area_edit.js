(function(doc){

    //上传成功
    if(getCookie('add_area')=='1'){
        layer.msg('保存成功',{icon: 1});
    }
    //上传失败
    if(getCookie('add_area')=='0'){
        layer.msg('保存失败',{icon: 2});
    }
    clearCookie('add_area');

    //是否存在分类id
    var Col=$('#area')[0],
        name=Col.name_zh;

    //提交表单
    $("#submit").on('click',function(){
        if($.trim(name.value)){
            return true;
        }else{
            layer.msg('栏目名称必须填写',{icon:2})
            return false;
        }
    })


})(document)
