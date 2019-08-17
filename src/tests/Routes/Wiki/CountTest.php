<?php

namespace App\Tests\Routes\Wiki\Managers;

use App\Wiki;
use App\Tests\TestCase;
use App\Tests\Routes\Traits\CrossSiteHeadersOnOptions;
use App\Tests\Routes\Traits\OptionsRequestAllowed;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * @covers WikiController::count
 */
class CountTest extends TestCase
{

  protected $route = 'wiki/count';

  use CrossSiteHeadersOnOptions;
  use OptionsRequestAllowed;

    use DatabaseTransactions;

    public function setUp(){
      parent::setUp();
      Wiki::all()->each(function($a){$a->destroy($a->id);});
    }

    public function testRootPostNotAllowed()
    {
        $this->post($this->route);
        // Method not allowed
        $this->assertEquals(405, $this->response->status());
    }

    public function testWikiCountNone()
    {
        $this->get('/wiki/count')->seeJsonEquals([
          'data' => 0,
          'success' => true
        ]);
        $this->assertEquals(200, $this->response->status());
    }

    public function testWikiCountOne()
    {
        // TODO should wikis be counted if they have no db?
        // TODO what actually is the use of this whole count??
        factory(Wiki::class, 'nodb')->create();
        $this->get($this->route)->seeJsonEquals([
          'data' => 1,
          'success' => true
        ]);
        $this->assertEquals(200, $this->response->status());
    }

    public function testWikiCountTwo()
    {
        factory(Wiki::class, 'nodb')->create();
        factory(Wiki::class, 'nodb')->create();
        $this->get($this->route)->seeJsonEquals([
          'data' => 2,
          'success' => true
        ]);
        $this->assertEquals(200, $this->response->status());
    }
}
