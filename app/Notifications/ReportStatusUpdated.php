<?php

namespace App\Notifications;

use App\Enums\ReportStatus;
use Illuminate\Notifications\Messages\MailMessage;

class ReportStatusUpdated extends MailNotification
{
    public function __construct(public string $reportCode, public ReportStatus $status) {}

    public function toMail(object $notifiable): MailMessage
    {
        return $this->mailMessage('Pembaruan laporan · '.$this->reportCode, 'Yth. Pelapor,')
            ->line('Informasi penanganan laporan '.$this->reportCode.' telah diperbarui.')
            ->line('Tahap penanganan: '.$this->status->label().'.')
            ->action('Lihat perkembangan laporan', route('reports.track'))
            ->line('Masukkan nomor laporan '.$this->reportCode.' untuk membaca pembaruan dan riwayat penanganan.')
            ->line('Email ini merupakan notifikasi otomatis (no-reply). Mohon tidak membalas email ini; gunakan percakapan pada laporan Anda.');
    }
}
