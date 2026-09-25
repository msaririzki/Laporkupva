<?php

namespace App\Enums;

enum NtbDemoLocation: string
{
    case MataramCakranegara = 'mataram_cakranegara';
    case MataramPejanggik = 'mataram_pejanggik';
    case MataramAmpenan = 'mataram_ampenan';
    case LombokBaratSenggigi = 'lombok_barat_senggigi';
    case LombokBaratGerung = 'lombok_barat_gerung';
    case LombokTengahPraya = 'lombok_tengah_praya';
    case LombokTengahKuta = 'lombok_tengah_kuta';
    case LombokTimurSelong = 'lombok_timur_selong';
    case LombokTimurMasbagik = 'lombok_timur_masbagik';
    case LombokUtaraTanjung = 'lombok_utara_tanjung';
    case SumbawaBesar = 'sumbawa_besar';
    case SumbawaAlas = 'sumbawa_alas';
    case SumbawaBaratTaliwang = 'sumbawa_barat_taliwang';
    case DompuKota = 'dompu_kota';
    case BimaKota = 'bima_kota';
    case BimaRaba = 'bima_raba';
    case KabupatenBimaWoha = 'kabupaten_bima_woha';

    public function areaName(): string
    {
        return match ($this) {
            self::MataramCakranegara => 'Cakranegara',
            self::MataramPejanggik => 'Pejanggik',
            self::MataramAmpenan => 'Ampenan',
            self::LombokBaratSenggigi => 'Senggigi',
            self::LombokBaratGerung => 'Gerung',
            self::LombokTengahPraya => 'Praya',
            self::LombokTengahKuta => 'Kuta Mandalika',
            self::LombokTimurSelong => 'Selong',
            self::LombokTimurMasbagik => 'Masbagik',
            self::LombokUtaraTanjung => 'Tanjung',
            self::SumbawaBesar => 'Sumbawa Besar',
            self::SumbawaAlas => 'Alas',
            self::SumbawaBaratTaliwang => 'Taliwang',
            self::DompuKota => 'Dompu',
            self::BimaKota => 'Kota Bima',
            self::BimaRaba => 'Raba',
            self::KabupatenBimaWoha => 'Woha',
        };
    }

    /**
     * @return array{
     *     regency: string,
     *     district: string,
     *     village: string,
     *     address: string,
     *     latitude: float,
     *     longitude: float
     * }
     */
    public function attributes(): array
    {
        return match ($this) {
            self::MataramCakranegara => ['regency' => NtbRegency::KotaMataram->value, 'district' => 'Cakranegara', 'village' => 'Cilinaya', 'address' => 'Area pertokoan Cakranegara, Kota Mataram (data demo)', 'latitude' => -8.5901, 'longitude' => 116.1322],
            self::MataramPejanggik => ['regency' => NtbRegency::KotaMataram->value, 'district' => 'Mataram', 'village' => 'Pejanggik', 'address' => 'Koridor komersial Pejanggik, Kota Mataram (data demo)', 'latitude' => -8.5834, 'longitude' => 116.1176],
            self::MataramAmpenan => ['regency' => NtbRegency::KotaMataram->value, 'district' => 'Ampenan', 'village' => 'Ampenan Tengah', 'address' => 'Area perdagangan Ampenan Tengah, Kota Mataram (data demo)', 'latitude' => -8.5667, 'longitude' => 116.0778],
            self::LombokBaratSenggigi => ['regency' => NtbRegency::KabupatenLombokBarat->value, 'district' => 'Batu Layar', 'village' => 'Senggigi', 'address' => 'Kawasan usaha Senggigi, Batu Layar (data demo)', 'latitude' => -8.4948, 'longitude' => 116.0475],
            self::LombokBaratGerung => ['regency' => NtbRegency::KabupatenLombokBarat->value, 'district' => 'Gerung', 'village' => 'Gerung Selatan', 'address' => 'Area pusat perdagangan Gerung Selatan (data demo)', 'latitude' => -8.6830, 'longitude' => 116.1252],
            self::LombokTengahPraya => ['regency' => NtbRegency::KabupatenLombokTengah->value, 'district' => 'Praya', 'village' => 'Praya', 'address' => 'Area pertokoan pusat Kota Praya (data demo)', 'latitude' => -8.7053, 'longitude' => 116.2702],
            self::LombokTengahKuta => ['regency' => NtbRegency::KabupatenLombokTengah->value, 'district' => 'Pujut', 'village' => 'Kuta', 'address' => 'Kawasan usaha Kuta Mandalika, Pujut (data demo)', 'latitude' => -8.8945, 'longitude' => 116.2836],
            self::LombokTimurSelong => ['regency' => NtbRegency::KabupatenLombokTimur->value, 'district' => 'Selong', 'village' => 'Selong', 'address' => 'Area perdagangan pusat Kota Selong (data demo)', 'latitude' => -8.6507, 'longitude' => 116.5319],
            self::LombokTimurMasbagik => ['regency' => NtbRegency::KabupatenLombokTimur->value, 'district' => 'Masbagik', 'village' => 'Masbagik Utara', 'address' => 'Area pertokoan Masbagik Utara (data demo)', 'latitude' => -8.6211, 'longitude' => 116.4771],
            self::LombokUtaraTanjung => ['regency' => NtbRegency::KabupatenLombokUtara->value, 'district' => 'Tanjung', 'village' => 'Tanjung', 'address' => 'Area pusat usaha Kecamatan Tanjung (data demo)', 'latitude' => -8.3562, 'longitude' => 116.1564],
            self::SumbawaBesar => ['regency' => NtbRegency::KabupatenSumbawa->value, 'district' => 'Sumbawa', 'village' => 'Seketeng', 'address' => 'Area pertokoan Seketeng, Sumbawa Besar (data demo)', 'latitude' => -8.4931, 'longitude' => 117.4202],
            self::SumbawaAlas => ['regency' => NtbRegency::KabupatenSumbawa->value, 'district' => 'Alas', 'village' => 'Dalam', 'address' => 'Area perdagangan Kecamatan Alas (data demo)', 'latitude' => -8.5148, 'longitude' => 117.0618],
            self::SumbawaBaratTaliwang => ['regency' => NtbRegency::KabupatenSumbawaBarat->value, 'district' => 'Taliwang', 'village' => 'Kuang', 'address' => 'Area pusat perdagangan Taliwang (data demo)', 'latitude' => -8.7449, 'longitude' => 116.8532],
            self::DompuKota => ['regency' => NtbRegency::KabupatenDompu->value, 'district' => 'Dompu', 'village' => 'Bada', 'address' => 'Area pertokoan pusat Kota Dompu (data demo)', 'latitude' => -8.5364, 'longitude' => 118.4634],
            self::BimaKota => ['regency' => NtbRegency::KotaBima->value, 'district' => 'Rasanae Barat', 'village' => 'Paruga', 'address' => 'Area perdagangan Paruga, Kota Bima (data demo)', 'latitude' => -8.4606, 'longitude' => 118.7267],
            self::BimaRaba => ['regency' => NtbRegency::KotaBima->value, 'district' => 'Raba', 'village' => 'Rabangodu Selatan', 'address' => 'Area pertokoan Raba, Kota Bima (data demo)', 'latitude' => -8.4578, 'longitude' => 118.7577],
            self::KabupatenBimaWoha => ['regency' => NtbRegency::KabupatenBima->value, 'district' => 'Woha', 'village' => 'Tente', 'address' => 'Area sekitar Pasar Tente, Jalan Buya Hamka (data demo)', 'latitude' => -8.5857, 'longitude' => 118.6967],
        };
    }
}
