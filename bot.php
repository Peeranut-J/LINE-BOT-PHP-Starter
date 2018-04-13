<?php
$access_token = 'vJdzjrUAxybexlcIhApBv8hS0XZHICcF7poHIcSGaVGgHK4/xiYkWs1FCyl9LQRy3Mwh9MIwmvZg/n/0pRxDZLDsCES76NChzi3eAeEhiP0HczzHv/L2SI6eInqZeSkr68e/zwRVeuwD8EGY5d2yfQdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);

/*function grab_image($url){
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $raw=curl_exec($ch);
    curl_close ($ch);
    if(file_exists($saveto)){
        unlink($saveto);
    }
    $fp = fopen($saveto,'x');
    fwrite($fp, $raw);
    fclose($fp);
}*/

function testMakeFunction($one){
	if($one == 1){
		$tell = 'yes its work' ;
	} else {
		$tell = 'nooooooo' ;
	}
	return $tell;
}

function grab_image($url,$saveto){
	error_log('url = ' . $url,0);
	error_log('saveto = ' . $saveto,0);
    $ch = curl_init ($url);
	error_log('ch = ' . $ch,0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $raw=curl_exec($ch);
	echo($raw);
	error_log('raw = ' . $raw,0);
    curl_close ($ch);
    if(file_exists($saveto)){
        unlink($saveto);
    }
    $fp = fopen($saveto,'x');
	error_log('fp = ' . $fp,0);
    fwrite($fp, $raw);
    fclose($fp);
	return $saveto;
}

function imageCreateFromAny($filepath,$img) { 
    $type = getimagesize($filepath); // [] if you don't have exif you could use getImageSize() 
	error_log('type = ' . $type . ' url = ' . $filepath . ' img = ' . $img);
    $allowedTypes = array( 
        1,  // [] gif 
        2,  // [] jpg 
        3  // [] png 
    ); 
    if (!in_array($type, $allowedTypes)) { 
        return false; 
    } 
    switch ($type) { 
        case 1 : 
            $im = imageCreateFromGif($img); 
        break; 
        case 2 : 
            $im = imageCreateFromJpeg($img); 
        break; 
        case 3 : 
            $im = imageCreateFromPng($img); 
        break;
    }    
    return $im;  
} 


//from http://php.net/manual/en/function.imagejpeg.php comment 1
function scaleImageFileToBlob($file) {

    $source_pic = $file;
    $max_width = 200;
    $max_height = 200;

    list($width, $height, $image_type) = getimagesize($file);

    switch ($image_type)
    {
        case 1: $src = imagecreatefromgif($file); break;
        case 2: $src = imagecreatefromjpeg($file);  break;
        case 3: $src = imagecreatefrompng($file); break;
        default: return '';  break;
    }

    $x_ratio = $max_width / $width;
    $y_ratio = $max_height / $height;

    if( ($width <= $max_width) && ($height <= $max_height) ){
        $tn_width = $width;
        $tn_height = $height;
        }elseif (($x_ratio * $height) < $max_height){
            $tn_height = ceil($x_ratio * $height);
            $tn_width = $max_width;
        }else{
            $tn_width = ceil($y_ratio * $width);
            $tn_height = $max_height;
    }

    $tmp = imagecreatetruecolor($tn_width,$tn_height);

    /* Check if this image is PNG or GIF, then set if Transparent*/
    if(($image_type == 1) OR ($image_type==3))
    {
        imagealphablending($tmp, false);
        imagesavealpha($tmp,true);
        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
        imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
    }
    imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

    /*
     * imageXXX() only has two options, save as a file, or send to the browser.
     * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
     * So I start the output buffering, use imageXXX() to output the data stream to the browser, 
     * get the contents of the stream, and use clean to silently discard the buffered contents.
     */
    ob_start();

    switch ($image_type)
    {
        case 1: imagegif($tmp); break;
        case 2: imagejpeg($tmp, NULL, 100);  break; // best quality
        case 3: imagepng($tmp, NULL, 0); break; // no compression
        default: echo ''; break;
    }

    $final_image = ob_get_contents();

    ob_end_clean();

    return $final_image;
}

// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		if ($event['type'] == 'message' && $event['message']['type'] == 'image') {
			// Get text sent
			//$image = $event['message']['image'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			$imageId = $event['message']['id'];

			//curl -v -X GET https://api.line.me/v2/bot/message/$imageId/content \
			//-H 'Authorization: Bearer $access_token'
			/*
			$img = 'white.jpg';
			//$im = imagecreatefrompng('./white.jpg');
			header('Content-Type: image/jpeg');
			readfile($img);
			$colors   = Array();
			$colors[] = imagecolorexact($img, 255, 0, 0);
			$colors[] = imagecolorexact($img, 0, 0, 0);
			$colors[] = imagecolorexact($img, 255, 255, 255);
			$colors[] = imagecolorexact($img, 100, 255, 52);

			print_r($colors);
			*/
		/*	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
			$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '4abf1bc163c4ae75b6c4e365d0510dd7']);
			$response = $bot->getMessageContent($message_id);
			if ($response->isSucceeded()) {
				$tempfile = tmpfile();
				fwrite($tempfile, $response->getRawBody());
				$text = sys_get_temp_dir();
				$talk = 'yes';
			} else {
				//error_log($response->getHTTPStatus() . ' ' . $response->getRawBody());
				$talk = 'no';
			}*/
			
			/*$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$urlIm = 'https://api.line.me/v2/bot/message/' + $imageId + '/content' ;*/
		/*	$ch = curl_init($urlIm);
			$fp = fopen('/my/folder/flower.gif', 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			//curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
			*/
		//	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$urlIm = 'https://api.line.me/v2/bot/message/' . $imageId . '/content';							
	//		$urlIm = 'https://image.ibb.co/dj8SSH/DE233625_7299_48_CD_897_D_44_A987166_BA0.jpg';
			 //can't get image fromline so i use image from link, need to do process after algo test monday
			 //update : can use image from line la
			$ch = curl_init ($urlIm);
	//		curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$raw=curl_exec($ch);
			//not work
			//$rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
			//$rescode = file_get_contents($ch);
			echo curl_error($ch);
			curl_close ($ch);
			$save_to = ' ';
			$fp = fopen($save_to,'x');
			fwrite($fp, $raw);
			fclose($fp);

	//		$urlIm = 'https://image.ibb.co/jNTDNH/normal1.jpg';
			$data = getimagesize($ch);
			$width = $data[0];
			$height = $data[1];
			$talk = $width . ' ' . $height;
			error_log($talk , 0);

			error_log('save_to = ' . $save_to,0);
			$ch = $save_to;

			$img = imagecreatefromjpeg($ch); // resource id = xxx ;
			error_log('ch = ' . $ch . 'img = ' . $img, 0);
			$rgb = imagecolorat($img, 800, 608);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			error_log('r g b = ' . $r . ' ' . $g . ' ' . $b, 0);

			//for all function

	//		error_log("img + urlIm + rescode" , 0);
	//		error_log('ch = ' . $ch , 0);
			//error_log("space" , 0);
			error_log('url = ' . $urlIm , 0);
			//error_log($ch , 0);
			//error_log($rescode , 0);

			if(empty($img)){
				$talk = 'no pic';
			}
			//$talk = getimagesize($urlIm);
			//error_log($talk , 0);
	//		$data = getimagesize($img);
	/*
			$data = getimagesize($rescode);
			$width = $data[0];
			$height = $data[1];
			$talk = $width . ' ' . $height;
			error_log($talk , 0);
	*/

			//$width = imagesx($img);
			//$height = imagesy($img);
			/*$count = 0;
			for($x = 0; $x < $width; $x++) {
				for($y = 0; $y < $height; $y++) {
				// pixel color at (x, y)
					$color = imagecolorat($img, $x, $y);
					$count++;
				}
			}*/
			//$talk = (string)$count;


/*
			header("Content-Type: image/jpeg");

			$url = 'https://images.nga.gov/en/web_images/constable.jpg';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.1 Safari/537.11');
			$res = curl_exec($ch);
			$rescode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
			curl_close($ch) ;
			echo $res;*/
			/*if(file_exists($saveto)){
				unlink($saveto);
			}
			$fp = fopen($saveto,'x');
			fwrite($fp, $raw);
			fclose($fp);*/
			/*$url = 'https://api.line.me/v2/bot/message/' + $imageId + '/content';
			$img;
			file_put_contents($img, file_get_contents($url));
			if(imagecolorexact($img, 255, 0, 0) > 0){
				$messages = [
				'type' => 'text',
				'text' => 'Hello'
			];
			}
*/
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $talk
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
				//'messages' => [$image],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
		// Reply only when message sent is in 'text' format
		else if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			if($text == 'functiontest'){
				$number = 1;
				$test1 = testMakeFunction($number);
				$messages = [
				'type' => 'text',
				'text' => $test1
				];
			}
			else if($text == 'normal1'){

			/*
				header('Content-type: image/jpeg');
				$getcon = file_get_contents($urlIm);
				error_log('img from get content = ' . $getcon, 0);
				$hi5 = scaleImageFileToBlob($getcon);
				error_log('img from funct -- getcon = ' . $hi5, 0);
				$rgb0 = imagecolorat($getcon, $width/2, $height/2);
				$r0 = ($rgb >> 16) & 0xFF;
				$g0= ($rgb >> 8) & 0xFF;
				$b0 = $rgb & 0xFF;
				error_log('r0 g0 b0 = ' . $r0 . ' ' . $g0 . ' ' . $b0, 0);
				
				$hi1 = scaleImageFileToBlob($urlIm);
				error_log('img from funct -- url = ' . $hi1, 0);		*/
				//function in range inner
				//function in range outer
				//function in range deadEye
				//function in range .... etc.
				/*
				//$remoteImage = "http://www.example.com/gifs/logo.gif";
				$imginfo = getimagesize($urlIm);
				header("Content-type: image/jpeg");
				$rf = readfile($urlIm);
				error_log('imginfo = ' . $rf, 0);
				$rgb0 = imagecolorat($rf, $width/2, $height/2);
				$r0 = ($rgb >> 16) & 0xFF;
				$g0= ($rgb >> 8) & 0xFF;
				$b0 = $rgb & 0xFF;
				error_log('r0 g0 b0 = ' . $r0 . ' ' . $g0 . ' ' . $b0, 0);
				*/
				//need to make picture from url .jpg
			//	header('Content-Type: image/jpeg');
			/*
				$img = imagecreatefromjpeg($urlIm);
				error_log('img00 = ' . $img, 0);

				$hi3 = scaleImageFileToBlob($img);
				error_log('img from funct -- createimg = ' . $hi3, 0);

				$test00 =  imagejpeg($img);
			//	imagedestroy($img);
				error_log('test00 = ' . $test00, 0);
				error_log('img = ' . $img, 0);
				$hi4 = scaleImageFileToBlob($test00);
				error_log('img from funct -- test00 = ' . $hi4, 0);
				*/
				/*
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $urlIm); 
			//	$fp = fopen("example_homepage.jpg", "w");
			//	curl_setopt($ch, CURLOPT_FILE, $fp);
			//	curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // good edit, thanks!
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1); // also, this seems wise considering output is image.
				$data = curl_exec($ch);
				curl_close($ch);
				$hi2 = scaleImageFileToBlob($data);
				error_log('img from funct -- data = ' . $hi2, 0);
				*/
				/*
				$datax = base64_decode($data);
				$imdtx = imagecreatefromstring($datax);
				header('Content-Type: image/png');
				$test01 = imagejpeg($imdtx);
				error_log('test01 = ' . $test01, 0);
				error_log('imgdtx = ' . $imdtx, 0);
				$hi6 = scaleImageFileToBlob($test01);
				error_log('img from funct -- test01 = ' . $hi6, 0);
				*/
			//	fclose($fp);
			//	$img = imagecreatefromjpeg("example_homepage.jpg");
		//		$img = imagecreatefromstring($data);
			//	$img = imagecreatefromjpeg($data);
			//	$img = @imagecreatefromjpeg($urlIm);
			//	$img = @imagecreatefrompng($urlIm);
				/*
				$rgb = imagecolorat($urlIm, $width/2, $height/2);
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;
				$colors = imagecolorsforindex($img, $rgb);
				error_log('color using img = ' . $colors, 0);
		//		$colors = imagecolorsforindex($test00, $rgb);
				error_log('url = ' . $urlIm, 0);
				error_log('img = ' . $img, 0);
				error_log('rgb = ' . $rgb, 0);
		//		error_log('color using test00 = ' . $colors, 0);
				$talk = $r . ' ' . $g . ' ' . $b . ' w = ' . $width/2 . ' h = ' . $height/2 . ' c0 = ' . $colors[0] . ' c1 = ' . $colors[1] . ' img =  ' . $img . ' rgb = ' . $rgb; 
				error_log($talk , 0);
				*/
				/*
				$handle = fopen($urlIm, "r");
				error_log('handle = ' . $handle , 0);
				$rgb0 = imagecolorat($handle, $width/2, $height/2);
				$r0 = ($rgb >> 16) & 0xFF;
				$g0= ($rgb >> 8) & 0xFF;
				$b0 = $rgb & 0xFF;
				error_log('r0 g0 b0 = ' . $r0 . ' ' . $g0 . ' ' . $b0, 0);
				
				$simg;
				$simg = grab_image($urlIm,$simg);
				$esimg = empty($simg);
				error_log('empty simg? = ' . $esimg,0);
				error_log('simg = ' . $simg , 0);
				$rgb0 = imagecolorat($simg, $width/2, $height/2);
				$r0 = ($rgb >> 16) & 0xFF;
				$g0= ($rgb >> 8) & 0xFF;
				$b0 = $rgb & 0xFF;
				error_log('r0 g0 b0 = ' . $r0 . ' ' . $g0 . ' ' . $b0, 0);			*/
				/*
		//		$url = $_GET['url'];
				$allow = ['gif', 'jpg', 'png'];  // allowed extensions
				$img = file_get_contents($urlIm);  // get image data from $url
				$url_info = pathinfo($urlIm);

				// if allowed extension
				if(in_array($url_info['extension'], $allow)) {
				  $save_to = $url_info['basename'];  // add image with the same name in 'imgs/' folder
				  if(file_put_contents($save_to, $img)) {
					$re = '<img src="'.  $save_to .'" title="'. $url_info['basename'] .'" />';
				  }
				  else $re = 'Unable to save the file';
				}
				else $re = 'Invalid extension: '. $url_info['extension'];
				error_log('re = ' . $re,0);
				*/
		//		$url = $_GET['url'];
		/*		$allow = ['gif', 'jpg', 'png'];  //allowed extensions
				$url_info = pathinfo($urlIm);
				$save_to = $url_info['basename'];  //set image path with the same name in 'imgs/'
				$re ='';  //for data to output	*/
/*
				$data = getimagesize($img);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log('w h = ' . $talk . "\n");
*/	
				

			//	error_log('Hi r g b = ' . $r . ' ' . $g . ' ' . $b . "\n");
	
				//echo 'r g b = ' . $r . ' ' . $g . ' ' . $b;
/*
				//if allowed extension
				if(in_array($url_info['extension'], $allow)){
				  //if the file not exists on server, gets its data from url, and saves it
				  if(!file_exists($save_to)){
					$img = file_get_contents($urlIm);
					if(!file_put_contents($save_to, $img)) $re ='Unable to save the file';
				  }
				}
				else $re ='Invalid extension: '. $url_info['extension'];
				error_log('re = ' . $re,0);

				$rgb0 = imagecolorat($save_to, $width/2, $height/2);
				$r0 = ($rgb >> 16) & 0xFF;
				$g0= ($rgb >> 8) & 0xFF;
				$b0 = $rgb & 0xFF;
				error_log('r0 g0 b0 = ' . $r0 . ' ' . $g0 . ' ' . $b0, 0);
*/
				$urlIm = 'https://image.ibb.co/jNTDNH/normal1.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$img = imagecreatefromjpeg($urlIm); // resource id = xxx ;
		//		$img = imageCreateFromAny($urlIm);
				error_log('img url = ' . $urlIm , 0);
				$rgb = imagecolorat($img, 800, 608);
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;
				error_log('r g b = ' . $r . ' ' . $g . ' ' . $b, 0);

				if(empty($rgb)){
					$talk = 'empty rgb';
				}
								
				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}else if($text == 'normal2'){
				$urlIm = 'https://image.ibb.co/j0sCGc/normal2.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}else if($text == 'normal3'){
				$urlIm = 'https://image.ibb.co/jNbuUx/normal3.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}else if($text == 'glau1'){
				$urlIm = 'https://preview.ibb.co/n3i2Gc/glau1.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}else if($text == 'glau2'){
				$urlIm = 'https://image.ibb.co/n324wc/glau2.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}else if($text == 'glau3'){
				$urlIm = 'https://preview.ibb.co/kJstpx/glau3.jpg';
				$data = getimagesize($urlIm);
				$width = $data[0];
				$height = $data[1];
				$talk = $width . ' ' . $height;
				error_log($talk , 0);

				$messages = [
				'type' => 'text',
				'text' => $talk
				];
			
			}
			// Build message to reply back
			else if($text == 'ร้องขอการลงทะเบียน') {
			$messages = [
				'type' => 'text',
				'text' => 'https://goo.gl/forms/gf1EF2X9kVdCjDR32'
			];
			} else if($text == 'ขอรับlinkกรอกข้อมูลอาการ') {
			$messages = [
				'type' => 'text',
				'text' => 'https://goo.gl/forms/Cd0medoTwiKYGgKv2'
			];
			} else if($text == 'ขอดูผลการวินิจฉัย') {
			$messages = [
				'type' => 'text',
				'text' => 'ผลการวินิจฉัย ณ ปัจจุบัน แพทย์จะส่งทางอีเมลล์ที่ท่านลงทะเบียนไว้ ภายใน 7 วันทำการ ขอบคุณครับ'
			];
			} else if($text == 'ขอรายชื่อโรงพยาบาลที่เกี่ยวข้อง') {
			$messages = [
				'type' => 'text',
				'text' => 'โรงพยาบาล A เบอร์ติดต่อ 02-000-0000                   โรงพยาบาล B เบอร์ติดต่อ 02-111-1111                       โรงพยาบาล C เบอร์ติดต่อ 02-456-8795                   โรงพยาบาล D เบอร์ติดต่อ 02-789-4561'
			];
			} else if($text == 'ปัจจัยเสี่ยงของต้อหิน') {
			$messages = [
				'type' => 'text',
				'text' => 'ปัจจัยเสี่ยงของโรคต้อหินนั้น จะอบ่งออกเป็น 2 ลักษณะ คือ ต้อหินเฉียบพลัน กับ ต้อหินเรื้องรัง                                          ต้อหินเฉียบพลันจะมีปัจจัยเสี่ยงโดยคร่าวๆ ดังนี้                                           1. เป็นผู้หญิง                                                                                     2. เป็นผู้มีเชื้อสายเอเซีย                                                              3. อายุมากกว่า40ปี                                                               4. มีสายตายาว                                                               5. ครอบครัวมีประวัติเคยเป็นโรคนี้                                                               ต้อหินเรื้อรังจะมีปัจจัยเสี่ยงโดยคร่าวๆ ดังนี้                                                              1. มีเชื้อสายแอฟริกัน                                                              2. เป็นโรคเรื้อรังบางประเภท เช่น โรคหัวใจ โรคความดันโลหิตสูง                                                               3. เป็นโรคเบาหวาน                                                               4. มีสายตาสั้น                                                               5. ครอบครัวมีประวัติเคยเป็นโรคนี้                                                               6. ความดันลูกตาสูงผิดปกติ                                                               7. กระจกตาบางกว่าปกติ                                                               8. เคยได้รับการผ่าตัดดวงตา                                                               9. เคยได้รับการรักษาโรคเรื้อรังทางดวงตา                                                               10. เคยได้รับอุบัติเหตุทางตา                                                               11. เคยมีประวัติการใช้งานยาหยอดตาและยารับประทานบางชนิด โดยเฉพาะยาสเตียรอยด์                                                                ข้อมูลเพิ่มเติม : https://medthai.com/ต้อหิน/                                                                อ้างอิง : https://medthai.com/ต้อหิน/'
			];
			} 
			 else if($text == 'ขอทราบวิธีการใช้งาน App') {
			$messages = [
				'type' => 'text',
				'text' => 'ในการใช้งาน Glaucoma checker bot นั้น มีวิธีใช้งาน ดังนี้                                     1.หากต้องการตรวจสอบเบื้องต้นว่าเป็นโรคต้อหินหรือไม่ กรุณาถ่ายภาพดวงตาของท่านด้วยอุปกรณ์ แล้วอัพโหลดรูปลงในไลน์บอทนี้                                     2. หากบอทได้ตอบกลับว่า "มีโอกาสเป็นโรค" หรือ "เป็นโรค" กรุณากดที่ปุ่ม "ขอรับ link กรอกข้อมูล" เพื่อที่บอทจะดำเนินการส่ง link google form สำหรับกรอกข้อมูลให้กับท่าน                                          3. หากต้องการทราบรายชื่อและเบอร์ติดต่อโรงพยาบาลที่เกี่ยวข้อง กรุณากดที่ปุ่ม "โรงพยาบาลที่เกี่ยวข้อง"                                     4. หากต้องการรายละเอียดปัจจัยเสี่ยงของต้อหิน กรุณากดที่ปุ่ม "ปัจจัยเสี่ยงของต้อหิน"'
			];
			} else {
			$messages = [
				'type' => 'text',
				'text' => 'หากมีคำถาม หรือต้องการใช้บริการอะไร กรุณากดปุ่มใน App Menu หรือหากต้องการตรวจต้อหินเบื้องต้น กรุณาส่งรูปภาพ ขอบคุณครับ'
			];
			}

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
		
	}
}
echo "OK";