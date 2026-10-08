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
        $displayMode = $request->string('view')->toString() === 'cards' ? 'cards' : 'map';

        $query = Kupva::query()
            ->where('license_status', 'active')
            ->where('is_active', true)
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('license_expires_at')
                    ->orWhereDate('license_expires_at', '>=', today());
            });

        if (filled($search)) {
            $query->where(function (Builder $query) use ($search): void {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%")
                    ->orWhere('village', 'like', "%{$search}%")
                    ->orWhere('regency', 'like', "%{$search}%");
            });
        }

        if ($selectedRegency !== null) {
            $query->where('regency', $selectedRegency);
        }

        $query->orderBy('name')->orderBy('id');

        $mapKupvas = $displayMode === 'map'
            ? (clone $query)->get(['id', 'name', 'address', 'village', 'district', 'regency', 'latitude', 'longitude', 'location_source'])
                ->map(fn (Kupva $kupva): array => [
                    'id' => $kupva->id,
                    'name' => $kupva->name,
                    'address' => $kupva->address ?: collect([$kupva->village, $kupva->district, $kupva->regency])->filter()->join(', '),
                    'latitude' => $kupva->latitude === null ? null : (float) $kupva->latitude,
                    'longitude' => $kupva->longitude === null ? null : (float) $kupva->longitude,
                    'approximate' => in_array($kupva->location_source, ['nominatim', 'nominatim_area'], true),
                    'area' => $kupva->location_source === 'nominatim_area',
                ])
            : collect();

        return view('pages.kupvas', [
            'kupvas' => (clone $query)->paginate(12)->withQueryString(),
            'mapKupvas' => $mapKupvas,
            'displayMode' => $displayMode,
            'regencies' => NtbRegency::cases(),
            'search' => $search,
            'selectedRegency' => $selectedRegency,
        ]);
    }
}
