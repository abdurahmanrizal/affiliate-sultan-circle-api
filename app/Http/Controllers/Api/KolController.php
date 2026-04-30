<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KolController extends Controller
{
    public function index(Request $request)
    {
        $query = Kol::with(['city', 'jamaahs', 'referrals'])
            ->withCount(['jamaahs', 'referrals'])
            ->withCount(['jamaahs as jamaahs_booking_count' => fn ($q) => $q->where('status', 'booking')])
            ->withCount(['jamaahs as jamaahs_registered_count' => fn ($q) => $q->where('status', 'paid')]);

        // Search by name, email, or referral_code
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('referral_code', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by city_id
        if ($request->has('city_id') && $request->city_id) {
            $query->where('city_id', $request->city_id);
        }

        // Filter by minimum jamaahs count
        // if ($request->has('min_jamaahs') && $request->min_jamaahs) {
        //     $query->having('jamaahs_count', '>=', $request->min_jamaahs);
        // }

        // Filter by minimum total_click
        // if ($request->has('min_clicks') && $request->min_clicks) {
        //     $query->where('total_click', '>=', $request->min_clicks);
        // }

        // Filter by departure_schedule_id (from jamaahs table)
        if ($request->has('departure_schedule_id') && $request->departure_schedule_id) {
            $query->whereHas('jamaahs', function ($q) use ($request) {
                $q->where('departure_schedule_id', $request->departure_schedule_id);
            });
        }

        // Filter by status (from jamaahs table)
        if ($request->has('status') && $request->status) {
            $query->whereHas('jamaahs', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'referral_code');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', $request->has('page_size') ? $request->page_size : 10);
        $page = $request->get('page', $request->has('page') ? $request->page : 1);

        $kols = $query->paginate($perPage, ['*'], 'page', $page);

        // Get total counts using raw query for performance
        $counts = \DB::selectOne("
            SELECT
                COALESCE(SUM(referral_counts.cnt), 0) as referrals_count,
                COALESCE(SUM(unique_referral_counts.cnt), 0) as referrals_unique_count,
                COALESCE(SUM(booking_counts.cnt), 0) as jamaahs_booking_count,
                COALESCE(SUM(registered_counts.cnt), 0) as jamaahs_registered_count
            FROM kols
            LEFT JOIN (
                SELECT kol_id, COUNT(*) as cnt FROM referrals GROUP BY kol_id
            ) referral_counts ON referral_counts.kol_id = kols.id
            LEFT JOIN (
                SELECT kol_id, COUNT(*) as cnt FROM referrals WHERE status = 'clicked' AND is_unique = 1 GROUP BY kol_id
            ) unique_referral_counts ON unique_referral_counts.kol_id = kols.id
            LEFT JOIN (
                SELECT kol_id, COUNT(*) as cnt FROM jamaahs WHERE status = 'booking' GROUP BY kol_id
            ) booking_counts ON booking_counts.kol_id = kols.id
            LEFT JOIN (
                SELECT kol_id, COUNT(*) as cnt FROM jamaahs WHERE status = 'paid' GROUP BY kol_id
            ) registered_counts ON registered_counts.kol_id = kols.id
        ");

        return response()->json([
            'data' => $kols->items(),
            'total' => $kols->total(),
            'page' => $kols->currentPage(),
            'total_pages' => $kols->lastPage(),
            'page_size' => $kols->perPage(),
            'referrals_count' => $counts->referrals_count ?? 0,
            'referrals_unique_count' => $counts->referrals_unique_count ?? 0,
            'jamaahs_booking_count' => $counts->jamaahs_booking_count ?? 0,
            'jamaahs_registered_count' => $counts->jamaahs_registered_count ?? 0,
        ]);
    }

    public function showByReferralCode($referralCode)
    {
        $kol = Kol::with(['city', 'jamaahs.departureSchedule', 'referrals'])
            // ->withCount(['jamaahs', 'referrals'])
            ->withCount(['jamaahs as jamaahs_booking_count' => fn ($q) => $q->where('status', 'booking')])
            ->withCount(['jamaahs as jamaahs_registered_count' => fn ($q) => $q->where('status', 'paid')])
            ->withCount(['referrals as referrals_count' => fn ($q) => $q->where('status', 'clicked')->where('is_unique', 1)])
            ->where('referral_code', $referralCode)
            ->first();

        if (! $kol) {
            return response()->json([
                'message' => 'KOL not found for the given referral code',
                'referral_code' => $referralCode,
            ], 404);
        }
        $kol->setAttribute('total_click_per_departure_schedule', DB::table('referrals')
            ->where('kol_id', $kol->id)
            ->where('status', 'clicked')
            ->where('is_unique', 1)
            ->groupBy('departure_schedule_id')
            ->selectRaw('departure_schedule_id, COUNT(*) as count')
            ->get());

        $kol->setAttribute('total_booking_per_departure_schedule', DB::table('jamaahs')
            ->where('kol_id', $kol->id)
            ->where('status', 'booking')
            ->groupBy('departure_schedule_id')
            ->selectRaw('departure_schedule_id, COUNT(*) as count')
            ->get());

        $kol->setAttribute('total_registered_per_departure_schedule', DB::table('jamaahs')
            ->where('kol_id', $kol->id)
            ->where('status', 'paid')
            ->groupBy('departure_schedule_id')
            ->selectRaw('departure_schedule_id, COUNT(*) as count')
            ->get());

        return response()->json($kol);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'number_whatsapp' => 'nullable|string|max:50',
            'tiktok_instagram_account' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'referral_code' => 'nullable|string|max:255|unique:kols,referral_code',
        ]);

        $referralCode = $validated['referral_code'] ?? $this->generateUniqueReferralCode();

        $kol = Kol::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'number_whatsapp' => $validated['number_whatsapp'] ?? null,
            'tiktok_instagram_account' => $validated['tiktok_instagram_account'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'referral_code' => $referralCode,
            'total_click' => 0,
            'total_register' => 0,
        ]);

        return response()->json($kol->load('city'), 201);
    }

    private function generateUniqueReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Kol::where('referral_code', $code)->exists());

        return $code;
    }
}
