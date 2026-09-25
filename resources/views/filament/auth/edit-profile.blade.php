@php
    $user = $this->getUser();
@endphp

<x-filament-panels::page>
    <div class="tambora-profile">
        <section class="tambora-profile-hero" aria-label="Ringkasan profil admin">
            <div class="tambora-profile-hero__identity">
                <div class="tambora-profile-hero__avatar">
                    <x-filament-panels::avatar.user :user="$user" />
                    <span class="tambora-profile-hero__status" aria-label="Akun aktif"></span>
                </div>

                <div class="tambora-profile-hero__copy">
                    <span class="tambora-profile-hero__eyebrow">Akun admin TAMBORA</span>
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }}</p>
                </div>
            </div>

            <div class="tambora-profile-hero__role">
                <span>Hak akses</span>
                <strong>{{ $user->role->label() }}</strong>
            </div>
        </section>

        <div class="tambora-profile-form">
            {{ $this->content }}
        </div>
    </div>
</x-filament-panels::page>
