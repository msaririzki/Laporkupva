<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class ReportSubmitted extends MailNotification
{
    public function __construct(public string $reportCode) {}

    public function toMail(object $notifiable): MailMessage
    {
        return $this->mailMessage('Laporan berhasil dikirim · '.$this->reportCode, 'Yth. Pelapor,')
            ->line('Laporan Anda dengan nomor '.$this->reportCode.' telah berhasil dikirim dan tercatat dalam sistem.')
            ->line('Petugas akan memeriksa informasi dan bukti yang Anda sampaikan. Simpan nomor laporan untuk memantau perkembangannya.')
            ->action('Lacak laporan', route('reports.track'))
            ->line('Masukkan nomor laporan '.$this->reportCode.' pada halaman pelacakan.')
            ->line('Email ini merupakan notifikasi otomatis (no-reply). Untuk menghubungi petugas, gunakan percakapan pada laporan Anda.');
    }
}
