<?php

namespace App\Enums;

enum PaymentMethods : string {
    case Bank = 'Bank Transfer';
    case GPay = 'Google Pay'; 
    case UPI = 'Other UPI'; 
    case CARD = 'Card'; 
    case CASH = 'Cash'; 
}