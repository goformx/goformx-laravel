<?php

use Inertia\Testing\AssertableInertia as Assert;

test('returns form fill page for valid form id', function () {
    $response = $this->get('/forms/some-id');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Forms/Fill')
        ->where('formId', 'some-id')
    );
});
