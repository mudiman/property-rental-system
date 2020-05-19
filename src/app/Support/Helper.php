<?php

namespace App\Support;

use Carbon\Carbon;

class Helper
{
    const FLOAT_REGEX = 'regex:/^\d*(\.\d{1,2})?$/';
    
    public function __construct()
    {
    }
    
    public static function paymentGatewayCommission($amount, $currency = 'gbp')
    {
      return ($amount * config("business.payment.payment_gateway_commission.$currency.percent") ) + config("business.payment.payment_gateway_commission.$currency.offset");
    }
    
    public static function smoorCommission($amount, $currency = 'gbp')
    {
      return ($amount * config("business.payment.smoor_commission.$currency"));
    }
    
    public static function smoorPropertyProCommission($amount, $currency = 'gbp')
    {
      return ($amount * config("business.payment.smoor_commission.$currency"));
    }
    
    public static function smoorAgencyCommission($amount, $currency = 'gbp')
    {
      return ($amount * config("business.payment.smoor_commission.$currency"));
    }
    
    public static function smoorCommissionAndRemaining($amount, $currency = 'gbp')
    {
      $smoor = Helper::smoorCommission($amount, $currency);
      return [$smoor, $amount - $smoor];
    }
    
    public static function calculateFactorTime(Carbon $originalDateTime, Carbon $actionDateTime)
    {
      $late = $originalDateTime->diffInHours($actionDateTime);
      if ($late < 0) {
        $type = 0;
        switch ($late) {
          case ($late >= -5):
            $factor = 0.2;
            break;
          case ($late >= -10 && $late < -5):
            $factor = 0.5;
            break;
          case ($late < -10):
            $factor = 1;
            break;
        }
      } else {
        $type = 1;
        switch ($late) {
          case ($late <= 5):
            $factor = 1;
            break;
          case ($late > 5 && $late <= 10):
            $factor = 0.5;
            break;
          case ($late > 10):
            $factor = 0.2;
            break;
        }
      }
      return [$type,$factor];
    }
    
    public static function calculateFactorDays(Carbon $originalDate, Carbon $actionDate)
    {
      $late = $originalDate->diffInDays($actionDate);
      if ($late < 0) {
        $factor = 1;
        $type = 1;
      } else {
        $type = 0;
        switch ($late) {
          case ($late <= 5):
            $factor = 0.2;
            break;
          case ($late > 5 && $late <= 10):
            $factor = 0.5;
            break;
          case ($late > 10):
            $factor = 1;
            break;
        }
      }
      return [$type,$factor];
    }
    
    public static function adjustDateTime(Carbon $datetime, $operation, $offset) {
      if ($operation == 'add') {
        if (in_array(\App::environment(),['local', 'dev'])) {
          return $datetime->addMinute($offset);
        }
        return $datetime->addDays($offset);
      }
      if ($operation == 'subtract') {
        if (in_array(\App::environment(),['local', 'dev'])) {
          return $datetime->subMinutes($offset);
        }
        return $datetime->subDays($offset);
      }
    }
    
    public static function costFormater($cost) {
      return number_format((float)round($cost, 2, PHP_ROUND_HALF_DOWN), 2, '.', '');
    }
    
    public static function temporaryFile($name, $content)
    {
        $file = DIRECTORY_SEPARATOR .
                trim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) .
                DIRECTORY_SEPARATOR .
                ltrim($name, DIRECTORY_SEPARATOR);

        file_put_contents($file, $content);

        register_shutdown_function(function() use($file) {
            unlink($file);
        });

        return $file;
    }
}