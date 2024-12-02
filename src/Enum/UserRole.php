<?php

namespace App\Enum;



enum UserRole: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case COMMERCIAL = 'commercial';
}

