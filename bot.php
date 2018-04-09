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
	/*		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$urlIm = 'https://api.line.me/v2/bot/message/' + $imageId + '/content';							*/
			$urlIm = 'https://image.ibb.co/dj8SSH/DE233625_7299_48_CD_897_D_44_A987166_BA0.jpg';
			 //can't get image fromline so i use image from link, need to do process after algo test monday
			$ch = curl_init ($urlIm);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$img=curl_exec($ch);
			echo curl_error($ch);
			curl_close ($ch);
			/*$fp = fopen($img,'x');
			fwrite($fp, $raw);
			fclose($fp);*/

			if(empty($img)){
				$talk = 'no pic';
			} else {
				$talk = $imageId;
			}

			$width = imagesx($img);
			$height = imagesy($img);
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
				//'text' => $text
				//'text' => $imageId
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

			// Build message to reply back
			if($text == 'ร้องขอการลงทะเบียน') {
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
				'text' => 'https://goo.gl/forms/Asv9LpuNMqqu57pX2'
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