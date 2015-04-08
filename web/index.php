<?php

use classes\Database\PDOdb;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$db = new PDOdb();

$app = new Silex\Application();
$app['debug'] = true;

//get all
$app->get('/resource', function () use ($db){
        $db->query("SELECT * FROM messages");
        return new JsonResponse($db->fetchAll());
});

//get one
$app->get('/resource/{id}', function ($id) use ($db){
    $db->query("SELECT * FROM messages WHERE id=:id");
    $db->bind(':id', $id);
    return new JsonResponse($db->fetchAll());
});

//delete one
$app->delete('/resource/{id}', function ($id) use ($db){
    return new JsonResponse($db->deleteById($id,'messages'));
});

//add one
$app->post('/resource', function (Request $request) use ($db){

    $payload = json_decode($request->getContent());;

    $newResource = [
        'author'  => $payload->author,
        'message' => $payload->message,
    ];

    $db->query("INSERT INTO messages (author,message) VALUES ('{$payload->author}','{$payload->message}')");
    $db->execute();

    return new JsonResponse($newResource);
});

//edit one
$app->post('/resource/{id}', function ($id, Request $request) use ($db){

    $payload = json_decode($request->getContent());

    $resource = [
        'author'  => $payload->author,
        'message' => $payload->message,
    ];

    $db->query("UPDATE messages WHERE id = :id");
    $db->bind('id',$id);
    $db->execute();

    return new JsonResponse($resource);
});


$app->run();