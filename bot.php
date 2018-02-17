﻿<?php
$access_token = 'vJdzjrUAxybexlcIhApBv8hS0XZHICcF7poHIcSGaVGgHK4/xiYkWs1FCyl9LQRy3Mwh9MIwmvZg/n/0pRxDZLDsCES76NChzi3eAeEhiP0HczzHv/L2SI6eInqZeSkr68e/zwRVeuwD8EGY5d2yfQdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		if ($event['type'] == 'message' && $event['message']['type'] == 'image') {
			// Get text sent
			$image = $event['message']['image'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => 'เป็นโรค'
			];

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
		// Reply only when message sent is in 'text' format
		else if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			if($text == 'ขอรับ link กรอกข้อมูล') {
			$messages = [
				'type' => 'text',
				'text' => 'https://goo.gl/forms/LeQgHX7Kuv6s6Plx1'
			];
			} else if($text == 'ขอรายชื่อโรงพยาบาลที่เกี่ยวข้อง') {
			$messages = [
				'type' => 'text',
				'text' => 'โรงพยาบาล A เบอร์ติดต่อ 02-000-0000                   โรงพยาบาล B เบอร์ติดต่อ 02-111-1111 , 0x100005'
			];
			} else if($text == 'ปัจจัยเสี่ยงของต้อหิน') {
			$messages = [
				'type' => 'text',
				'text' => 'coming soon'
			];
			} 
			 else if($text == 'coming soon...') {
			$messages = [
				'type' => 'text',
				'text' => 'coming soon'
			];
			} else {
			$messages = [
				'type' => 'text',
				'text' => 'หากมีคำถาม หรือต้องการใช้บริการอะไร กรุณากดปุ่มใน bulletin หรือหากต้องการตรวจต้อหินเบื้องต้น กรุณาส่งรูปภาพ ขอบคุณครับ'
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