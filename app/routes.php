<?php
use Framework\Routing\Router;

return function(Router $router)
{
    $router->add('GET', '/', fn() => 'hello world');
    $router->add('GET', '/old-home', fn() => $router->redirect('/'));
    $router->add('GET', '/has-server-error', fn() => throw new Exception());
    $router->add('GET', '/has-validation-error', fn() => $router->dispatchNotAllowed());
    $router->add('GET', '/auth/login', fn() => '<h1>Login Page</h1>');
    $router->add('GET', '/api/todos', function() {
        $todos = [
          [
              'id'=>1,
              'description'=> 'Build php framework',
              'completed'=> false
          ],
            [
                'id'=>2,
                'description'=> 'Finish Vue masterclass course',
                'completed'=> false
            ],
            [
                'id'=>1,
                'description'=> 'Prepare the tools and equipments',
                'completed'=> true
            ]
        ];
        return json_encode($todos, JSON_PRETTY_PRINT);
    });
//    $router->errorHandler(404, fn() => 'whoops');
    $router->add('GET', '/rockets', fn() => '<h3>RocketController@index</h3>');
};