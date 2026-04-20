<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\SchoolContent;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;

class BeritaAppController extends Controller
{
	public function home()
	{
		$orderedQuery = Berita::query()
			->orderByDesc('tanggal_berita')
			->orderByDesc('created_at');

		$headline = (clone $orderedQuery)->first();

		$latestNews = collect();
		$topArticles = collect();

		if ($headline) {
			$baseQuery = (clone $orderedQuery)->where('id', '!=', $headline->id);

			$latestNews = (clone $baseQuery)
				->limit(8)
				->get();
		}

		$topArticles = (clone $orderedQuery)
			->limit(6)
			->get();

		$schoolProfile = SchoolProfile::first();

		return view('app.home_berita', [
			'headline' => $headline,
			'latestNews' => $latestNews,
			'topArticles' => $topArticles,
			'schoolProfile' => $schoolProfile,
		]);
	}

	public function show(Berita $berita)
	{
		$schoolProfile = SchoolProfile::first();

		$recentBeritas = Berita::query()
			->where('id', '!=', $berita->id)
			->orderByDesc('tanggal_berita')
			->orderByDesc('created_at')
			->limit(6)
			->get();

		return view('app.read_berita', [
			'berita' => $berita,
			'recentBeritas' => $recentBeritas,
			'schoolProfile' => $schoolProfile,
		]);
	}

	public function news(Request $request)
	{
		$search = trim((string) $request->input('q', ''));

		$query = Berita::query();

		if (!empty($search)) {
			$query->where(function ($builder) use ($search) {
				$builder->where('judul', 'like', '%' . $search . '%')
					->orWhere('isi', 'like', '%' . $search . '%');
			});
		}

		$beritas = $query
			->orderByDesc('tanggal_berita')
			->orderByDesc('created_at')
			->paginate(15)
			->withQueryString();

		$recentBeritas = Berita::query()
			->orderByDesc('tanggal_berita')
			->orderByDesc('created_at')
			->limit(7)
			->get();

		$schoolProfile = SchoolProfile::first();

		return view('app.news_berita', [
			'beritas' => $beritas,
			'recentBeritas' => $recentBeritas,
			'schoolProfile' => $schoolProfile,
			'currentSearch' => $search,
		]);
	}

	public function showNews(Berita $berita)
	{
		$schoolProfile = SchoolProfile::first();

		$recentBeritas = Berita::query()
			->where('id', '!=', $berita->id)
			->orderByDesc('tanggal_berita')
			->orderByDesc('created_at')
			->limit(6)
			->get();

		return view('app.read_news_berita', [
			'berita' => $berita,
			'recentBeritas' => $recentBeritas,
			'schoolProfile' => $schoolProfile,
		]);
	}

	public function video()
	{
		$schoolProfile = SchoolProfile::first();

		$query = SchoolContent::query()
			->where('platform', 'youtube')
			->orderByDesc('created_at');

		if ($schoolProfile) {
			$query->where('school_profile_id', $schoolProfile->id);
		} else {
			$query->whereRaw('1 = 0');
		}

		$videos = $query->paginate(15);

		return view('app.video_berita', [
			'videos' => $videos,
			'schoolProfile' => $schoolProfile,
		]);
	}

	public function instagram()
	{
		$schoolProfile = SchoolProfile::first();

		$query = SchoolContent::query()
			->where('platform', 'instagram')
			->orderByDesc('created_at');

		if ($schoolProfile) {
			$query->where('school_profile_id', $schoolProfile->id);
		} else {
			$query->whereRaw('1 = 0');
		}

		$instagramContents = $query->paginate(6);

		return view('app.instagram_berita', [
			'instagramContents' => $instagramContents,
			'schoolProfile' => $schoolProfile,
		]);
	}
}
