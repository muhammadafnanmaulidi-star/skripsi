<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Admin: List all members
     */
    public function index()
    {
        // Ambil SEMUA kustomer (User) kecuali yang memiliki role admin
        $users = \App\Models\User::where('role', '!=', 'admin')
            ->latest()
            ->get();

        // Ambil data member untuk pengecekan status
        $members = Member::all()->keyBy('phone');

        $usersWithStatus = $users->map(function($user) use ($members) {
            $memberData = $members->get($user->phone_number);
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'is_member' => $memberData ? $memberData->status === 'approved' : false,
                'member_status' => $memberData ? $memberData->status : null,
                'member_id' => $memberData ? $memberData->id : null,
                'points' => $memberData ? $memberData->points : 0,
                'registration_source' => $memberData ? $memberData->registration_source : null,
            ];
        });

        // Tetap ambil members asli untuk fungsionalitas modal edit dsb (jika dibutuhkan)
        $actualMembers = Member::latest()->get();

        return Inertia::render('Admin/AdminMembers', [
            'usersList' => $usersWithStatus,
            'members' => $actualMembers
        ]);
    }

    /**
     * Admin: Store new member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|regex:/^[^0-9]*$/',
            'phone' => 'required|string|regex:/^08[0-9]{8,11}$/|unique:members,phone',
            'points' => 'nullable|integer|min:0'
        ]);

        $validated['status'] = 'approved';
        $validated['registration_source'] = 'admin';
        $member = Member::create($validated);

        return response()->json([
            'success' => true,
            'member' => $member
        ]);
    }

    /**
     * Admin: Update member details or points
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'nullable|string|max:50|regex:/^[^0-9]*$/',
            'phone' => 'nullable|string|regex:/^08[0-9]{8,11}$/|unique:members,phone,' . $id,
            'points' => 'nullable|integer|min:0'
        ]);


        $member->update($validated);

        return response()->json([
            'success' => true,
            'member' => $member
        ]);
    }

    /**
     * Admin: Redeem free menu (9 points each)
     */
    public function redeem(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $request->validate([
            'count' => 'required|integer|min:1|max:100'
        ]);


        $deductPoints = $request->count * 9;

        if ($member->points < $deductPoints) {
            return response()->json([
                'success' => false,
                'message' => 'Poin tidak cukup untuk redeem.'
            ], 422);
        }

        $member->decrement('points', $deductPoints);

        return response()->json([
            'success' => true,
            'member' => $member
        ]);
    }

    /**
     * Admin: Delete member
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Admin: Approve Member Application
     */
    public function approve($id)
    {
        $member = Member::findOrFail($id);
        $member->update(['status' => 'approved']);

        return response()->json(['success' => true]);
    }

    /**
     * Admin: Reject Member Application
     */
    public function reject($id)
    {
        $member = Member::findOrFail($id);
        $member->delete(); // Or update status to 'rejected', but deleting allows re-apply

        return response()->json(['success' => true]);
    }

    /**
     * User: Apply for Membership
     */
    public function apply()
    {
        $user = auth()->user();

        if (empty($user->phone_number)) {
            return redirect()->back()->with('error', 'Silakan lengkapi Nomor Handphone di Profil terlebih dahulu sebelum mengajukan Member.');
        }

        $existing = Member::where('phone', $user->phone_number)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Nomor HP ini sudah terdaftar sebagai Member atau sedang menunggu persetujuan.');
        }

        Member::create([
            'name' => $user->name,
            'phone' => $user->phone_number,
            'points' => 0,
            'status' => 'pending',
            'registration_source' => 'user_request'
        ]);

        return redirect()->back()->with('success', 'Pengajuan member berhasil dikirim. Menunggu persetujuan admin.');
    }

    /**
     * Public/Public Search: Find member by phone
     */
    public function search(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^08[0-9]{8,11}$/'
        ]);

        $member = Member::where('phone', $request->phone)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan.'
            ], 44);
        }

        return response()->json([
            'success' => true,
            'member' => $member
        ]);
    }
}
