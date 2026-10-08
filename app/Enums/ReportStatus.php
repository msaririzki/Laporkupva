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
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Laporan dikirim',
            self::Received => 'Laporan diterima',
            self::Coordination => 'Koordinasi dengan APH',
            self::FieldAction => 'Kunjungan lapangan / penertiban',
            self::Completed => 'Selesai',
        };
    }

    public function requiresApproval(): bool
    {
        return in_array($this, [self::Coordination, self::FieldAction, self::Completed], true);
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
            self::Completed => 'success',
        };
    }

    public function publicMessage(string $reportNumber): string
    {
        $message = match ($this) {
            self::Submitted => "Laporan Anda dengan nomor {$reportNumber} telah berhasil dikirim dan tercatat dalam sistem. Informasi serta bukti yang Anda sampaikan akan diperiksa oleh petugas untuk menentukan tindak lanjut yang sesuai. Silakan simpan nomor laporan ini dan pantau perkembangan penanganannya melalui halaman pelacakan laporan. Terima kasih atas partisipasi Anda dalam mendukung pengawasan kegiatan penukaran valuta asing.",
            self::Received => "Laporan Anda dengan nomor {$reportNumber} telah diterima oleh petugas dan memasuki tahap pemeriksaan awal. Pada tahap ini, petugas menelaah informasi lokasi, uraian kejadian, serta bukti pendukung yang Anda sampaikan untuk menentukan langkah penanganan berikutnya. Apabila diperlukan informasi tambahan, petugas akan menyampaikannya melalui fitur percakapan pada laporan ini. Silakan pantau halaman pelacakan untuk mengetahui perkembangan selanjutnya.",
            self::Coordination => "Penanganan laporan Anda dengan nomor {$reportNumber} telah memasuki tahap koordinasi dengan Aparat Penegak Hukum (APH). Pada tahap ini, petugas melakukan koordinasi dengan pihak terkait untuk menelaah informasi laporan dan menyelaraskan langkah tindak lanjut sesuai kewenangan masing-masing. Perkembangan yang dapat disampaikan kepada pelapor akan diperbarui melalui halaman pelacakan laporan. Terima kasih atas kesabaran Anda selama proses penanganan berlangsung.",
            self::FieldAction => "Laporan Anda dengan nomor {$reportNumber} telah memasuki tahap tindak lanjut lapangan. Pada tahap ini, petugas menindaklanjuti laporan melalui kunjungan ke lokasi untuk memeriksa kondisi yang dilaporkan dan/atau melakukan penertiban sesuai hasil pemeriksaan serta kewenangan yang berlaku. Informasi perkembangan kegiatan yang dapat disampaikan kepada pelapor akan dicantumkan pada halaman pelacakan laporan. Terima kasih atas dukungan Anda dalam proses pengawasan ini.",
            self::Completed => "Proses penanganan laporan Anda dengan nomor {$reportNumber} telah dinyatakan selesai oleh petugas. Tahapan tindak lanjut telah ditutup, dan informasi penanganan yang dapat disampaikan kepada pelapor dapat Anda lihat pada riwayat perkembangan laporan ini. Apabila Anda memerlukan penjelasan mengenai pembaruan yang disampaikan, silakan gunakan fitur percakapan pada laporan ini. Terima kasih atas kepedulian dan partisipasi Anda dalam mendukung pengawasan kegiatan penukaran valuta asing.",
        };

        return "Yth. Pelapor,\n\n{$message}";
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
            self::FieldAction => self::Completed,
            self::Completed => null,
        };
    }
}
