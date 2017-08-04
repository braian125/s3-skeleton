<?php
$app->get('/', 'HomeController:index');


$app->get('/auth', 'AuthController:signin');
$app->post('/auth', 'AuthController:postSignin');

