<?php
function upload(
  $api_key,
  $secret_key,
  $email,
  $pass,
  $vidName,
  $vidDesc,
  $proxy,
  $testVideoFile,
  $channel,
  $tags
){
  define("DAILYMOTION_API_KEY", $api_key);
  define("DAILYMOTION_API_SECRET_KEY", $secret_key);
  $testUser = $email;
  $testPassword = $pass;
  $url = 'https://api.dailymotion.com/oauth/token';
  $testVideoFile = $testVideoFile;
  $vidName = $vidName;
  $vidDesc = $vidDesc;
  try {
  $data = "grant_type=password&client_id=".$api_key."&client_secret=".$secret_key."&username=$testUser&password=$testPassword&scope=read+write";
  $curlInit = curl_init($url);
  curl_setopt($curlInit, CURLOPT_POST, 1);
  curl_setopt($curlInit, CURLOPT_POSTFIELDS, $data);
  curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($curlInit);
  curl_close($curlInit);
  $res = json_decode($output);
  // http://baytkod.com/demo/dail/[dynamic_part]
  $accessToken = $res->access_token;
  $getUploadUrl = "curl -d 'access_token=$accessToken' -G  https://api.dailymotion.com/file/upload/ $proxy ";

  $uploadUrl = json_decode(exec($getUploadUrl));
  $postFileCmd = "curl -F 'file=@$testVideoFile'" . ' "' . $uploadUrl->upload_url . '"';
  $postFileResponse = json_decode(exec($postFileCmd));
  $postVideoCmd = "curl -d 'access_token=$accessToken&url=$postFileResponse->url' https://api.dailymotion.com/me/videos $proxy";

  $postVideoResponse = json_decode(exec($postVideoCmd));
  $videoId = $postVideoResponse->id;

  $publishCmd = "curl -F 'access_token=$accessToken' \
   -F 'title=$vidName' \
   -F 'published=true' \
   -F 'description=$vidDesc' \
   -F 'channel=$channel' \
   https://api.dailymotion.com/video/$videoId $proxy ";

   $publishres = exec($publishCmd);
   } catch (Exception $e) {
     print_r($e);
   }
   $datax[a1] = $postVideoResponse;
   $datax[a2] = $publishres;
   $datax[a3] = $postFileResponse;
   return $datax;
}
