<?php

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$client = new Client();
$crawler = $client->request('GET', 'http://marvel.com/comics/characters');
$lists1 = $crawler->filter('ul.JCAZList-MultiCol')->children();
$lists2 = array();


var_dump($lists2);

$app->get('/', function() use ($crawler){

//    return json_encode($lists2);

    return $crawler->filter('ul.JCAZList-MultiCol')->children()->each(function (Crawler $node, $i) {
//         $lists[] = $node->text();
         echo $node->text();

//        return $i;
    });

});
//$crawler->filter('h2')->each(function ($node) {
//    return $node->text()."\n";
//});
//$crawler->filter('li')->attr('JCAZList-MultiCol');


$toys = array(
    '00001'=> array(
         'name' => 'Racing Car',
     'quantity' => '53',
     'description' => '...',
     'image' => 'racing_car.jpg',
 ),
 '00002' => array(
         'name' => 'Raspberry Pi',
        'quantity' => '13',
        'description' => '...',
        'image' => 'raspberry_pi.jpg',
    ),
);

$app->get('/toys', function() use ($toys) {
     return json_encode($toys);
});

$blogPosts = array(
    1 => array(
        'date'      => '2011-03-29',
        'author'    => 'igorw',
        'title'     => 'Using Silex',
        'body'      => '...',
    ),
);

$app->get('/blog', function () use ($blogPosts) {
    $output = '';
    foreach ($blogPosts as $post) {
        $output .= $post['title'];
        $output .= '<br />';
    }

    return $output;
});


$app->run();

