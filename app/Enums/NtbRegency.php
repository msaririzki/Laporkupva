<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NtbRegency: string implements HasLabel
{
    case KabupatenLombokBarat = 'Kabupaten Lombok Barat';
    case KabupatenLombokTengah = 'Kabupaten Lombok Tengah';
    case KabupatenLombokTimur = 'Kabupaten Lombok Timur';
    case KabupatenLombokUtara = 'Kabupaten Lombok Utara';
    case KabupatenSumbawa = 'Kabupaten Sumbawa';
    case KabupatenSumbawaBarat = 'Kabupaten Sumbawa Barat';
    case KabupatenDompu = 'Kabupaten Dompu';
    case KabupatenBima = 'Kabupaten Bima';
    case KotaMataram = 'Kota Mataram';
    case KotaBima = 'Kota Bima';

    public function getLabel(): string
    {
        return $this->value;
    }
}
