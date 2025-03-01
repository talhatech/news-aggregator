<?php

namespace App\Enums;

enum NewsSource: string
{
    case NEWSAPI = 'newsapi';
    case GUARDIAN = 'guardian';
    case NYTIMES = 'nytimes';

    
}
