<?php
use Framework\Routing\Router;

return function(Router $router)
{
    $router->add('GET', '/', fn() => 'hello world');
    $router->add('GET', '/old-home', fn() => $router->redirect('/'));
    $router->add('GET', '/has-server-error', fn() => throw new Exception());
    $router->add('GET', '/has-validation-error', fn() => $router->dispatchNotAllowed());
    $router->add('GET', '/auth/login', fn() => '<h1>Login Page</h1>');
    $router->add('GET', '/api/todo', function() {
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
//    $router->add('GET', '/rockets/{id}', fn() => '<h3>RocketController@index</h3>');
//    $router->add('GET', '/invoices/{id}', fn() => '<p>invoice number #898</p>');
//    $router->add('GET', '/products/view/{product}/{id}', fn() => '<p>product Dell::3537</p>');

    $router->add('GET', '/products/view/{product}/{id}', function () use($router) {
        $parameters = $router->current()->parameters();
        var_dump($parameters);
       return "product is {$parameters['product']} with #{$parameters['id']}";
    });

    $router->add('GET', 'blog/{slug?}', function() use($router) {
    return $router->current()->parameters()['slug'];
    });

    $router->add('GET', '/profile/{id}', function() use ($router) {
       return "<p>Welcome to your profile number# {$router->current()->parameters()['id']}</p>";
    });
    $router->add(
        'GET', '/products/{page?}',
        function () use ($router) {
            $parameters = $router->current()->parameters();
            $parameters['page'] ??= 1;
            return "products for page {$parameters['page']}";
        },
    );
//    ->name('product-list');
};