<?php

/** @var Router $router */
use Laravel\Lumen\Routing\Router;

// GET
$router->get('wiki/count', ['uses' => 'WikisController@count']);
// POST
$router->post('auth/login', ['uses' => 'AuthController@authenticate']);
$router->post('user/register', ['uses' => 'UserController@create']);
$router->post('user/verifyEmail', ['uses' => 'UserVerificationTokenController@verify']);
$router->post('user/sendVerifyEmail', ['uses' => 'UserVerificationTokenController@createAndSendForUser']);
$router->post('interest/register', ['uses' => 'InterestController@create']);

// Authed
$router->group(['middleware' => ['auth']], function () use ($router) {
    // user
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('self', ['uses' => 'UserController@getSelf']);
    });
    // wiki
    $router->group(['prefix' => 'wiki'], function () use ($router) {
        $router->post('create', ['uses' => 'WikiController@create']);
        $router->post('mine', ['uses' => 'WikisController@getWikisOwnedByCurrentUser']);
        $router->post('details', ['uses' => 'WikiController@getWikiDetailsForIdForOwner']);
        // TODO should wiki managers really be here?
        $router->post('managers/list', ['uses' => 'WikiManagersController@getManagersOfWiki']);
    });
    // admin
    $router->group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['admin']], function () use ($router) {
        // invitation
        $router->group(['prefix' => 'invitation'], function () use ($router) {
            $router->post('list', ['uses' => 'InvitationsController@get']);
            $router->post('create', ['uses' => 'InvitationController@create']);
            $router->post('delete', ['uses' => 'InvitationController@delete']);
        });
        // interest
        $router->group(['prefix' => 'interest'], function () use ($router) {
            $router->post('list', ['uses' => 'InterestsController@get']);
            // $router->post('create', ['uses' => 'InvitationController@create']);
        // $router->post('delete', ['uses' => 'InvitationController@delete']);
        });
    });
});

// Allow options methods on all routes?
// TODO do I really want this to be all routes?
$router->options('{all:.*}', ['middleware' => 'cors', function () {
    return response('');
}]);