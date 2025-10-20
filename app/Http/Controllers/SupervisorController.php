<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SupervisorController extends Controller
{
       /**
     * Tampilkan daftar supervisor
     */
    public function index(Request $request)
    {
        $query = Supervisor::with(['mitra', 'user']);

        // Filter berdasarkan mitra_id
        if ($mitraId = $request->query('mitra_id')) {
            $query->where('mitra_id', $mitraId);
        }

        $perPage = (int) $request->query('per_page', 15);
        $data = $query->latest('supervisor_id')->orderByDesc('supervisor_id')->paginate($perPage);

        return response()->json($data, 200);
    }

    /**
     * Tampilkan detail supervisor
     */
    public function show($id)
    {
        $supervisor = Supervisor::with(['mitra', 'user'])->findOrFail($id);
        return response()->json($supervisor, 200);
    }

    /**
     * Tambah supervisor baru
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // 🧩 1️⃣ Validasi data user
            $validatedUser = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed', // password_confirmation wajib dikirim
                'role' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        if ($value !== 'supervisor') {
                            $fail('Role harus "supervisor".');
                        }
                    }
                ],
                'mitra_id' => ['required', 'exists:mitra,mitra_id'],
                'nama_supervisor' => ['required', 'string', 'max:100'],
                'jabatan' => ['nullable', 'string', 'max:100'],
                'no_hp' => ['nullable', 'string', 'max:20'],
            ]);

            // 🧩 2️⃣ Buat user baru
            $user = User::create([
                'name' => $validatedUser['name'],
                'username' => $validatedUser['username'],
                'email' => $validatedUser['email'],
                'password' => bcrypt($validatedUser['password']),
                'role' => $validatedUser['role'],
            ]);

            // 🧩 3️⃣ Buat token untuk user
            $token = $user->createToken('api-token')->plainTextToken;
            $token_expired = Carbon::now()->addHours(2);
            $user->tokens->last()->update(['expires_at' => $token_expired]);

            // 🧩 4️⃣ Buat supervisor
            $supervisor = Supervisor::create([
                'user_id' => $user->user_id,
                'mitra_id' => $validatedUser['mitra_id'],
                'nama_supervisor' => $validatedUser['nama_supervisor'],
                'jabatan' => $validatedUser['jabatan'] ?? null,
                'email' => $validatedUser['email'], // sama dengan user email
                'no_hp' => $validatedUser['no_hp'] ?? null,
            ]);

            DB::commit();

            // 🧩 5️⃣ Response sukses
            return response()->json([
                'message' => 'Supervisor berhasil didaftarkan',
                'user' => [
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'supervisor' => $supervisor->load(['mitra', 'user']),
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => $token_expired,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update supervisor
     */
    public function update(Request $request, $id)
    {
        $supervisor = Supervisor::with('user')->findOrFail($id);

        DB::beginTransaction();

        try {
            // 🧩 1️⃣ Validasi data input
            $rules = [
                'name' => ['nullable', 'string', 'max:255'],
                'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $supervisor->user_id . ',user_id'],
                'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $supervisor->user_id . ',user_id'],
                'password' => ['nullable', 'string', 'min:6', 'confirmed'],
                'role' => [
                    'nullable',
                    'string',
                    function ($attribute, $value, $fail) {
                        if ($value && $value !== 'supervisor') {
                            $fail('Role harus "supervisor".');
                        }
                    }
                ],
                'mitra_id' => ['nullable', 'exists:mitra,mitra_id'],
                'nama_supervisor' => ['nullable', 'string', 'max:100'],
                'jabatan' => ['nullable', 'string', 'max:100'],
                'no_hp' => ['nullable', 'string', 'max:20'],
            ];

            $validated = Validator::make($request->all(), $rules);
            if ($validated->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validated->errors(),
                ], 422);
            }

            $data = $validated->validated();

            // 🧩 2️⃣ Update data user
            $user = $supervisor->user;

            $userUpdateData = [];
            if (isset($data['name']))
                $userUpdateData['name'] = $data['name'];
            if (isset($data['username']))
                $userUpdateData['username'] = $data['username'];
            if (isset($data['email']))
                $userUpdateData['email'] = $data['email'];
            if (isset($data['password']))
                $userUpdateData['password'] = bcrypt($data['password']);
            if (isset($data['role']))
                $userUpdateData['role'] = $data['role'];

            if (!empty($userUpdateData)) {
                $user->update($userUpdateData);
            }

            // 🧩 3️⃣ Update data supervisor
            $supervisorUpdateData = [];
            if (isset($data['mitra_id']))
                $supervisorUpdateData['mitra_id'] = $data['mitra_id'];
            if (isset($data['nama_supervisor']))
                $supervisorUpdateData['nama_supervisor'] = $data['nama_supervisor'];
            if (isset($data['jabatan']))
                $supervisorUpdateData['jabatan'] = $data['jabatan'];
            if (isset($data['no_hp']))
                $supervisorUpdateData['no_hp'] = $data['no_hp'];
            if (isset($data['email']))
                $supervisorUpdateData['email'] = $data['email']; // sync email

            if (!empty($supervisorUpdateData)) {
                $supervisor->update($supervisorUpdateData);
            }

            DB::commit();

            // 🧩 4️⃣ Response sukses
            return response()->json([
                'message' => 'Supervisor berhasil diperbarui',
                'data' => $supervisor->fresh()->load(['mitra', 'user']),
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui data supervisor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Hapus supervisor
     */
    public function destroy($id)
    {
        $supervisor = Supervisor::findOrFail($id);
        $supervisor->delete();

        return response()->json([
            'message' => 'Supervisor berhasil dihapus',
        ], 200);
    }
}
