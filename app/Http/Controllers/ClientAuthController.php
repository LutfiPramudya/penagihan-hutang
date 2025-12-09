<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.client_register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'phone'                 => ['nullable', 'string', 'max:50', 'required_without:email', Rule::unique('customers', 'phone')],
            'email'                 => ['nullable', 'email', 'max:255', 'required_without:phone', Rule::unique('customers', 'email')],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'] ?? null,
            'phone'    => $data['phone'] ?? null,
            'role'     => 'customer',
            'password' => Hash::make($data['password']),
        ]);

        $customer = Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name'     => $user->name,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'alamat_ktp'       => 'Belum diisi',
                'nik'              => $this->fallbackNik($data),
                'tempat_lahir'     => 'Belum diisi',
                'tanggal_lahir'    => now()->toDateString(),
                'jenis_kelamin'    => 'Laki-laki',
                'golongan_darah'   => 'O',
                'rt'               => '00',
                'rw'               => '00',
                'kelurahan'        => 'Belum diisi',
                'kecamatan'        => 'Belum diisi',
                'agama'            => 'Islam',
                'status_perkawinan'=> 'Belum Menikah',
                'pekerjaan'        => 'Belum Diisi',
                'kewarganegaraan'  => 'WNI',
                'masa_berlaku'     => 'Seumur Hidup',
                'foto_ktp'         => 'default.jpg',
                'pendidikan_terakhir' => 'Belum Diisi',
                'gaji_per_bulan'      => 0,
                'nama_bank'           => '-',
                'pemilik_rekening'    => '-',
                'nomor_rekening'      => '-',
                'status_verifikasi'   => 'menunggu',
                'limit_pinjaman'      => 0,
            ]
        );

        Auth::guard('web')->login($user);

        return redirect()->route('client.profile')->with('success', 'Registrasi berhasil. Lengkapi data diri Anda.');
    }

    public function showLogin()
    {
        return view('auth.client_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = str_contains($credentials['login'], '@') ? 'email' : 'phone';

        $client = Customer::where($loginField, $credentials['login'])->first();

        if ($client && $client->password && Hash::check($credentials['password'], $client->password)) {
            Auth::guard('client')->login($client, $request->boolean('remember'));
            $request->session()->regenerate();
            $request->session()->put('client_customer_id', $client->id);
            return redirect()->intended(route('client.loans.index'));
        }

        return back()->withErrors([
            'login' => 'Kredensial tidak cocok.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->forget('client_customer_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login')->with('status', 'Anda sudah logout.');
    }

    private function fallbackNik(array $data): string
    {
        // generate NIK dummy jika belum ada di form registrasi
        $base = preg_replace('/\D/', '', ($data['phone'] ?? '0000000000'));
        return substr(str_pad($base, 16, '0'), 0, 16);
    }
}
