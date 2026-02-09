<?php

namespace App\Http\Controllers;

use App\Models\SchoolBackground;
use App\Models\SchoolContent;
use App\Models\SchoolProgram;
use App\Models\SchoolProfile;
use App\Models\User;

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
}

