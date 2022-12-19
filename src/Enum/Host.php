<?php

namespace Inpsyde\LogzIoMonolog\Enum;

enum Host: string
{
    case UsEast1 = 'listener.logz.io';
    case ApSoutheast1 = 'listener-au.logz.io';
    case CaCentral1 = 'listener-ca.logz.io';
    case EuCentral1 = 'listener-eu.logz.io';
    case WestEurope = 'listener-nl.logz.io';
    case EuWest2 = 'listener-uk.logz.io';
    case WestUs1 = 'listener-wa.logz.io';
}
