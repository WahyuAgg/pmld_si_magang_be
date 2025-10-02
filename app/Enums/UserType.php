<?php

namespace App\Enums;

enum UserType: string
{
    case Admin = 'admin';
    case Mahasiswa = 'mahasiswa';
    case Supervisor = 'supervisor';
    case Dosbing = 'dosbing';
}
