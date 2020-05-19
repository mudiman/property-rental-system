<?php

return [
  'stripe' => [
    'environment' => env('STRIPE_ENV'),
    'publish_key' => env('STRIPE_KEY', 'pk_test_kcI4jeOIGLjgrxEZlmClNsyz'),
    'secret_key' => env('STRIPE_SECRET', 'sk_test_FbaaxgT1caP5F21Hc5rm368P'),
  ],
];