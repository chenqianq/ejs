<?php
/**
 * 对源代码进行加密
 */

class SourceCodeEncryption {

    /**
     * 目录
     */
    private $path;

    /**
     * 保存加密后文件目录
     */
    private $savePath;

    /**
     * 构造函数
     */
    public function __construct() {

    }

    /**
     * 
     */
    private function getAllFile($path) {
        if ( is_dir( $path ) ) {
            if ( $dh = @opendir( $path ) ) {
                while ( ($file = readdir( $dh ) ) !== false ) {
                    
                    if ( $file == '.' || $file == '..' || strtolower($file) == '.svn' ) {
                        continue;
                    }

                    $filename = $path . "\\" . $file;
                    
                    if ( is_dir($filename) ) {
                        $path_ = str_replace($this -> path, $this -> savePath, $filename);
                        @mkdir($path_, 0777, true);                        
                        $this -> getAllFile($filename);
                    }

                    if ( filetype($filename) == 'file' ) {

                        $dest = str_replace($this -> path, $this -> savePath, $filename);
                        
                        if ( stripos($filename,'.php') ) {
                            $this -> encrypt($filename, $dest);
                        } else {
                            copy($filename, $dest);
                        }
                    }

                    // echo 'filename: ' . $file . ' : filetype: ' . filetype($this -> path . $file) . "<br />";
                }
                closedir($dh);
            }
        }
    }



    /**
     * 返回随机字符串
     * @return string
     */
    private function randStr() {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        return str_shuffle($str);
    }

    /**
     * 加密源代码
     * @param string $file 源文件
     * @param string $dest 加密后文件
     */
    public function encrypt($file, $dest) {
        
        $randKey1 = $this -> randStr();
        $randKey2 = $this -> randStr();
        $fileContent = file_get_contents($file);
        $base64Str = base64_encode($fileContent);

        $value = strtr($base64Str, $randKey1, $randKey2);
        $value = $randKey1 . $randKey2 . $value;

        $q1 = "O00O0O";  
        $q2 = "O0O000";
        $q3 = "O0OO00";
        $q4 = "OO0O00";  
        $q5 = "OO0000";  
        $q6 = "O00OO0";

        $str = '$'.$q6.'=urldecode("%6E1%7A%62%2F%6D%615%5C%76%740%6928%2D%70%78%75%71%79%2A6%6C%72%6B%64%679%5F%65%68%63%73%77%6F4%2B%6637%6A");$'.$q1.'=$'.$q6.'{3}.$'.$q6.'{6}.$'.$q6.'{33}.$'.$q6.'{30};$'.$q3.'=$'.$q6.'{33}.$'.$q6.'{10}.$'.$q6.'{24}.$'.$q6.'{10}.$'.$q6.'{24};$'.$q4.'=$'.$q3.'{0}.$'.$q6.'{18}.$'.$q6.'{3}.$'.$q3.'{0}.$'.$q3.'{1}.$'.$q6.'{24};$'.$q5.'=$'.$q6.'{7}.$'.$q6.'{13};$'.$q1.'.=$'.$q6.'{22}.$'.$q6.'{36}.$'.$q6.'{29}.$'.$q6.'{26}.$'.$q6.'{30}.$'.$q6.'{32}.$'.$q6.'{35}.$'.$q6.'{26}.$'.$q6.'{30};eval($'.$q1.'("'.base64_encode('$'.$q2.'="'.$value.'";eval(\'?>\'.$'.$q1.'($'.$q3.'($'.$q4.'($'.$q2.',$'.$q5.'*2),$'.$q4.'($'.$q2.',$'.$q5.',$'.$q5.'),$'.$q4.'($'.$q2.',0,$'.$q5.'))));').'"));';  

        $str = '<?php '."\n" . $str . "\n".' ?>';
        $fpp = fopen($dest, 'w');
        fwrite($fpp, $str);
        fclose($fpp);
    }
    

    /**
     * 加密所有文件
     */
    public function encryptAllFile() {
        set_time_limit(0);
        $this -> getAllFile($this -> path);
        return true;
    }

    /**
     * 设置目录
     */
    public function setPath($path) {
        $this -> path = $path;
        $this -> createSavePath();
    }

    /**
     * 创建保存目录
     */
    private function createSavePath() {
        if ( is_dir($this -> path) ) {
            $this -> savePath = $this -> path . '_bak';
            @mkdir($this -> savePath, 0777, true);
        }
    }

}