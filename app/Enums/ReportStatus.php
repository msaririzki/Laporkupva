<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReportStatus: string implements HasColor, HasLabel
{
    case Submitted = 'submitted';
    case Received = 'received';
    case Coordination = 'coordination';
    case FieldAction = 'field_action';
    case ResultReport = 'result_report';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Laporan dikirim',
            self::Received => 'Laporan diterima',
            self::Coordination => 'Koordinasi dengan APH',
            self::FieldAction => 'Kunjungan lapangan / penertiban',
            self::ResultReport => 'Laporan hasil',
            self::Completed => 'Selesai',
        };
    }

    public function getLabel(): ?string
    {
        return $this->label();
    }

    public function description(): string
    {
        return match ($this) {
            self::Submitted => 'Laporan berhasil tersimpan dan menunggu pemeriksaan awal.',
            self::Received => 'Laporan sudah diterima dan mulai diproses.',
            self::Coordination => 'Laporan sedang dikoordinasikan dengan aparat penegak hukum.',
            self::FieldAction => 'Kunjungan lapangan atau tindakan penertiban sedang dilakukan.',
            self::ResultReport => 'Ringkasan hasil penanganan sudah tersedia.',
            self::Completed => 'Seluruh proses penanganan laporan telah selesai.',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Submitted => 'info',
            self::Received => 'primary',
            self::Coordination => 'gray',
            self::FieldAction => 'warning',
            self::ResultReport => 'success',
            self::Completed => 'success',
        };
    }

    public function getColor(): string|array|null
    {
        return $this->color();
    }

    public function next(): ?self
    {
        return match ($this) {
            self::Submitted => self::Received,
            self::Received => self::Coordination,
            self::Coordination => self::FieldAction,
            self::FieldAction => self::ResultReport,
            self::ResultReport => self::Completed,
            self::Completed => null,
        };
    }
}
