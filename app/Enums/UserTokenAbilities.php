<?php

namespace App\Enums;

enum UserTokenAbilities:string
{
    case READ = "user:read";
    case WRITE = "user:write";
    case UPDATE = "user:update";
    case DELETE = "user:delete";
    case ALL = "user:*";
}
