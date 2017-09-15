<?php
/**
 * @version		$Id$
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	mod_jux_real_estate_properties
 * @copyright	Copyright (C) 20013 - 2015 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html GNU/GPL version 3
*/

defined('_JEXEC') or die('Restricted access'); 

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class modJUXRealEstateProperties {
	
	protected $prefix = '#jux_real_estate_properties';
	
	public function __construct($prefix = ''){
		$this->prefix = $prefix;
	}

	protected function _renderCssObject($property,$cssJson,$one = false){
		$cssArr = json_decode($cssJson,true);
		$cssString = '';
		if (count($cssArr)){
			foreach ($cssArr as $key=>$v){
				if ($key == 'dd_shadow_inset'){
					$cssArr[$key] = $v == '1' ? 'inset' : '';
				}else{
					if (is_array($v)){
						$rbg = $this->HextoRBG($v['color']);
						$rbg['opacity'] = $v['opacity'];
						$cssArr[$key] = 'rgba('.implode(',',$rbg).')';
					}
				}
			}
			
			if (is_array($property)){
				foreach ($property as $k=>$pr){
					if (!$one){
						$cssString .= $pr.': '.implode(' ',$cssArr).';'."\n";
					}else{
						$cssArr = array_values($cssArr);
						$cssString .=$pr.':'.(isset($cssArr[$k]) ? $cssArr[$k] : '').';'."\n";
                                                
					}
				}
			}elseif (is_string($property)){
				$cssString .= $property.': '.implode(' ',$cssArr).';'."\n";
			}
		}
		return $cssString;
	}
	
	protected function _renderTopStyle(&$params){
		$sliderCss = '';
               
		return $sliderCss;
	}

	protected function _renderItemStyle(&$params){
		$itemStype = '';
                $jux_margin =  $this->_renderCssObject('margin',$params->get('jux_margin')); 
                $itemStype .= ' '.$this->prefix.' #owl-demo .owl-item .realty-item {'. $jux_margin. ' }';
                
                return $itemStype;       
	}
        protected function _renderDropdowStyle(&$params){
            $logoCssDropdow = '';
           
            return $logoCssDropdow;  
        }

	public function HextoRBG($hex){
		$hex = str_replace("#", "", $hex);
		$color = array();
		if(strlen($hex) == 3) {
			$color['r'] = hexdec(substr($hex, 0, 1) . $r);
			$color['g'] = hexdec(substr($hex, 1, 1) . $g);
			$color['b'] = hexdec(substr($hex, 2, 1) . $b);
		}
		else if(strlen($hex) == 6) {
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
		}

		return $color;
	}
	
	public function RBGtoHex($r, $g, $b){
		$hex = "#";
		$hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
		$hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
		$hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

		return $hex;
	}
	
	protected  function getCssData(&$params){
		$dataCss  = '';
		//$dataCss .= $this->_renderTopStyle($params);
		$dataCss .= $this->_renderItemStyle($params);
		//$dataCss .= $this->_renderDropdowStyle($params);
                $dataCss .= $params->get('custom_css');
		return $dataCss;
	}
	
	public static function process(&$params,$filename,$prefix=''){
		$css = new modJUXRealEstateProperties($prefix);
		return $css->_processor($params, $filename);
	}
	
	protected function _processor(&$params,$filename = 'custom.css'){
		
		$minCss = "/*===============================\n time:".($params->get('juxtime',''))."\n================================================================================*/\n";
		//$path = JPath::clean($path);
		$file = JPATH_SITE.'/'.$filename;
		if (JFile::exists($file)){
			$data = file($file);
			$time = '';
			foreach ($data as $k=>$v){
				if ($k == 1){
					$time = trim($v);
					break;
				}
			}
			$timeArr =  explode(':',$time);

			if ($timeArr[1] != $params->get('juxtime','')){
				$minCss .= Minify_CSS_Compressor::process(self::getCssData($params));
				return JFile::write($file, $minCss);
			}
			return true;	
		}else{
			$minCss .= Minify_CSS_Compressor::process(self::getCssData($params));
			return JFile::write($file, $minCss);

		}

		return false;
	}
}

if (!class_exists('Minify_CSS_Compressor')){
	/**
	* Class Minify_CSS_Compressor
	* @package Minify
	*/
	
	/**
	* Compress CSS
	*
	* This is a heavy regex-based removal of whitespace, unnecessary
	* comments and tokens, and some CSS value minimization, where practical.
	* Many steps have been taken to avoid breaking comment-based hacks,
	* including the ie5/mac filter (and its inversion), but expect tricky
	* hacks involving comment tokens in 'content' value strings to break
	* minimization badly. A test suite is available.
	*
	* @package Minify
	* @author Stephen Clay <steve@mrclay.org>
	* @author http://code.google.com/u/1stvamp/ (Issue 64 patch)
	*/
	class Minify_CSS_Compressor {

	    /**
	* Minify a CSS string
	*
	* @param string $css
	*
	* @param array $options (currently ignored)
	*
	* @return string
	*/
	public static function process($css, $options = array())
	{
		$obj = new Minify_CSS_Compressor($options);
		return $obj->_process($css);
	}

	    /**
	* @var array
	*/
	protected $_options = null;

	    /**
	* Are we "in" a hack? I.e. are some browsers targetted until the next comment?
	*
	* @var bool
	*/
	protected $_inHack = false;


	    /**
	* Constructor
	*
	* @param array $options (currently ignored)
	*/
	private function __construct($options) {
		$this->_options = $options;
	}

	    /**
	* Minify a CSS string
	*
	* @param string $css
	*
	* @return string
	*/
	protected function _process($css)
	{
		$css = str_replace("\r\n", "\n", $css);

	        // preserve empty comment after '>'
	        // http://www.webdevout.net/css-hacks#in_css-selectors
		$css = preg_replace('@>/\\*\\s*\\*/@', '>/*keep*/', $css);

	        // preserve empty comment between property and value
	        // http://css-discuss.incutio.com/?page=BoxModelHack
		$css = preg_replace('@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $css);
		$css = preg_replace('@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $css);

	        // apply callback to all valid comments (and strip out surrounding ws
		$css = preg_replace_callback('@\\s*/\\*([\\s\\S]*?)\\*/\\s*@'
			,array($this, '_commentCB'), $css);

	        // remove ws around { } and last semicolon in declaration block
		$css = preg_replace('/\\s*{\\s*/', '{', $css);
		$css = preg_replace('/;?\\s*}\\s*/', '}', $css);

	        // remove ws surrounding semicolons
		$css = preg_replace('/\\s*;\\s*/', ';', $css);

	        // remove ws around urls
		$css = preg_replace('/
			url\\( # url(
				\\s*
				([^\\)]+?) # 1 = the URL (really just a bunch of non right parenthesis)
		\\s*
		\\) # )
		/x', 'url($1)', $css);

	        // remove ws between rules and colons
		$css = preg_replace('/
			\\s*
			([{;]) # 1 = beginning of block or rule separator
				\\s*
				([\\*_]?[\\w\\-]+) # 2 = property (and maybe IE filter)
				\\s*
				:
				\\s*
				(\\b|[#\'"-]) # 3 = first character of a value
				/x', '$1$2:$3', $css);

	        // remove ws in selectors
		$css = preg_replace_callback('/
			(?: # non-capture
				\\s*
				[^~>+,\\s]+ # selector part
				\\s*
				[,>+~] # combinators
				)+
		\\s*
		[^~>+,\\s]+ # selector part
		{ # open declaration block
			/x'
			,array($this, '_selectorsCB'), $css);

	        // minimize hex colors
		$css = preg_replace('/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i'
			, '$1#$2$3$4$5', $css);

	        // remove spaces between font families
		$css = preg_replace_callback('/font-family:([^;}]+)([;}])/'
			,array($this, '_fontFamilyCB'), $css);

		$css = preg_replace('/@import\\s+url/', '@import url', $css);

	        // replace any ws involving newlines with a single newline
		$css = preg_replace('/[ \\t]*\\n+\\s*/', "\n", $css);

	        // separate common descendent selectors w/ newlines (to limit line lengths)
		$css = preg_replace('/([\\w#\\.\\*]+)\\s+([\\w#\\.\\*]+){/', "$1\n$2{", $css);

	        // Use newline after 1st numeric value (to limit line lengths).
		$css = preg_replace('/
			((?:padding|margin|border|outline):\\d+(?:px|em)?) # 1 = prop : 1st numeric value
			\\s+
			/x'
			,"$1\n", $css);

	        // prevent triggering IE6 bug: http://www.crankygeek.com/ie6pebug/
		$css = preg_replace('/:first-l(etter|ine)\\{/', ':first-l$1 {', $css);

		return trim($css);
	}

	    /**
	* Replace what looks like a set of selectors
	*
	* @param array $m regex matches
	*
	* @return string
	*/
	protected function _selectorsCB($m)
	{
	        // remove ws around the combinators
		return preg_replace('/\\s*([,>+~])\\s*/', '$1', $m[0]);
	}

	    /**
	* Process a comment and return a replacement
	*
	* @param array $m regex matches
	*
	* @return string
	*/
	protected function _commentCB($m)
	{
		$hasSurroundingWs = (trim($m[0]) !== $m[1]);
		$m = $m[1];
	        // $m is the comment content w/o the surrounding tokens,
	        // but the return value will replace the entire comment.
		if ($m === 'keep') {
			return '/**/';
		}
		if ($m === '" "') {
	            // component of http://tantek.com/CSS/Examples/midpass.html
			return '/*" "*/';
		}
		if (preg_match('@";\\}\\s*\\}/\\*\\s+@', $m)) {
	            // component of http://tantek.com/CSS/Examples/midpass.html
			return '/*";}}/* */';
		}
		if ($this->_inHack) {
	            // inversion: feeding only to one browser
			if (preg_match('@
				^/ # comment started like /*/
				\\s*
				(\\S[\\s\\S]+?) # has at least some non-ws content
				\\s*
				/\\* # ends like /*/ or /**/
				@x', $m, $n)) {
	                // end hack mode after this comment, but preserve the hack and comment content
				$this->_inHack = false;
			return "/*/{$n[1]}/**/";
		}
	}
	        if (substr($m, -1) === '\\') { // comment ends like \*/
	            // begin hack mode and preserve hack
	        	$this->_inHack = true;
	        	return '/*\\*/';
	        }
	        if ($m !== '' && $m[0] === '/') { // comment looks like /*/ foo */
	            // begin hack mode and preserve hack
	        	$this->_inHack = true;
	        	return '/*/*/';
	        }
	        if ($this->_inHack) {
	            // a regular comment ends hack mode but should be preserved
	        	$this->_inHack = false;
	        	return '/**/';
	        }
	        // Issue 107: if there's any surrounding whitespace, it may be important, so
	        // replace the comment with a single space
	        return $hasSurroundingWs // remove all other comments
	        ? ' '
	        : '';
	    }
	    
	    /**
	* Process a font-family listing and return a replacement
	*
	* @param array $m regex matches
	*
	* @return string
	*/
	protected function _fontFamilyCB($m)
	{
	        // Issue 210: must not eliminate WS between words in unquoted families
		$pieces = preg_split('/(\'[^\']+\'|"[^"]+")/', $m[1], null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		$out = 'font-family:';
		while (null !== ($piece = array_shift($pieces))) {
			if ($piece[0] !== '"' && $piece[0] !== "'") {
				$piece = preg_replace('/\\s+/', ' ', $piece);
				$piece = preg_replace('/\\s?,\\s?/', ',', $piece);
			}
			$out .= $piece;
		}
		return $out . $m[2];
	}
}
}