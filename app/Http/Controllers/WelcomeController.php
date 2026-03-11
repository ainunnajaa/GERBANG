<?php

namespace App\Http\Controllers;

use App\Models\SchoolBackground;
use App\Models\SchoolContent;
use App\Models\SchoolProgram;
use App\Models\SchoolProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WelcomeController extends Controller
{
	public function index()
	{
		$profile = SchoolProfile::first();
		$guruCount = User::where('role', 'guru')->count();
		$gurus = User::where('role', 'guru')->orderBy('name')->get();
		$programs = $profile
			? SchoolProgram::where('school_profile_id', $profile->id)->orderBy('order')->get()
			: collect();
		$contents = $profile
			? SchoolContent::where('school_profile_id', $profile->id)->orderBy('order')->get()
			: collect();
		$backgrounds = $profile
			? SchoolBackground::where('school_profile_id', $profile->id)->orderBy('order')->get()
			: collect();

		return view('welcome', [
			'schoolProfile' => $profile,
			'guruCount' => $guruCount,
			'gurus' => $gurus,
			'programs' => $programs,
			'contents' => $contents,
			'backgrounds' => $backgrounds,
		]);
	}

	public function contact(Request $request)
	{
		$data = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'max:255'],
			'phone' => ['nullable', 'string', 'max:50'],
			'message' => ['required', 'string'],
		]);

		$profile = SchoolProfile::first();
		$toEmail = 'agita.maulana23@gmail.com';

		try {
			Mail::send('emails.contact', ['data' => $data, 'profile' => $profile], function ($message) use ($toEmail, $data) {
				$message->to($toEmail)
					->replyTo($data['email'], $data['name'])
					->subject('Pesan Kontak Baru dari ' . $data['name']);
			});
		} catch (\Throwable $e) {
			return back()->withInput()->with('error', 'Terjadi kesalahan saat mengirim email.');
		}

		return back()->with('success', 'Terima kasih, pesan Anda telah dikirim.');
	}
}

