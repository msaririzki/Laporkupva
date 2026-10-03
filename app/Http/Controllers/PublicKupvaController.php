<?php

namespace App\Http\Controllers;

use App\Enums\NtbRegency;
use App\Models\Kupva;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicKupvaController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $search = $request->string('q')->trim()->limit(100)->toString();
        $selectedRegency = NtbRegency::tryFrom($request->string('regency')->toString())?->value;

        $query = Kupva::query()
            ->where('license_status', 'active')
            ->where('is_active', true)
            ->whereNotNull('license_number')
            ->where('license_number', '!=', '')
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('license_expires_at')
                    ->orWhereDate('license_expires_at', '>=', today());
            });

        if (filled($search)) {
            $query->where(function (Builder $query) use ($search): void {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
            });
        }

        if ($selectedRegency !== null) {
            $query->where('regency', $selectedRegency);
        }

        return view('pages.kupvas', [
            'kupvas' => $query->orderBy('name')->paginate(12)->withQueryString(),
            'regencies' => NtbRegency::cases(),
            'search' => $search,
            'selectedRegency' => $selectedRegency,
        ]);
    }
}
