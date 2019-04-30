<?php
/**
 * @class Captcha
 * @kinsly 验证码生成类库
 */
class CpaCaptcha
{
    /** Width of the image */
    public $width  = 90;

    /** Height of the image */
    public $height = 26;

    public $resourcesPath = 'resources';
    
    /**
     * Path for resource files (fonts, words, etc.)
     * "resources" by default. For security reasons, is better move this
     * directory to another location outise the web server
     */

    /** Min word length (for non-dictionary random text generation) */
    public $minWordLength = 4;

    /**
     * Max word length (for non-dictionary random text generation)
     * Used for dictionary words indicating the word-length
     * for font-size modification purposes
     */
    public $maxWordLength = 4;

    /** Sessionname to store the original text */
    public $session_var = 'captcha';

    /** Background color in RGB-array */
    public $backgroundColor = array(255, 255, 255);

    /** Foreground colors in RGB-array */
    public $colors = array(
        array(19,18,78), // blue
        array(43,1,3), // green
        array(44,47,18),  // red
        array(99,49,42),
        array(53,43,129),
    );

    /** Shadow color in RGB-array or null */
    public $shadowColor = null; //array(0, 0, 0);

    public $fontSize    = 20;

    /**
     * Font configuration
     * - font: TTF file
     * - spacing: relative pixel space between character
     * - minSize: min font size
     * - maxSize: max font size
     */
    public $fonts = array(
       	'Time'    => array('spacing' => 1, 'minSize' => 22, 'maxSize' => 24, 'font' => 'font.ttf'),
        'Duality'  => array('spacing' => 1, 'minSize' => 18, 'maxSize' => 25, 'font' => 'VeraSansBold.ttf'),
        'Heineken' => array('spacing' => 1, 'minSize' => 20, 'maxSize' => 24, 'font' => 'Heineken.ttf'),
        'Jura'     => array('spacing' => 1, 'minSize' => 28, 'maxSize' => 32, 'font' => 'VeraSansBold.ttf'),
    );

    /** Wave configuracion in X and Y axes */
    public $Yperiod    = 12;
    public $Yamplitude = 14;
    public $Xperiod    = 11;
    public $Xamplitude = 5;

    /** letter rotation clockwise */
    public $maxRotation = 8;

    /**
     * Internal image size factor (for better image quality)
     * 1: low, 2: medium, 3: high
     */
    public $scale = 3;

    /**
     * Blur effect for better image quality (but slower image processing).
     * Better image results with scale=3
     */
    public $blur = false;

    /** Debug? */
    public $debug = false;

    /** Image format: jpeg or png */
    public $imageFormat = 'jpeg';

    /** GD image */
    public $im;

    private $captchaType = '';

    public function __construct($config = array()) {
        $this -> captchaType = $config["captcha_type"];
    }

    public function CreateImage() {
        $ini = microtime(true);

        /** Initialization */
        $this->ImageAllocate();

        /** Text insertion */
        $text = $this->GetCaptchaText();
        
        $fontcfg  = $this->fonts[array_rand($this->fonts)];
        $this->WriteText($text, $fontcfg);

        $this->session_var = $text;
		$this ->createCaptchaCookie();
        /** Transformations */
        $this->WaveImage();
        if ($this->blur && function_exists('imagefilter'))
		{
            imagefilter($this->im, IMG_FILTER_GAUSSIAN_BLUR);
        }
        $this->ReduceImage();

        if ($this->debug)
		{
            imagestring($this->im, 1, 1, $this->height-20,
                "$text {$fontcfg['font']} ".round((microtime(true)-$ini)*1000)."ms",
                $this->GdFgColor
            );
        }


        /** Output */
        $this->WriteImage();
        $this->Cleanup();
    }
	
	/**
	* creates the captcha cookie
	*/
	protected function createCaptchaCookie(){
		
        $captchaType = 'captchaCode';

        if ($this->captchaType) {
            $captchaType = $this->captchaType;
        }

		HelperFactory::getCookieHelper()->delete($captchaType);
		HelperFactory::getCookieHelper()->set($captchaType,'',$this ->session_var,time()+500);

	}
	
	
    /**
     * Creates the image resources
     */
    protected function ImageAllocate()
	{
        // Cleanup
        if (!empty($this->im))
		{
            imagedestroy($this->im);
        }

        $this->im = imagecreatetruecolor($this->width*$this->scale, $this->height*$this->scale);
        
        // Background color
        $this->GdBgColor = imagecolorallocate($this->im,
            $this->backgroundColor[0],
            $this->backgroundColor[1],
            $this->backgroundColor[2]
        );
        
        imagefilledrectangle($this->im, 0, 0, $this->width*$this->scale, $this->height*$this->scale, $this->GdBgColor); 
         
        // Foreground color
        $color           = $this->colors[mt_rand(0, sizeof($this->colors)-1)];
        $this->GdFgColor = imagecolorallocate($this->im, $color[0], $color[1], $color[2]);

        // Shadow color
        if (!empty($this->shadowColor) && is_array($this->shadowColor) && sizeof($this->shadowColor) >= 3)
		{
            $this->GdShadowColor = imagecolorallocate($this->im,
                $this->shadowColor[0],
                $this->shadowColor[1],
                $this->shadowColor[2]
            );
        }
    }

    /**
     * Text generation
     *
     * @return string Text
     */
    protected function GetCaptchaText()
	{
        $text = $this->GetRandomCaptchaText();
        return $text;
    }

    /**
     * Random text generation
     * @return string Text
     */
    protected function GetRandomCaptchaText($length = null)
	{
        if (empty($length))
		{
            $length = rand($this->minWordLength, $this->maxWordLength);
        }

        $words  = "abcdefghijlmnopqrstvwyz";
        $vocals = "aeiou";

        $text  = "";
        $vocal = rand(0, 1);
        for ($i=0; $i<$length; $i++)
		{
            if ($vocal)
			{
                $text .= substr($vocals, mt_rand(0, 4), 1);
            }
			else
			{
                $text .= substr($words, mt_rand(0, 22), 1);
            }
            $vocal = !$vocal;
        }
        return $text;
    }


    /**
     * Text insertion
     */
    protected function WriteText($text, $fontcfg = array())
	{
        if (empty($fontcfg))
		{
            // Select the font configuration
            $fontcfg  = $this->fonts[array_rand($this->fonts)];
        }

        // Full path of font file
        $fontfile = dirname(__FILE__). '/' .$this->resourcesPath . '/'.$fontcfg['font'];
        
        /** Increase font-size for shortest words: 9% for each glyp missing */
        $lettersMissing = $this->maxWordLength-strlen($text);
        $fontSizefactor = 1+($lettersMissing*0.09);

		//$fontspace = $this->width/strlen($text)-2;
		//$minSize = $fontspace;
		//$maxSize= $fontspace;

        // Text generation (char by char)
        $x      = 20*$this->scale*0.5;
        $y      = round(($this->height*27/40)*$this->scale);
        $length = strlen($text);
        for ($i=0; $i<$length; $i++)
		{
            $degree   = rand($this->maxRotation*-1, $this->maxRotation);
            $fontsize = rand($this->fontSize+1, $this->fontSize-1)*$this->scale*$fontSizefactor;
			//$fontsize = $maxSize*$this->scale*$fontSizefactor;
            $letter   = substr($text, $i, 1);

            if ($this->shadowColor)
			{
                $coords = imagettftext($this->im, $fontsize, $degree,
                    $x+$this->scale, $y+$this->scale,
                    $this->GdShadowColor, $fontfile, $letter);
            }
            $coords = imagettftext($this->im, $fontsize, $degree,
                $x, $y,
                $this->GdFgColor, $fontfile, $letter);
            $x += ($coords[2]-$x) + ($fontcfg['spacing']*$this->scale);
        }
    }

    /**
     * Wave filter
     */
    protected function WaveImage()
	{
        // X-axis wave generation
        $xp = $this->scale*$this->Xperiod*rand(1,3);
        $k = rand(0, 100);
        for ($i = 0; $i < ($this->width*$this->scale); $i++)
		{
            // var_dump(sin($k+$i/$xp) * ($this->scale*$this->Xamplitude));
            imagecopy($this->im, $this->im,
                $i, 0,
                $i, 0, 1, $this->height*$this->scale);
        }
        // exit;
        // Y-axis wave generation
        $k = rand(0, 100);
        $yp = $this->scale*$this->Yperiod*rand(1,2);
        for ($i = 0; $i < ($this->height); $i++)
		{
            imagecopy($this->im, $this->im,
                0, $i,
                0, $i, $this->width, 1);
        }
    }

    /**
     * Reduce the image to the final size
     */
    protected function ReduceImage()
	{
        $imResampled = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($imResampled, $this->im,
            0, 0, 0, 0,
            $this->width, $this->height,
            $this->width*$this->scale, $this->height*$this->scale
        );
        imagedestroy($this->im);
        $this->im = $imResampled;
    }

    /**
     * File generation
     */
    protected function WriteImage()
	{
        if ($this->imageFormat == 'png' && function_exists('imagepng'))
		{
            header("Content-type: image/png");
            imagepng($this->im);
        }
		else
		{
            header("Content-type: image/jpeg");
            imagejpeg($this->im, null, 90);
        }
    }

    /**
     * Cleanup
     */
    protected function Cleanup()
	{
        imagedestroy($this->im);
    }
}

?>