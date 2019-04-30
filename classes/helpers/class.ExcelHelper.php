<?php

/**
 * 生成Excel文件类
 *
 * 
 */
if (!defined('PHPEXCEL_ROOT')) {
    /**
     * @ignore
     */
    define('PHPEXCEL_ROOT', DIR_FS_CLASSES . 'excel/');
    require(PHPEXCEL_ROOT . 'PHPExcel/autoloader.php');
}
class Excel{
	/**
	* excel文档头(返回的行)
	*
	* 依照excel xml规范。
	* @access private
	* @var string
	*/
	private $header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?\>
	<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"
	xmlns:x=\"urn:schemas-microsoft-com:office:excel\"
	xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"
	xmlns:html=\"http://www.w3.org/TR/REC-html40\">";

	/**
	* excel页脚
	* 依照excel xml规范。
	*
	* @access private
	* @var string
	*/
	private $footer = "</Workbook>";
	
	/**
	* 文档行(行数组中)
	*
	* @access private
	* @var array
	*/
	private $lines = array ();
	/**
	* 工作表(数组)
	*
	* @access private
	* @var array
	*/
	private $worksheets = array ();
	/**
	* 单元格样式
	* @access private
	* @var string
	*/
	private $cellstyle = array();
	
	/**
	* 默认单元格数据格式
	* @access private
	* @var string
	*/
	private $default_cellformat = "String";
	
	public function __construct(){
		//设置默认样式
		$this->cellstyle['Default'] = '<Style ss:ID="Default" ss:Name="Normal">
			   <Alignment ss:Vertical="Center"/>
			   <Borders/>
			   <Font ss:FontName="宋体" x:CharSet="134" ss:Size="11" ss:Color="#000000"/>
			   <Interior/>
			   <NumberFormat/>
			   <Protection/>
			  </Style>';
	}
	/**
	* 添加单行数据
	*
	* @access private
	* @param array 1维数组
	* @todo 行创建
	*/
	private function addRow ($array)
	{
		//初始化单元格
		$cells = "";
		//构建单元格
		foreach ($array as $k => $v){
			$style_str = '';
			if(!empty($v['styleid'])){
				$style_str = 'ss:StyleID="'.$v['styleid'].'"';
			}
			$format_str = $this->default_cellformat;
			if(!empty($v['format'])){
				$format_str = $v['format'];
			}
			$cells .= "<Cell {$style_str} ><Data ss:Type=\"{$format_str}\">{$v['data']}</Data></Cell>\n";
		}
		//构建行数据
		$this->lines[] = "<Row>\n" . $cells . "</Row>\n";			
	}
	/**
	* 添加多行数据
	* @access public
	* @param array 2维数组
	* @todo 构造多行
	*/
	public function addArray ($array)
	{
		$this->lines = array();
		//构建行数据
		foreach ((array)$array as $k => $v){
			$this->addRow ($v);
		}
	}
	/**
	* 添加工作表
	* @access public
	* @param string $sheettitle 工作表名
	* @todo 构造工作表XML
	*/
	public function addWorksheet($sheettitle)
	{
		//剔除特殊字符
		$sheettitle = preg_replace ("/[\\\|:|\/|\?|\*|\[|\]]/", "", $sheettitle);
		//现在,将其减少到允许的长度
		//$sheettitle = substr ($sheettitle, 0, 50);
		$this->worksheets[] = "\n<Worksheet ss:Name=\"$sheettitle\">\n<Table ss:DefaultRowHeight=\"20\">\n".
							  "<Column ss:Index=\"1\" ss:AutoFitWidth=\"0\"/>\n".
							  implode ("\n", $this->lines).
							  "</Table>\n</Worksheet>\n";
	}
	/**
	* 设置单元格样式
	* 
	* @access public
	* @param array 样式数组例如： array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1'));
	* 当id为Default时，为表格的默认样式
	*/
	public function setStyle ($style_arr){
		if(empty($style_arr)){
			return false;
		}
		$id = $style_arr['id'];
		unset($style_arr['id']);
		$style_str = "<Style ss:ID=\"$id\">";
		foreach($style_arr as $k=>$v){
			$tmp = '';
			foreach((array)$v as $k_item=>$v_item){
				$tmp .= (" ss:$k_item=\"$v_item\"");
			}
			$style_str .= "<$k ".$tmp.'/>';
		}
		
		$this->cellstyle[$id] = $style_str.'</Style>';
	}
	/**
	* 设置默认单元格格式
	* 
	* @access public
	* @param string
	*/
	public function setDefaultFormat ($format_str){
		if(empty($style_arr)){
			return false;
		}
		$this->default_cellformat = $format_str;
	}
	/**
	* 生成excel文件
	* 最后生成excel文件,并使用header()函数来将它交付给浏览器。
	* @access public
	* @param string $filename 文件名称
	*/
	public function generateXML ($filename)
	{
		$encoded_filename = urlencode($filename);
		$encoded_filename = str_replace("+", "%20", $encoded_filename);
		//头
		$ua = $_SERVER["HTTP_USER_AGENT"];
		header("Content-Type: application/vnd.ms-excel");
		if(preg_match("/MSIE/", $ua)){
			header('Content-Disposition: attachment; filename="'.$encoded_filename.'.xls"');
		}else if(preg_match("/Firefox/", $ua)){
			header('Content-Disposition: attachment; filename*="utf8\'\''.$filename.'.xls"');
		}else{
			header('Content-Disposition: attachment; filename="'.$filename.'.xls"');
		}
		header('Cache-Control: max-age=0');
		echo stripslashes ($this->header);
		//样式
		echo "\n<Styles>";
		foreach((array)$this->cellstyle as $k=>$v){
			echo "\n".$v;
		}
		echo "\n</Styles>";
		//工作表
		echo implode ("\n", $this->worksheets);
		echo $this->footer;
	}

	/**
	 * 转码函数
	 *
	 * @param mixed $content
	 * @param string $from
	 * @param string $to
	 * @return mixed
	 */
    public function charset($content, $from='gbk', $to='utf-8') {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($content)) {
            //如果编码相同则不转换
            return $content;
        }
        if (function_exists('mb_convert_encoding')) {
 			if (is_array($content)){
				$content = var_export($content, true);
				$content = mb_convert_encoding($content, $to, $from);
				eval("\$content = $content;");return $content;
			}else {
				return mb_convert_encoding($content, $to, $from);
			}
        } elseif (function_exists('iconv')) {
 			if (is_array($content)){
				$content = var_export($content, true);
				$content = iconv($from, $to, $content);
				eval("\$content = $content;");return $content;
			}else {
				return iconv($from,$to,$content);
			}
        } else {
            return $content;
        }
    }

	/**
	 * 获得excel文件
	 * @param $fileName
	 * @return array
	 */
	public function getExcel($fileName){

		$returnExcel = [];
		$phpExcel = PHPExcel_IOFactory::load($fileName);//$PHPReader->load($fileName);  //加载要读取的文件
		$currentSheet = $phpExcel->getSheet(0);      //取文件中的第一个表
		$allColumn = $currentSheet->getHighestColumn();  //获取列数
		$allRow = $currentSheet->getHighestRow();      //获取行数

		for($currentRow = 2; $currentRow<=$allRow; $currentRow++){  //通过所有行数和列数循环读取表中的所有值
			for($currentColumn='A'; $currentColumn<=$allColumn; $currentColumn++){
				$address = $currentColumn.$currentRow;
				$returnExcel[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
				//echo $currentSheet->getCell($address)->getValue()."\t";  //输出取到的表格中的值
			}
			//echo "\n";
		}
		return $returnExcel;
	}


    /**
     * 读取excel文件
     * @param $fileName
     * @return array
     */
    public function readExcel($fileName)
    {
        $returnExcel = [];
        $phpExcel = PHPExcel_IOFactory::load($fileName);//$PHPReader->load($fileName);  //加载要读取的文件
        $currentSheet = $phpExcel->getSheet(0);      //取文件中的第一个表
        $allColumn = $currentSheet->getHighestColumn();  //获取列数
        $allRow = $currentSheet->getHighestRow();      //获取行数
        $allColumn ++;
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {  //通过所有行数和列数循环读取表中的所有值
            for ($currentColumn = 'A'; $currentColumn != $allColumn; $currentColumn++) {
                $address = $currentColumn . $currentRow;
                $returnExcel[$currentRow][$currentColumn] = $this->convertFullWidthToHalfWidth($currentSheet->getCell($address)->getValue());
                //echo $currentSheet->getCell($address)->getValue()."\t";  //输出取到的表格中的值
            }
            //echo "\n";
        }
        return $returnExcel;
    }
	
	/**
	 * 读取excel文件(获得的文本的信息)
	 * 如时间不会变成科学计数的形式
	 * 与readExcel差别
	 * readExcel：$currentSheet->getCell($address)->getValue()
	 * readFormatExcel：$currentSheet->getCell($address)->getFormattedValue()
	 *
	 * @param $fileName
	 * @return array
	 */
	public function readFormatExcel($fileName)
	{
		$returnExcel = [];
		$phpExcel = PHPExcel_IOFactory::load($fileName);//$PHPReader->load($fileName);  //加载要读取的文件
		
		$currentSheet = $phpExcel->getSheet(0);      //取文件中的第一个表
		$allColumn = $currentSheet->getHighestColumn();  //获取列数
		$allRow = $currentSheet->getHighestRow();      //获取行数
		$allColumn ++;
		
		for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {  //通过所有行数和列数循环读取表中的所有值
			for ($currentColumn = 'A'; $currentColumn != $allColumn; $currentColumn++) {
				$address = $currentColumn . $currentRow;
				
				$returnExcel[$currentRow][$currentColumn] = $this->convertFullWidthToHalfWidth($currentSheet->getCell($address)->getFormattedValue());
				//echo $currentSheet->getCell($address)->getValue()."\t";  //输出取到的表格中的值
			}
			//echo "\n";
		}
		return $returnExcel;
	}
    
    /**
     * 读取excel文件
     * @param $fileName
     * @return array
     */
    public function readWarehouseExcel($fileName)
    {
        $returnExcel = [];
        $phpExcel = PHPExcel_IOFactory::load($fileName);//$PHPReader->load($fileName);  //加载要读取的文件
        $currentSheet = $phpExcel->getSheet(0);      //取文件中的第一个表
        $allColumn = $currentSheet->getHighestColumn();  //获取列数
        $allRow = $currentSheet->getHighestRow();      //获取行数
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {  //通过所有行数和列数循环读取表中的所有值
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                $address = $currentColumn . $currentRow;
                $returnExcel[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
                //echo $currentSheet->getCell($address)->getValue()."\t";  //输出取到的表格中的值
            }
        }
        return $returnExcel;
    }

     public function uploadExcel($fileKey,$excelName="")
     {
         $status = "fail";
         $errorMessage = '';
         try {
             $dir = Zc::C('default.dir') . 'Excel/';
             $upload = HelperFactory::getUploadFileHelper();
             $upload->set('default_dir', $dir);
             $upload->set('allow_type', array('xlsx', 'xls','csv'));
             if (!empty($_FILES[$fileKey]['name'])) {
                 $fileNameArray = explode('.', $_FILES[$fileKey]['name']);
                 if($excelName){
                     $upload->set('file_name', $excelName. "." . $fileNameArray[1]);
                 }else {
                     $upload->set('file_name', iconv("UTF-8", "gb2312", $fileNameArray[0]) . '-' . date('YmdHis') . "." . $fileNameArray[1]);
                 }
                 $result = $upload->upOtherfile($fileKey);
                 if (!$result) {
                     $errorMessage = $upload->error;
                 } else {
                     $status = "success";
                 }
             } else {
                 $errorMessage = '上传文件为空';
             }
             $excelName = $upload->get('file_name');
             $excelPath = DIR_FS_DOCUMENT_ROOT . '/data/upload/' . $dir . $excelName;
         } catch (Exception $e) {
             $errorMessage = $upload->error;
         }finally {
             return array(
                 'excelName' => $excelName,
                 'excelPath' => $excelPath,
                 'status' => $status,
                 'errorInfo' => $errorMessage
             );
         }
     }

     public function getExcelPath($excelName)
     {
         $dir = Zc::C('default.dir') . 'Excel/';
         return DIR_FS_DOCUMENT_ROOT . '/data/upload/' . $dir . $excelName;
     }

    /**
     * 读取表格多个单元
     */
     public function readMultipleSheetOfExcel($fileName)
     {
         $returnExcel = [];
         $phpExcel = PHPExcel_IOFactory::load($fileName);//$PHPReader->load($fileName);  //加载要读取的文件
         $allSheet = $phpExcel->getSheetCount();
         $search = array(" ","　","\n","\r","\t");
         $replace = array("","","","","");
         for ($sheetIndex = 0; $sheetIndex < $allSheet; $sheetIndex++) {
             $currentSheet = $phpExcel->getSheet($sheetIndex);      //取文件中的第一个表
             $allColumn = $currentSheet->getHighestColumn();  //获取列数
             $allRow = $currentSheet->getHighestRow();      //获取行数
             for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {  //通过所有行数和列数循环读取表中的所有值

                 for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                     $address = $currentColumn . $currentRow;
                     $returnExcel[$sheetIndex][$currentRow][$currentColumn] =  $currentSheet->getCell($address)->getValue();
                     //str_replace($search, $replace, $currentSheet->getCell($address)->getValue());
                 }
             }
         }
         return $returnExcel;
     }

    /**
     * 转化非半角空格为半角空格并去除两侧空格
     */
    private function convertFullWidthToHalfWidth($str)
    {
    	$pattern = "/\s+/u";
    	return trim(preg_replace($pattern, ' ', $str));
    }

}
?>