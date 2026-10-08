<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class NewStaffMessage extends MailNotification
{
    public function __construct(public string $reportCode) {}

    public function toMail(object $notifiable): MailMessage
    {
        return $this->mailMessage('Balasan baru dari petugas · '.$this->reportCode, 'Yth. Pelapor,')
            ->line('Petugas TAMBORA telah mengirim balasan baru untuk laporan '.$this->reportCode.'.')
            ->line('Silakan buka percakapan pada halaman pelacakan untuk membaca dan menanggapi pesan petugas.')
            ->action('Buka percakapan laporan', route('reports.track'))
            ->line('Masukkan nomor laporan '.$this->reportCode.' pada halaman pelacakan.')
            ->line('Email ini merupakan notifikasi otomatis (no-reply). Balasan melalui email ini tidak masuk ke percakapan aplikasi.');
    }
}
