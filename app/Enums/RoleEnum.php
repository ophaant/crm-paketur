<?php

namespace App\Enums;

enum RoleEnum:string
{
    case SUPER_ADMIN = 'super admin';
    case MANAGER = 'manager';
    case EMPLOYEE = 'employee';
}
