<?php
namespace app\admin\controller\api;
use think\Controller;
use think\Request;

class ConsultApi extends Controller
{
    public function access(){
        if(!isLogin()){
            $this->redirect(ADMIN_ROUTE.'login');
        }
    }

    //添加咨询日志
    public function index(Request $request){
        $data=$request->param();
        db('consult')->insert($data);

        //获取IP地址
        $url='http://api.map.baidu.com/location/ip?ak=iVkjtqXl8kZhWzckddNkX1oUPHfTkTUI&ip='.getip().'&coor=bd09ll';
        $url='http://api.map.baidu.com/location/ip?ak=iVkjtqXl8kZhWzckddNkX1oUPHfTkTUI&ip='.'182.150.27.221'.'&coor=bd09ll';
        $ip = json_decode(file_get_contents($url));
        $address='客户位置:'.$ip->address;
        sendMail(['6325610@qq.com'=>'可是','381508990@qq.com'=>'刘皮'],'兴蜀大宗sypme',$address);
    }
    //删除文章
    public function delete(Request $request){
        $id=$request->param('id');
        $id=explode(",",$id);
        if($id){
            if(db('consult')->delete($id)){
                return ['success'=>true,'msg'=>'删除成功'];
            }
            return ['fail'=>true,'msg'=>'删除失败'];
        }
    }
    //phpoffice/phpspreadsheet

    public function exportExcel(Request $request){
        $objPHPExcel = new \PHPExcel();
        // 操作第一个工作表
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置sheet名
        $objPHPExcel->getActiveSheet()->setTitle('xx列表');

        // 设置表格宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(60);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

        // 列名表头文字加粗
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
        // 列表头文字居中
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // 列名赋值
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '编号');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', '用户名');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', '邮箱');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', '手机号');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', '工作签证');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', '地区');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', '内容');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', '咨询时间');

        $field = ['id','username', 'email', 'phone', 'work_permit','address', 'content','time'];//第二行列字段内容
        $res = db('consult')->select();
        // 数据起始行
        $row_num = 2;
        // 向每行单元格插入数据
        foreach($res as $value)
        {
            // 设置所有垂直居中
            $objPHPExcel->getActiveSheet()->getStyle('A' . $row_num . ':' . 'J' . $row_num)->getAlignment()
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            // 设置价格为数字格式
            $objPHPExcel->getActiveSheet()->getStyle('F' . $row_num)->getNumberFormat()
                ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            // 居中
            $objPHPExcel->getActiveSheet()->getStyle('A' . $row_num . ':' . 'F' . $row_num)->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            // 设置单元格数值
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row_num, $value['id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row_num, $value['username']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row_num, $value['email']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row_num, $value['phone']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $row_num, $value['work_permit']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row_num, $value['address']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row_num, $value['content']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $row_num, $value['time']);
            $row_num++;
        }

        $outputFileName = 'consult_' . time() . '.xls';//导出的文件名
        $xlsWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        header("Content-Type: application/force-download");//告诉浏览器强制下载
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . $outputFileName . '"');//attachment作为附件下载,inline在线下载,filename设置文件名
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");//设置浏览器响应缓存
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $xlsWriter->save("php://output");

        exit();
    }
}
