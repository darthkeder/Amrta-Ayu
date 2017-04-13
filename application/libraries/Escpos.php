<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class EscposImage {
	/**
	 * @var string The image's bitmap data (if it is a Windows BMP).
	 */
	private $imgBmpData;
	
	
	/**
	 * @var string image data in rows: 1 for black, 0 for white.
	 */
	private $imgData;
	
	/**
	 * @var string cached raster format data to avoid re-computation
	 */
	private $imgRasterData;
	
	/**
	 * @var int height of the image
	 */
	private $imgHeight;

	/**
	 * @var int width of the image
	 */
	private $imgWidth;
	
	/**
	 * Load up an image
	 * 
	 * @param string $imgPath The path to the image to load. Currently only PNG graphics are supported.
	 */
	public function __construct($imgPath) {
		/* Can't use bitmaps yet */
		$this -> imgBmpData = null;
		$this -> imgRasterData = null;
		
		/* Load up using GD */
		$im = imagecreatefrompng($imgPath);
		if(!$im) {
			throw new Exception("Failed to load image '$imgPath'. Must be a PNG file.");
		}
		
		/* Make a string of 1's and 0's */
		$this -> imgHeight = imagesy($im);
		$this -> imgWidth = imagesx($im);
		$this -> imgData = str_repeat("\0", $this -> imgHeight * $this -> imgWidth);
 		for($y = 0; $y < $this -> imgHeight; $y++) {
 			for($x = 0; $x < $this -> imgWidth; $x++) {
 				/* Faster to average channels, blend alpha and negate the image here than via filters (tested!) */
 				$cols = imagecolorsforindex($im, imagecolorat($im, $x, $y));
 				$greyness = (int)($cols['red'] + $cols['red'] + $cols['blue']) / 3;
 				$black = (255 - $greyness) >> (7 + ($cols['alpha'] >> 6));
 				$this -> imgData[$y * $this -> imgWidth + $x] = $black;
 			}
 		}
	}
	
	/**
	 * @return int height of the image in pixels
	 */
	public function getHeight() {
		return $this -> imgHeight;
	}
	
	/**
	 * @return int Number of bytes to represent a row of this image
	 */
	public function getHeightBytes() {
		return (int)(($this -> imgHeight + 7) / 8);
	}
	
	/**
	 * @return int Width of the image
	 */
	public function getWidth() {
		return $this -> imgWidth;
	}
	
	/**
	 * @return int Number of bytes to represent a row of this image
	 */
	public function getWidthBytes() {
		return (int)(($this -> imgWidth + 7) / 8);
	}
	
	/**
	 * @return string binary data of the original file, for function which accept bitmaps.
	 */
	public function getWindowsBMPData() {
		return $this -> imgBmpData;
	}
	
	/**
	 * @return boolean True if the image was a windows bitmap, false otherwise
	 */
	public function isWindowsBMP() {
		return $this -> imgBmpData != null;
	}
	
	/**
	 * Output the image in raster (row) format. This can result in padding on the right of the image, if its width is not divisible by 8.
	 * 
	 * @throws Exception Where the generated data is unsuitable for the printer (indicates a bug or oversized image).
	 * @return string The image in raster format.
	 */
	public function toRasterFormat() {
		if($this -> imgRasterData != null) {
			/* Use previous calculation */
			return $this -> imgRasterData;
		}
		/* Loop through and convert format */
		$widthPixels = $this -> getWidth();
		$heightPixels = $this -> getHeight();
		$widthBytes = $this -> getWidthBytes();
		$heightBytes = $this -> getHeightBytes();
		$x = $y = $bit = $byte = $byteVal = 0;
		$data = str_repeat("\0", $widthBytes * $heightPixels);
		do {
			$byteVal |= (int)$this -> imgData[$y * $widthPixels + $x] << (7 - $bit);
			$x++;
			$bit++;
			if($x >= $widthPixels) {
				$x = 0;
				$y++;
				$bit = 8;
				if($y >= $heightPixels) {
					$data[$byte] = chr($byteVal);
					break;
				}
			}
			if($bit >= 8) {
				$data[$byte] = chr($byteVal);
				$byteVal = 0;
				$bit = 0;
				$byte++;
			}
		} while(true);
 		if(strlen($data) != ($this -> getWidthBytes() * $this -> getHeight())) {
 			throw new Exception("Bug in " . __FUNCTION__ . ", wrong number of bytes.");
 		}
 		$this -> imgRasterData = $data;
 		return $this -> imgRasterData;
	}
	
	/**
	 * Output image in column format. This format results in padding at the base and right of the image, if its height and width are not divisible by 8.
	 */
	private function toColumnFormat() {
		/* Note: This function is marked private, as it is not yet used and may be buggy. */
		$widthPixels = $this -> getWidth();
		$heightPixels = $this -> getHeight();
		$widthBytes = $this -> getWidthBytes();
		$heightBytes = $this -> getHeightBytes();
		$x = $y = $bit = $byte = $byteVal = 0;
		$data = str_repeat("\0", $widthBytes * $heightBytes * 8);
 		do {
 			$byteVal |= (int)$this -> imgData[$y * $widthPixels + $x] << (8 - $bit);
 			$y++;
 			$bit++;
 			if($y >= $heightPixels) {
 				$y = 0;
 				$x++;
 				$bit = 8;
 				if($x >= $widthPixels) {
 					$data[$byte] = chr($byteVal);
 					break;
 				}
 			}
 			if($bit >= 8) {
 				$data[$byte] = chr($byteVal);
 				$byteVal = 0;
 				$bit = 0;
 				$byte++;
 			}
 		} while(true);
  		if(strlen($data) != ($widthBytes * $heightBytes * 8)) {
  			throw new Exception("Bug in " . __FUNCTION__ . ", wrong number of bytes. Should be " . ($widthBytes * $heightBytes * 8) . " but was " . strlen($data));
  		}
		return $data;
	}
}
class EscposPrintBuffer {
	/**
	 * This array maps Escpos character tables to names iconv encodings
	 */
// 	TODO Not yet used
// 	private static $characterMaps = array(
// 			Escpos::CHARSET_CP437 => "CP437",
// 			Escpos::CHARSET_CP850 => "CP850",
// 			Escpos::CHARSET_CP860 => "CP860",
// 			Escpos::CHARSET_CP863 => "CP863",
// 			Escpos::CHARSET_CP865 => "CP865",
// 			Escpos::CHARSET_CP851 => "CP851",
// 			Escpos::CHARSET_CP857 => "CP857",
// 			Escpos::CHARSET_CP737 => "CP737",
// 			Escpos::CHARSET_ISO8859_7 => "ISO_8859-7",
// 			Escpos::CHARSET_CP1252 => "CP1252",
// 			Escpos::CHARSET_CP866 => "CP866",
// 			Escpos::CHARSET_CP852 => "CP852",
// 			Escpos::CHARSET_TCVN3_1 => "TCVN",
// 			Escpos::CHARSET_CP775 => "CP775",
// 			Escpos::CHARSET_CP855 => "CP855",
// 			Escpos::CHARSET_CP861 => "CP861",
// 			Escpos::CHARSET_CP862 => "CP862",
// 			Escpos::CHARSET_CP864 => "CP864",
// 			Escpos::CHARSET_CP869 => "CP869",
// 			Escpos::CHARSET_ISO8859_2 => "ISO_8859-2",
// 			Escpos::CHARSET_ISO8859_15 => "ISO_8859-15",
// 			Escpos::CHARSET_CP1125 => "CP1125",
// 			Escpos::CHARSET_CP1250 => "CP1250",
// 			Escpos::CHARSET_CP1251 => "CP1251",
// 			Escpos::CHARSET_CP1253 => "CP1253",
// 			Escpos::CHARSET_CP1254 => "CP1254",
// 			Escpos::CHARSET_CP1255 => "CP1255",
// 			Escpos::CHARSET_CP1256 => "CP1256",
// 			Escpos::CHARSET_CP1257 => "CP1257",
// 			Escpos::CHARSET_CP1258 => "CP1258",
// 			Escpos::CHARSET_RK1048 => "RK1048"
// 		);
	// List of available characters
	private static $available = null;

	private static $availableFile = "/charmap-available.ser";

	// True if we are auto-switching 
	private $auto;

	// Current character table (ESC/POS table number)
	private $characterTable;

	// File pointer for output
	private $connector;
	
	// Printer for output
	private $printer;

	function __construct(Escpos $printer, PrintConnector $connector) {
// 		ini_set('mbstring.substitute_character', "?");
		$this -> connector = $connector;
		$this -> printer = $printer;

// 	TODO Not yet used
// 		$this -> auto = true;
// 		$this -> characterTable = Escpos::CHARSET_CP437;
//  		if(self::$available == null) {
//  			self::$available = self::loadAvailableCharacters();
//  		}
	}

	/**
	 * Finalize the underlying connector
	 */
	function finalize() {
		// TODO final line break if needed
		$this -> connector -> finalize();
	}
	
	static function generateAvailableCharacters() {
		throw new Exception("Not implemented");
		// 	TODO Not yet used
// 		$encode = array();
// 		$available = array();
// 		foreach(self::$characterMaps as $num => $characterMap) {
// 			for($char = 128; $char <= 255; $char++) {
// 				$utf8 = @iconv($characterMap, 'UTF-8', chr($char));
// 				if($utf8 == '') {
// 					continue;
// 				}
// 				if(iconv('UTF-8', $characterMap, $utf8) != chr($char)) {
// 					continue;
// 				}
// 				if(!isset($available[$utf8])) {
// 					$available[$utf8] = array();
// 				}
// 				$available[$utf8][$num] = true;
// 			}
// 		}
// 		/* Attempt to cache, but don't worry if we can't */
// 		$data = serialize($available);
// 		if($data !== false) {
// 			@file_put_contents(dirname(__FILE__) . self::$availableFile, $data);
// 		}
// 		return $available;
	}

	static function loadAvailableCharacters() {
		throw new Exception("Not implemented");
		// 	TODO Not yet used
// 		if(file_exists(dirname(__FILE__) . self::$availableFile)) {
// 			return unserialize(file_get_contents(dirname(__FILE__) . self::$availableFile));
// 		}
// 		return self::generateAvailableCharacters();
	}

	function getCharacterTable() {
		// 	TODO Not yet used
		throw new Exception("Not implemented");
//		return $this -> characterTable;
	}

	// Multibyte
	function writeText($text) {
		$this -> write($text);
		
		
// 		// 	TODO Not yet used
// 		if($text == null) {
// 			return;
// 		}
// 		if(!mb_detect_encoding($text, 'UTF-8', true)) {
// 			// Assume that the user has already put non-UTF8 into the target encoding.
// 			return $this -> writeTextRaw($text);
// 		}
// 		if(!$this -> auto) {
// 			// If we are not auto-switching characters, then pass it on directly
// 			$encoding = $this -> characterTable;
// 			return $this -> writeTextUsingEncoding($text, $encoding);
// 		}
// 		$i = 0;
// 		$j = 0;
// 		while($i < mb_strlen($text)) {
// 			$encoding = $this -> identify($text);
// 			$j = 0;
// 			do {
// 				$char = mb_substr($text, $i, 1);
// 				$matching = !isset(self::$available[$char]) || (isset(self::$available[$char][$encoding]));
// 				$i++;
// 				$j++;
// 			} while($matching);
// 			$this -> writeTextUsingEncoding(mb_substr($text, $i - $j, $j), $encoding);
// 		}	
	}

	// Multibyte
	private function writeTextUsingEncoding($text, $encodingNo) {
		throw new Exception("Not implemented");
		// 	TODO Not yet used
// 		$encoding = self::$characterMaps[$encodingNo];
// 		$rawText = str_repeat("?", mb_strlen($text));
// 		for($i = 0; $i < mb_strlen($text); $i++) {
// 			$char = "a";//mb_substr($text, $i, 1);
// 			echo $char . "\n";
// echo $encoding . "\n";
// 			$rawChar = iconv('UTF-8', $encoding, $text);
// 			if(strlen($rawChar) == 1) {
// 				$rawText[$i] = $rawChar;
// 			}
// 		}

// 		echo $rawText . "\n";
// die();


		

// 		$this -> writeTextRaw($rawText);
// 		// If encoding is not current encoding.. send switch code.
		
		
// 		// Call writeTextRaw on the result of iconv'ing as many characters as we can represent.
		
// 		// Return any remaining characters.
	}

	// Single-byte, in current encoding. Non-printable characters will be stripped out here.
	function writeTextRaw($text) {
		
		// TODO Call write, passing on printable characters only!
		$this -> write($text);
	}

	function write($data) {
		$this -> connector -> write($data);
	}

	// Figure out what encoding some text is
	private function identify($text) {
		throw new Exception("Not implemented");
		// 	TODO Not yet used
		//return Escpos::CHARSET_CP437;
	}
}
interface PrintConnector {
	/**
	 * Print connectors should cause a NOTICE if they are deconstructed
	 * when they have not been finalized.
	 */
	public function __destruct();

	/**
	 * Finish using this print connector (close file, socket, send
	 * accumulated output, etc).
	 */
	public function finalize();

	/**
	 * @param string $data
	 */
	public function write($data);
}
class WindowsPrintConnector implements PrintConnector {
	/**
	 * @var array Accumulated lines of output for later use.
	 */
	private $buffer;

	/**
	 * @var string The hostname of the target machine, or null if this is a local connection.
	 */
	private $hostname;

	/**
	 * @var boolean True if a port is being used directly (must be Windows), false if network shares will be used.
	 */
	private $isLocal;

	/**
	 * @var boolean True if this script is running on Windows, false otherwise.
	 */
	private $isWindows;

	/**
	 * @var string The name of the target printer (eg "Foo Printer") or port ("COM1", "LPT1").
	 */
	private $printerName;

	/**
	 * @var string Valid local ports.
	 */
	const REGEX_LOCAL = "/^(LPT\d|COM\d)$/";

	/**
	 * @var string Valid printer name.
	 */
	const REGEX_PRINTERNAME = "/^(\w+)(\s\w*)*$/";

	/**
	 * @var string Valid smb:// URI containing hostname & printer name only.
	 */
	const REGEX_SMB = "/^smb:\/\/(\w*)/";

	/**
	 * @param string $dest
	 * @throws BadMethodCallException
	 */
	public function __construct($dest) {
		$this -> isWindows = (PHP_OS == "WINNT");
		$this -> isLocal = false;
		$this -> buffer = null;
		if(preg_match(self::REGEX_LOCAL, $dest)) {
			// Straight to LPT1, COM1 or other local port. Allowed only if we are actually on windows.
			if(!$this -> isWindows) {
				throw new BadMethodCallException("WindowsPrintConnector can only be used to print to a local printer ('".$dest."') on a Windows computer.");
			}
			$this -> isLocal = true;
			$this -> hostname = null;
			$this -> printerName = $dest;
		} else if(preg_match(self::REGEX_SMB, $dest)) {
			// Connect to samba share. smb://host/printer
			$part = parse_url($dest);
			$this -> hostname = $part['host'];
			$this -> printerName = ltrim($part['path'], '/');
		} else if(preg_match(self::REGEX_PRINTERNAME, $dest)) {
			// Just got a printer name. Assume it's on the current computer.
			$hostname = gethostname();
			if(!$hostname) {
				$hostname = "localhost";
			}
			$this -> hostname = $hostname;
			$this -> printerName = $dest;
		} else {
			throw new BadMethodCallException("Printer '" . $dest . "' is not valid. Use local port (LPT1, COM1, etc) or smb://computer/printer notation.");
		}
		$this -> buffer = array();
	}

	public function __destruct() {
		if($this -> buffer !== null) {
			trigger_error("Print connector was not finalized. Did you forget to close the printer?", E_USER_NOTICE);
		}
	}

	public function finalize() {
		$data = implode($this -> buffer);
		$this -> buffer = null;
		if($this -> isWindows) {
			/* Build windows-friendly print command */
			if(!$this -> isLocal) {
				$device = "\\\\" . $this -> hostname . "\\" . $this -> printerName;
			} else {
				$device = $this -> printerName;
			}
			$filename = tempnam(sys_get_temp_dir(), "escpos");
			$cmd = sprintf("print /D:%s %s",
				escapeshellarg($device),
				escapeshellarg($filename));
			
			/* Write print job and run command */
			file_put_contents($filename, $data);
 			ob_start();
 			passthru($cmd, $retval);
 			$outp = ob_get_contents();
 			ob_end_clean();
			if($retval != 0 || strpos($outp, $device) !== false) {
				trigger_error("Failed to print. Command '$cmd' returned $retval: $outp", E_USER_NOTICE);
			}
			unlink($filename);
		} else {
			/* Build linux-friendly print command */
			// smbspool (Linux).
			throw new Exception("Linux printing over Samba not yet implemented");
			$cmd = "";
		}
	}

	public function write($data) {
		$this -> buffer[] = $data;
	}
}
class Escpos {
	/* ASCII codes */
	const NUL = "\x00";
	const LF = "\x0a";
	const ESC = "\x1b";
	const FS = "\x1c";
	const GS = "\x1d";

	/* Barcode types */
	const BARCODE_UPCA = 0;
	const BARCODE_UPCE = 1;
	const BARCODE_JAN13 = 2;
	const BARCODE_JAN8 = 3;
	const BARCODE_CODE39 = 4;
	const BARCODE_ITF = 5;
	const BARCODE_CODABAR = 6;
	
	/*
	 * All character tables. Only a subset of these are automatically
	 * switched to (see EscposPrintBuffer), but all can be manually applied
	 * and accessed via textRaw();
	 */
// TODO not yet used, see issue #9
// 	const CHARSET_AUTO = -1;
// 	const CHARSET_CP437 = 0;
// 	const CHARSET_KATAKANA = 1;
// 	const CHARSET_CP850 = 2;
// 	const CHARSET_CP860 = 3;
// 	const CHARSET_CP863 = 4;
// 	const CHARSET_CP865 = 5;
// 	const CHARSET_HIRAGANA = 6;
// 	const CHARSET_KANJI_1 = 7;
// 	const CHARSET_KANJI_2 = 8;
// 	const CHARSET_CP851 = 11;
// 	const CHARSET_CP853 = 12;
// 	const CHARSET_CP857 = 13;
// 	const CHARSET_CP737 = 14;
// 	const CHARSET_ISO8859_7 = 15;
// 	const CHARSET_CP1252 = 16;
// 	const CHARSET_CP866 = 17;
// 	const CHARSET_CP852 = 18;
// 	const CHARSET_CP858 = 19;
// 	const CHARSET_THAI_42 = 20;
// 	const CHARSET_THAI_11 = 21;
// 	const CHARSET_THAI_13 = 22;
// 	const CHARSET_THAI_14 = 23;
// 	const CHARSET_THAI_16 = 24;
// 	const CHARSET_THAI_17 = 25;
// 	const CHARSET_THAI_18 = 26;
// 	const CHARSET_TCVN3_1 = 30;
// 	const CHARSET_TCVN3_2 = 31;
// 	const CHARSET_CP720 = 32;
// 	const CHARSET_CP775 = 33;
// 	const CHARSET_CP855 = 34;
// 	const CHARSET_CP861 = 35;
// 	const CHARSET_CP862 = 36;
// 	const CHARSET_CP864 = 37;
// 	const CHARSET_CP869 = 38;
// 	const CHARSET_ISO8859_2 = 39;
// 	const CHARSET_ISO8859_15 = 40;
// 	const CHARSET_CP1098 = 41;
// 	const CHARSET_CP1118 = 42;
// 	const CHARSET_CP1119 = 43;
// 	const CHARSET_CP1125 = 44;
// 	const CHARSET_CP1250 = 45;
// 	const CHARSET_CP1251 = 46;
// 	const CHARSET_CP1253 = 47;
// 	const CHARSET_CP1254 = 48;
// 	const CHARSET_CP1255 = 49;
// 	const CHARSET_CP1256 = 50;
// 	const CHARSET_CP1257 = 51;
// 	const CHARSET_CP1258 = 52;
// 	const CHARSET_RK1048 = 53;
// 	const CHARSET_ISCII_DEVANAGARI = 66;
// 	const CHARSET_ISCII_BENGALI = 67;
// 	const CHARSET_ISCII_TAMIL = 68;
// 	const CHARSET_ISCII_TELUGU = 69;
// 	const CHARSET_ISCII_ASSAMESE = 70;
// 	const CHARSET_ISCII_ORIYA = 71;
// 	const CHARSET_ISCII_KANNADA = 72;
// 	const CHARSET_ISCII_MALAYALAM = 73;
// 	const CHARSET_ISCII_GUJARATI = 74;
// 	const CHARSET_ISCII_PUNJABI = 75;
// 	const CHARSET_ISCII_MARATHI = 82;
// 	const CHARSET_254 = 254;
// 	const CHARSET_255 = 255;
	
	/* Cut types */
	const CUT_FULL = 65;
	const CUT_PARTIAL = 66;
	
	/* Fonts */
	const FONT_A = 0;
	const FONT_B = 1;
	const FONT_C = 2;
	
	/* Image sizing options */
	const IMG_DEFAULT = 0;
	const IMG_DOUBLE_WIDTH = 1;
	const IMG_DOUBLE_HEIGHT = 2;
	
	/* Justifications */
	const JUSTIFY_LEFT = 0;
	const JUSTIFY_CENTER = 1;
	const JUSTIFY_RIGHT = 2;
	
	/* Print mode constants */
	const MODE_FONT_A = 0;
	const MODE_FONT_B = 1;
	const MODE_EMPHASIZED = 8;
	const MODE_DOUBLE_HEIGHT = 16;
	const MODE_DOUBLE_WIDTH = 32;
	const MODE_UNDERLINE = 128;
	
	/* Underline */
	const UNDERLINE_NONE = 0;
	const UNDERLINE_SINGLE = 1;
	const UNDERLINE_DOUBLE = 2;
	
	/**
	 * @var EscposPrintBuffer The printer's output buffer.
	 */
	private $buffer;
	
// 	/**
// 	 * @var array The list of accepted character sets. This is used only for validation.
// 	 */
// 	private static $charsets = array();
	
	/**
	 * Construct a new print object
	 * 
	 * @param PrintConnector $connector The PrintConnector to send data to. If not set, output is sent to standard output.
	 */
	function __construct(PrintConnector $connector = null) {
		if(is_null($connector) && php_sapi_name() == 'cli') {
			$connector = new FilePrintConnector("php://stdout");
		}
		$this -> buffer = new EscposPrintBuffer($this, $connector);
		$this -> initialize();
	}
	
	/**
	 * Print a barcode.
	 *
	 * @param string $content The information to encode.
	 * @param int $type The barcode standard to output. If not specified, `Escpos::BARCODE_CODE39` will be used.
	 */
	function barcode($content, $type = self::BARCODE_CODE39) {
		// TODO validation on barcode() inputs
		$this -> buffer -> write(self::GS . "k" . chr($type) . $content . self::NUL);
	}
	
	/**
	 * Print an image, using the older "bit image" command. This creates padding on the right of the image,
	 * if its width is not divisible by 8.
	 * 
	 * Should only be used if your printer does not support the graphics() command.
	 * 
	 * @param EscposImage $img The image to print
	 * @param EscposImage $size Size modifier for the image.
	 */
	function bitImage(EscposImage $img, $size = self::IMG_DEFAULT) {
		self::validateInteger($size, 0, 3, __FUNCTION__);
		$header = self::dataHeader(array($img -> getWidthBytes(), $img -> getHeight()), true);
		$this -> buffer -> write(self::GS . "v0" . chr($size) . $header);
		$this -> buffer -> write($img -> toRasterFormat());
	}
	
	/**
	 * Close the underlying buffer. With some connectors, the
	 * job will not actually be sent to the printer until this is called.
	 */
	function close() {
		$this -> buffer -> finalize();
	}
	
	/**
	 * Cut the paper.
	 *
	 * @param int $mode Cut mode, either Escpos::CUT_FULL or Escpos::CUT_PARTIAL. If not specified, `Escpos::CUT_FULL` will be used.
	 * @param int $lines Number of lines to feed
	 */
	function cut($mode = self::CUT_FULL, $lines = 3) {
		// TODO validation on cut() inputs
		$this -> buffer -> write(self::GS . "V" . chr($mode) . chr($lines));
	}
	
	/**
	 * Print and feed line / Print and feed n lines.
	 * 
	 * @param int $lines Number of lines to feed
	 */
	function feed($lines = 1) {
		self::validateInteger($lines, 1, 255, __FUNCTION__);
		if($lines <= 1) {
			$this -> buffer -> write(self::LF);
		} else {
			$this -> buffer -> write(self::ESC . "d" . chr($lines));
		}
	}
	
	/**
	 * Print and reverse feed n lines.
	 *
	 * @param int $lines number of lines to feed. If not specified, 1 line will be fed.
	 */
	function feedReverse($lines = 1) {
		self::validateInteger($lines, 1, 255, __FUNCTION__);
		$this -> buffer -> write(self::ESC . "e" . chr($lines));
	}
	
	/**
	 * Print an image to the printer.
	 * 
	 * Size modifiers are:
	 * - IMG_DEFAULT (leave image at original size)
	 * - IMG_DOUBLE_WIDTH
	 * - IMG_DOUBLE_HEIGHT
	 * 
	 * See the example/ folder for detailed examples.
	 * 
	 * The function bitImage() takes the same parameters, and can be used if
	 * your printer doesn't support the newer graphics commands.
	 * 
	 * @param EscposImage $img The image to print.
	 * @param int $size Output size modifier for the image.
	 */
	function graphics(EscposImage $img, $size = self::IMG_DEFAULT) {
		self::validateInteger($size, 0, 3, __FUNCTION__);
		$imgHeader = self::dataHeader(array($img -> getWidth(), $img -> getHeight()), true);
		$tone = '0';
		$colors = '1';
		$xm = (($size & self::IMG_DOUBLE_WIDTH) == self::IMG_DOUBLE_WIDTH) ? chr(2) : chr(1);
		$ym = (($size & self::IMG_DOUBLE_HEIGHT) == self::IMG_DOUBLE_HEIGHT) ? chr(2) : chr(1);
		$header = $tone . $xm . $ym . $colors . $imgHeader;
		$this -> graphicsSendData('0', 'p', $header . $img -> toRasterFormat());
		$this -> graphicsSendData('0', '2');
	}
	
	/**
	 * Wrapper for GS ( L, to set correct data length.
	 * 
	 * @param int $m
	 * @param int $fn
	 * @param string $data
	 */
	private function graphicsSendData($m, $fn, $data = '') {
		if(strlen($m) != 1 || strlen($fn) != 1) {
			throw new IllegalArgumentException("graphicsSendData: m and fn must be one character each.");
		}
		$header = $this -> intLowHigh(strlen($data) + 2, 2);
		$this -> buffer -> write(self::GS . "(L" . $header . $m . $fn . $data);
	}
	
	/**
	 * Initialize printer. This resets formatting back to the defaults.
	 */
	function initialize() {
		$this -> buffer -> write(self::ESC . "@");
	}
	
	/**
	 * Generate a pulse, for opening a cash drawer if one is connected.
	 * The default settings should open an Epson drawer.
	 *
	 * @param int $pin 0 or 1, for pin 2 or pin 5 kick-out connector respectively.
	 * @param int $on_ms pulse ON time, in milliseconds.
	 * @param int $off_ms pulse OFF time, in milliseconds.
	 */
	function pulse($pin = 0, $on_ms = 120, $off_ms = 240) {
		// TODO validation on pulse() inputs
		$this -> buffer -> write(self::ESC . "p" . chr($pin + 48) . chr($on_ms / 2) . chr($off_ms / 2));
	}
	
	function selectCharacterTable($table = self::CP_437) {
		self::validateInteger($table, 0, 255, __FUNCTION__);
		$this -> buffer -> write(self::ESC . "t" . chr($table));
	}
	
	/**
	 * Select print mode(s).
	 * 
	 * Several MODE_* constants can be OR'd together passed to this function's `$mode` argument. The valid modes are:
	 *  - MODE_FONT_A
	 *  - MODE_FONT_B
	 *  - MODE_EMPHASIZED
	 *  - MODE_DOUBLE_HEIGHT
	 *  - MODE_DOUBLE_WIDTH
	 *  - MODE_UNDERLINE
	 * 
	 * @param int $mode The mode to use. Default is Escpos::MODE_FONT_A, with no special formatting. This has a similar effect to running initialize().
	 */
	function selectPrintMode($mode = self::MODE_FONT_A) {
		$allModes = self::MODE_FONT_B | self::MODE_EMPHASIZED | self::MODE_DOUBLE_HEIGHT | self::MODE_DOUBLE_WIDTH | self::MODE_UNDERLINE;
		if(!is_integer($mode) || $mode < 0 || ($mode & $allModes) != $mode) {
			throw new InvalidArgumentException("Test");
		}

		$this -> buffer -> write(self::ESC . "!" . chr($mode));
	}
	
	/**
	 * Set barcode height.
	 *
	 * @param int $height Height in dots. If not specified, 8 will be used.
	 */
	function setBarcodeHeight($height = 8) {
		self::validateInteger($height, 1, 255, __FUNCTION__);
		$this -> buffer -> write(self::GS . "h" . chr($height));
	}
	
	/**
	 * Turn double-strike mode on/off.
	 *
	 * @param boolean $on true for double strike, false for no double strike
	 */
	function setDoubleStrike($on = true) {
		self::validateBoolean($on, __FUNCTION__);
		$this -> buffer -> write(self::ESC . "G". ($on ? chr(1) : chr(0)));
	}
	
	/**
	 * Turn emphasized mode on/off.
	 *
	 *  @param boolean $on true for emphasis, false for no emphasis
	 */
	function setEmphasis($on = true) {
		self::validateBoolean($on, __FUNCTION__);
		$this -> buffer -> write(self::ESC . "E". ($on ? chr(1) : chr(0)));
	}
	
	/**
	 * Select font. Most printers have two fonts (Fonts A and B), and some have a third (Font C).
	 *
	 * @param int $font The font to use. Must be either Escpos::FONT_A, Escpos::FONT_B, or Escpos::FONT_C.
	 */
	function setFont($font = self::FONT_A) {
		self::validateInteger($font, 0, 2, __FUNCTION__);
		$this -> buffer -> write(self::ESC . "M" . chr($font));
	}
	
	/**
	 * Select justification.
	 *
	 * @param int $justification One of Escpos::JUSTIFY_LEFT, Escpos::JUSTIFY_CENTER, or Escpos::JUSTIFY_RIGHT.
	 */
	function setJustification($justification = self::JUSTIFY_LEFT) {
		self::validateInteger($justification, 0, 2, __FUNCTION__);
		$this -> buffer -> write(self::ESC . "a" . chr($justification));
	}
	
	/**
	 * Set black/white reverse mode on or off. In this mode, text is printed white on a black background.
	 * 
	 * @param boolean $on True to enable, false to disable.
	 */
	function setReverseColors($on = true) {
		self::validateBoolean($on, __FUNCTION__);
		$this -> buffer -> write(self::GS . "B" . ($on ? chr(1) : chr(0)));
	}
	
	/**
	 * Set underline for printed text.
	 * 
	 * Argument can be true/false, or one of UNDERLINE_NONE,
	 * UNDERLINE_SINGLE or UNDERLINE_DOUBLE.
	 * 
	 * @param int $underline Either true/false, or one of Escpos::UNDERLINE_NONE, Escpos::UNDERLINE_SINGLE or Escpos::UNDERLINE_DOUBLE. Defaults to Escpos::UNDERLINE_SINGLE.
	 */
	function setUnderline($underline = self::UNDERLINE_SINGLE) {
		/* Map true/false to underline constants */
		if($underline === true) {
			$underline = self::UNDERLINE_SINGLE;
		} else if($underline === false) {
			$underline = self::UNDERLINE_NONE;
		}
		/* Set the underline */
		self::validateInteger($underline, 0, 2, __FUNCTION__);
		$this -> buffer -> write(self::ESC . "-". chr($underline));
	}
	
	/**
	 * Add text to the buffer.
	 *
	 * Text should either be followed by a line-break, or feed() should be called
	 * after this to clear the print buffer.
	 *
	 * @param string $str Text to print
	 */
	function text($str = "") {
		self::validateString($str, __FUNCTION__);
		$this -> buffer -> writeText((string)$str);
	}
	
	/**
	 * Add text to the buffer without attempting to interpret chararacter codes.
	 *
	 * Text should either be followed by a line-break, or feed() should be called
	 * after this to clear the print buffer.
	 *
	 * @param string $str Text to print
	 */
	function textRaw($str = "") {
		self::validateString($str, __FUNCTION__);
		$this -> buffer -> writeTextRaw((string)$str);
	}
	
	/**
	 * Convert widths and heights to characters. Used before sending graphics to set the size.
	 * 
	 * @param array $inputs
	 * @param boolean $long True to use 4 bytes, false to use 2
	 * @return string
	 */
	private static function dataHeader(array $inputs, $long = true) {
		$outp = array();
		foreach($inputs as $input) {
			if($long) {
				$outp[] = Escpos::intLowHigh($input, 2);
			} else {
				self::validateInteger($input, 0 , 255, __FUNCTION__);
				$outp[] = chr($input);
			}
		}
		return implode("", $outp);
	}
	
	/**
	 * Generate two characters for a number: In lower and higher parts, or more parts as needed.
	 * @param int $int Input number
	 * @param int $length The number of bytes to output (1 - 4).
	 */
	private static function intLowHigh($input, $length) {
		$maxInput = (256 << ($length * 8) - 1);
		self::validateInteger($length, 1, 4, __FUNCTION__);
		self::validateInteger($input, 0, $maxInput, __FUNCTION__);
		$outp = "";
		for($i = 0; $i < $length; $i++) {
			$outp .= chr($input % 256);
			$input = (int)($input / 256);
		}
		return $outp;
	}
	
	/**
	 * Throw an exception if the argument given is not a boolean
	 * 
	 * @param boolean $test the input to test
	 * @param string $source the name of the function calling this
	 */
	private static function validateBoolean($test, $source) {
		if(!($test === true || $test === false)) {
			throw new InvalidArgumentException("Argument to $source must be a boolean");
		}
	}
	
	/**
	 * Throw an exception if the argument given is not an integer within the specified range
	 * 
	 * @param int $test the input to test
	 * @param int $min the minimum allowable value (inclusive)
	 * @param int $max the maximum allowable value (inclusive)
	 * @param string $source the name of the function calling this
	 */
	private static function validateInteger($test, $min, $max, $source) {
		if(!is_integer($test) || $test < $min || $test > $max) {
			throw new InvalidArgumentException("Argument to $source must be a number between $min and $max, but $test was given.");
		}
	}
	
	/**
	 * Throw an exception if the argument given can't be cast to a string
	 *
	 * @param string $test the input to test
	 * @param string $source the name of the function calling this
	 */
	private static function validateString($test, $source) {
		if (is_object($test) && !method_exists($test, '__toString')) {
			throw new InvalidArgumentException("Argument to $source must be a string");
		}
	}
}
