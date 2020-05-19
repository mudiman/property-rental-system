<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
  'smoor' => [
    'website' => env('SMOOR_WEBSITE', 'smoor.com'),
    'name' => env('SMOOR_NAME', 'Smoor'),
    'address' => env('SMOOR_ADDRESS', 'Smoor<br/>61 North Woolwich Road<br/>London<br/>E16 2AA<br/>'),
  ],
  'admin' => [
    'id' => '1',
    'access_token' => env('SMOOR_ADMIN_ACCESS_TOKEN')
  ],
  'payment' => [
    'smoor_commission' => [
      'gbp' => 3/100
    ],
    'smoor_property_pro_commission' => [
      'gbp' => 10/100
    ],
    'smoor_agency_commission' => [
      'gbp' => 0
    ],
    'payment_gateway_commission' => [
      'gbp' => [
        'percent' => 1.4/100,
        'offset' => 0.2,
        ]
    ],
    'propertypro_commission' => 2,
    'minimum_amount' => 0.3,
  ],
  'tenancy' => [
    'offer_accept_expiry' => env('SMOOR_OFFER_EXPIRY', 24), //hours
    'holding_deposit_expiry' => env('SMOOR_OFFER_HOLDING_DEPOSIT_EXPIRY', 24), //hours
    
    'sign_expiry' => env('SMOOR_TENANCY_SIGN_EXPIRY', 24), //hours
    
    'max_short_duration_days' => env('SMOOR_TENANCY_MAX_SHORT_DURATION_DAYS', 7),
    'max_mid_duration_days' => env('SMOOR_TENANCY_MAX_MID_DURATION_DAYS', 31),
    
    'tds_reminder' => env('SMOOR_TDS_REMINDER', 3),
    
    'checkin_reminder' => env('SMOOR_TENANT_CHECKIN_REMINDER_DAYS_AFTER_CHECKIN_DATE', 7),
    
    'prenotice' => [
      'start' => env('SMOOR_TENANCY_PRE_NOTICE_PERIOD_START', 62),
      'end' => env('SMOOR_TENANCY_PRE_NOTICE_PERIOD_END', 70),
    ],
    
    'payout' => [
      'update_reminder' => env('SMOOR_TENANCY_PAYOUT_UPDATE_REMINDER', 8),
    ],
    
    'first_rent_and_security' => [
      'start' => env('SMOOR_TENANCY_FIRST_RENT_AND_SECURITY_PERIOD_START', 30),
      'next_notification' => env('SMOOR_TENANCY_FIRST_RENT_AND_SECURITY_PERIOD_NEXT_HOURS', 48),
      'cancel' => env('SMOOR_TENANCY_FIRST_RENT_AND_SECURITY_PERIOD_CANCEL_HOURS', 72),
    ],
    
    'notice' => [
      'start' => env('SMOOR_TENANCY_NOTICE_PERIOD_START', 31),
      'end' => env('SMOOR_TENANCY_NOTICE_PERIOD_END', 31),
      'duration' => env('SMOOR_TENANCY_NOTICE_DURATION', 0),
    ],
    
    'rolling' => [
      'notice_duration' => env('SMOOR_TENANCY_ROLLING_NOTICE_DURATION', 0),
    ]
  ],
  'social' => [
    'twitter' => env('SMOOR_SOCIAL_TWITTER', 'http://twitter.com/smoorapp '),
    'plus_google' => env('SMOOR_SOCIAL_GOOGLE_PLUS', 'http://plus.google.com/smoorapp '),
    'facebook' => env('SMOOR_SOCIAL_FACEBOOK', 'http://facebook.com/smoorapp '),
    'instagram' => env('SMOOR_SOCIAL_INSTAGRAM', 'http://instagram.com/smoorapp '),
  ],
  'registration' => [
    'expiry' => env('SMOOR_REGISTRATION_EXPIRY', 7)
  ],
  'scoring' => [
    'max_score' => 10,
    'min_score' => 0,
    'start_score' => 5,
    'streak_impact' => 0.1,
    'review_max' => 5,
    'default_type' => [
      'offer' => 10,
      'tenancy' => 4,
      'tenancy_cancel' => 12,
      'tenancy_bind' => 12,
      'viewing' => 1,
      'viewing_conduct' => 2,
      'property_pro_conduct' => 2,
      'transaction' => 15,
      'user' => 25,
      'responsiveness' => 9,
    ]
  ],
];