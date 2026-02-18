<?php

test('returns landing page', function () {
    $this->get('/')->assertOk();
});
