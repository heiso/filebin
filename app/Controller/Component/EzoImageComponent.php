<?php 
/**
* @auteur : Alexandre Moatty 2011
**/
class EzoImageComponent extends Component
{ 
	
	// private $webroot = WWW_ROOT;
	private $webroot = '';
	
	public function cropResize($image_src, $image_dst, $width_dest = 0, $height_dest = 0, $resize_inf = false, $force_ext = '') {
		$image_src = $this->webroot.$image_src;
		$image_dst = $this->webroot.$image_dst;
		
		$ext = strtolower(substr(strrchr($image_src,'.'),1));
		$size = getimagesize($image_src);
		$width_src = $size[0];
		$height_src = $size[1];
		if($width_dest == 0 || $height_dest == 0) {
			$y_coord = 0;
			$x_coord = 0;
			$width_tmp = $width_src;
			$height_tmp = $height_src;
			$width_dest = $width_src;
			$height_dest = $height_src;
		}
		else {
			if(!$resize_inf && $width_src < $width_dest && $height_src < $height_dest) {
				$y_coord = 0;
				$x_coord = 0;
				$width_tmp = $width_src;
				$height_tmp = $height_src;
				$width_dest = $width_src;
				$height_dest = $height_src;
			}
			else {
				if($width_dest >= $height_dest) {
					$x_coord = 0;
					$width_tmp = $width_src;
					$height_tmp = ($height_dest*$width_tmp)/$width_dest;
					$y_coord = ($height_src-$height_tmp)/2;
					if($height_tmp > $height_src) {
						$y_coord = 0;
						$height_tmp = $height_src;
						$width_tmp = ($width_dest*$height_tmp)/$height_dest;
						$x_coord = ($width_src-$width_tmp)/2;
					}
				}
				elseif($width_dest < $height_dest) {
					$y_coord = 0;
					$height_tmp = $height_src;
					$width_tmp = ($width_dest*$height_tmp)/$height_dest;
					$x_coord = ($width_src-$width_tmp)/2;
					if($width_tmp > $width_src) {
						$x_coord = 0;
						$width_tmp = $width_src;
						$height_tmp = ($height_dest*$width_tmp)/$width_dest;
						$y_coord = ($height_src-$height_tmp)/2;
					}
				}
			}
		}
		
		$img_crop = imagecreatetruecolor($width_dest, $height_dest);
		if($ext == 'jpeg') 
			$ext = 'jpg';
		switch($ext){
			case 'bmp': $image_tmp = imagecreatefromwbmp($image_src); break;
			case 'gif': $image_tmp = imagecreatefromgif($image_src); break;
			case 'jpg': $image_tmp = imagecreatefromjpeg($image_src); break;
			case 'png': $image_tmp = imagecreatefrompng($image_src); break;
		}
		if($ext == 'gif' or $ext == 'png'){
			imagecolortransparent($img_crop, imagecolorallocatealpha($img_crop, 0, 0, 0, 127));
			imagealphablending($img_crop, false);
			imagesavealpha($img_crop, true);
		}
		imagecopyresampled($img_crop, $image_tmp, 0, 0, $x_coord, $y_coord, $width_dest, $height_dest, $width_tmp, $height_tmp);
		if($force_ext != '')
			$ext = $force_ext;
		switch($ext){
			case 'bmp': imagewbmp($img_crop, $image_dst); break;
			case 'gif': imagegif($img_crop, $image_dst); break;
			case 'jpg': imagejpeg($img_crop, $image_dst); break;
			case 'png': imagepng($img_crop, $image_dst); break;
		}
		
		/*--debug--*/
		/* echo 'img src = '.$image_src.'<br />
		img dest = '.$image_dst.'<br />
		width_dest = '.$width_dest.'<br />
		height_dest = '.$height_dest.'<br />
		width_src = '.$width_src.'<br />
		height_src = '.$height_src.'<br />
		width_tmp = (height_tmp x width_dest)/height_dest<br />
		'.$width_tmp.' = ('.$height_tmp.' x '.$width_dest.')/'.$height_dest.' = '.($height_tmp*$width_dest)/$height_dest.'<br /><br />'; */

		imagedestroy($image_tmp);
		imagedestroy($img_crop);
	}
	
	public function textToImage($text, $image_dst) {
		$image_dst = $this->webroot.$image_dst;
		
		$width = strlen($text)*9+6;
		$height = 16;
		$img = imagecreate($width, $height);
		$white = imagecolorallocate($img, 255, 255, 255);
		$black = imagecolorallocate($img, 0, 0, 0);
		imagestring($img, 5, 3, 0, $text, $black);
		imagepng($img, $image_dst);
		imagedestroy($img);
	}
	
	public function textOnImage($image_src, $text, $font_size, $x, $y) {
		$image_src = $this->webroot.$image_src;
		
		$infos = pathinfo($image_src);
		$ext = strtolower($infos['extension']);
		if($ext == 'jpeg') 
			$ext = 'jpg';
		switch($ext){
			case 'bmp': $img = imagecreatefromwbmp($image_src); break;
			case 'gif': $img = imagecreatefromgif($image_src); break;
			case 'jpg': $img = imagecreatefromjpeg($image_src); break;
			case 'png': $img = imagecreatefrompng($image_src); break;
		}
		$color_black = imagecolorallocate($img, 0, 0, 0);
		imagestring($img, $font_size, $x, $y, $text, $color_black);
		switch($ext){
			case 'bmp': imagewbmp($img, $image_src); break;
			case 'gif': imagegif($img, $image_src); break;
			case 'jpg': imagejpeg($img, $image_src); break;
			case 'png': imagepng($img, $image_src); break;
		}
		imagedestroy($img);
	}
	
}