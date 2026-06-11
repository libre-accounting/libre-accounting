<?php

namespace Tests\Feature\Common;

use Tests\Feature\FeatureTestCase;

class HealthTest extends FeatureTestCase
{
    public function testItShouldReturnOkWithoutAuthentication()
    {
        $this->get(route('health'))
            ->assertOk()
            ->assertExactJson(['status' => 'ok']);
    }
}
