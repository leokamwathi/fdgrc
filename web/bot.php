<?php

//print_r($_GET["hub_challenge"]);
//file_put_contents("fb.txt",file_get_contents("php://input"));
try {

$hubChallenge = $_GET["hub_challenge"];
if (isset($hubChallenge) && $hubChallenge != '') {

print_r($hubChallenge);

}else{

$fb = file_get_contents("php://input");
$fb = json_decode($fb);

//{"object":"page","entry":[{"id":"763933067090623","time":1489656298161,"messaging":[{"sender":{"id":"1486644564679609"},"recipient":{"id":"763933067090623"},"timestamp":1489656298087,"message":{"mid":"mid.$cAAK2yxk7oTRhB7SCZ1a1m5n8K6Fr","seq":4271,"text":"rift"}}]}]}

$rid = $fb->entry[0]->id;
$sid = $fb->entry[0]->messaging[0]->sender->id;
$message = $fb->entry[0]->messaging[0]->message->text;

$botname = "livebot-".$sid;
//http://api.program-o.com/v2/chatbot/?bot_id=6&say=what%20do%20you%20eat&convo_id=fbbot-145896237&format=json
//if ($message > 0){
if (isset($message) && $message != '') {



$options = array(
'http' => array(
'method' => 'POST',
'content' => '',
'header' => "Content-Type: application/json\n"
)

);
$context = stream_context_create($options);
$path = "http://api.program-o.com/v2/chatbot/?bot_id=6&say=$message&convo_id=$botname&format=json";

$botReply = file_get_contents($path, false, $context);

file_put_contents("php://stderr", $path.$botReply.PHP_EOL);

//{"convo_id":"fbbot-145896237","usersay":"WHAT DO YOU EAT","botsay":"Program-O eats fairy cakes."}
$botReply = json_decode($botReply);
$botsay = "LIVEBOT: ".$botReply->botsay;


$token = "EAAUw3e7h6TMBAJZCWV7WRZB8cZCwmNNfDy2iMLuIDj6E3jDkCsds3OPScZArZCM0GcNALCkPKf067ZAHrrfjMZAvSAbtKRJC2MpSMl4jIgwRZBklbpPOWglMyN7gLi2afaFhifNCMQeG73cNY5dxsnRMp3fFOEaBzJQZD";

$data = array(
'recipient' => array('id'=> $sid),
'message' => array('text'=>$botsay)
);

$options = array(
'http' => array(
'method' => 'POST',
'content' => json_encode($data),
'header' => "Content-Type: application/json\n"
)

);
$context = stream_context_create($options);


$reply = file_get_contents("https://graph.facebook.com/v2.6/me/messages?access_token=$token", false, $context);
}
}
} catch (Exception $e) {
    // Handle exception
}
