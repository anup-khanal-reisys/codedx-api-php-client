<?php
require(__DIR__ . '/bootstrap.php');
use Httpful\Request;

$cred_file = dirname(__FILE__) . '/settings.json';
$uri = $cred->uri;
$token = $cred->token;
$template = Request::init()->addHeader('API-Key', $token);

$cache_file = dirname(__FILE__) . '/api-cache.array';
$json_file = dirname(__FILE__) . '/api-cache.json';
$settings_file = dirname(__FILE__) . '/settings.array';
$settings = unserialize(file_get_contents($settings_file));
if(empty($settings)){
  $expires = time() + 24*60*60;
  $setting = array();
  $setting['expires'] = $expires;
  file_put_contents($settings_file, serialize($setting));
}else{
  $expires = $settings['expires'];
}
if ( time() > $expires || empty(unserialize(file_get_contents($cache_file)))) {
// Set it as a template
Request::ini($template);
$response = Request::get($uri.'/api/projects')->send();
print_r('<pre>');
$projects = $response->body->projects;
$array = array();
$arrayParent = array();
foreach($projects as $project){
  $arrayChild = array();
  $url = $uri.'/x/projects/'.$project->id.'/findings/grouped-counts';
  $responseA = (Request::post($url)
              ->body('{"filter": {"status": ["new","unresolved"]},"countBy": "severity"}')
              ->send())->body;
  foreach($responseA as $resp){
    $arrayChild[$resp->name]=$resp->count;
  }
  $arrayParent[$project->name] = $arrayChild;
}
  $array[$uri] = $arrayParent;
  if ( $array ){
    file_put_contents($cache_file, serialize($array));
    file_put_contents($json_file, json_encode($array));
  }else{
    unlink($cache_file);
  }
  echo json_encode($array);
}else{
  echo json_encode(unserialize(file_get_contents($cache_file)));
}
 ?>
