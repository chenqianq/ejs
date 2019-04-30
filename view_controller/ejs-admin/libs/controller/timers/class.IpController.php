<?php


class IpController extends ZcController {


    private $urlHelper;
    private $ip = 0;
    private $savePath;
    private $wrap = "\r\n";


    public function __construct($route) {
        parent::__construct($route);    
        $this -> urlHelper = HelperFactory::getUrlHelper();        
        $this -> savePath = DIR_FS_CLASSES . 'helpers/localip/ip_white_list.inc';
    }


    /**
     * 把公网ip存储在文件中
     */
    public function putIp() {

        $ipStr = $this -> urlHelper -> getValue('ip_str');

        $this -> ip = HelperFactory::getEncryptionHelper()->decrypt($ipStr, MD5_KEY);

        if(filter_var($this -> ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )) {

            $this -> writeIp();

            echo 'true';exit;

        } else {

            echo 'false';exit;

        }

    }

    /**
     * 把ip写入文件
     */
    private function writeIp() {

        $text  = "<?php" . $this -> wrap;
        $text .= " return array(" . $this -> wrap;
        $text .= "    '" . $this -> ip . "'," . $this -> wrap;
        $text .= ");" . $this -> wrap;

        file_put_contents($this -> savePath, $text);

    }

    /**
     * test
     */
    public function getIp() {
        $ip = HelperFactory::getIpHelper() -> getIp();
        var_dump($ip);
        exit;
    }

}