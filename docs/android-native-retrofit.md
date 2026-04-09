Android Native Retrofit Guide for Presensi API

Base URL
- https://tkaba54semarang.my.id/

API Endpoints
- POST /api/login/google
- GET /api/me
- GET /api/presensi/riwayat
- POST /api/presensi/masuk
- GET /api/profil-sekolah
- POST /api/logout

Required Headers
- Accept: application/json
- Authorization: Bearer <token> for protected endpoints

Suggested Gradle Dependencies
- implementation("com.squareup.retrofit2:retrofit:2.11.0")
- implementation("com.squareup.retrofit2:converter-gson:2.11.0")
- implementation("com.squareup.okhttp3:okhttp:4.12.0")
- implementation("com.squareup.okhttp3:logging-interceptor:4.12.0")
- implementation("org.jetbrains.kotlinx:kotlinx-coroutines-android:1.8.1")

Data Classes

package com.gerbang.presensi.data

data class ApiEnvelope<T>(
    val status: String,
    val message: String? = null,
    val data: T? = null
)

data class LoginRequest(
    val email: String,
    val google_id: String,
    val name: String? = null
)

data class LoginUser(
    val id: Long,
    val name: String,
    val email: String,
    val role: String,
    val kelas: String?
)

data class LoginResponse(
    val status: String,
    val message: String,
    val token_type: String,
    val token: String,
    val user: LoginUser
)

data class MeTodayPresensi(
    val tanggal: String?,
    val jam_masuk: String?,
    val jam_pulang: String?,
    val status: String?,
    val keterangan: String?
)

data class MeResponse(
    val id: Long,
    val name: String,
    val email: String,
    val role: String,
    val kelas: String?,
    val phone: String?,
    val profile_photo_url: String?,
    val presensi_hari_ini: MeTodayPresensi?
)

data class RiwayatItem(
    val id: Long,
    val tanggal: String?,
    val jam_masuk: String?,
    val jam_pulang: String?,
    val status: String?,
    val keterangan: String?
)

data class RiwayatData(
    val count: Int,
    val items: List<RiwayatItem>
)

Retrofit Interface

package com.gerbang.presensi.network

import com.gerbang.presensi.data.*
import okhttp3.MultipartBody
import okhttp3.RequestBody
import retrofit2.http.*

interface PresensiApiService {
    @POST("api/login/google")
    suspend fun loginGoogle(@Body body: LoginRequest): LoginResponse

    @GET("api/me")
    suspend fun getMe(): ApiEnvelope<MeResponse>

    @GET("api/presensi/riwayat")
    suspend fun getRiwayat(
        @Query("from") from: String? = null,
        @Query("to") to: String? = null,
        @Query("limit") limit: Int = 30
    ): ApiEnvelope<RiwayatData>

    @Multipart
    @POST("api/presensi/masuk")
    suspend fun presensiMasuk(
        @Part("qr_code") qrCode: RequestBody,
        @Part("latitude") latitude: RequestBody,
        @Part("longitude") longitude: RequestBody,
        @Part fotoBukti: MultipartBody.Part? = null
    ): ApiEnvelope<Map<String, Any>>

    @POST("api/logout")
    suspend fun logout(): ApiEnvelope<Any>

    @GET("api/profil-sekolah")
    suspend fun getProfilSekolah(): ApiEnvelope<Map<String, Any>>
}

Token Storage and Interceptor

package com.gerbang.presensi.auth

import android.content.Context
import okhttp3.Interceptor
import okhttp3.Response

class TokenStore(context: Context) {
    private val prefs = context.getSharedPreferences("auth_pref", Context.MODE_PRIVATE)

    fun saveToken(token: String) {
        prefs.edit().putString("token", token).apply()
    }

    fun getToken(): String? = prefs.getString("token", null)

    fun clearToken() {
        prefs.edit().remove("token").apply()
    }
}

class AuthInterceptor(private val tokenStore: TokenStore) : Interceptor {
    override fun intercept(chain: Interceptor.Chain): Response {
        val token = tokenStore.getToken()
        val reqBuilder = chain.request().newBuilder()
            .addHeader("Accept", "application/json")

        if (!token.isNullOrBlank()) {
            reqBuilder.addHeader("Authorization", "Bearer $token")
        }

        return chain.proceed(reqBuilder.build())
    }
}

Retrofit Builder

package com.gerbang.presensi.network

import com.gerbang.presensi.auth.AuthInterceptor
import com.gerbang.presensi.auth.TokenStore
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

object ApiClient {
    fun create(tokenStore: TokenStore): PresensiApiService {
        val logging = HttpLoggingInterceptor().apply {
            level = HttpLoggingInterceptor.Level.BODY
        }

        val okHttp = OkHttpClient.Builder()
            .addInterceptor(AuthInterceptor(tokenStore))
            .addInterceptor(logging)
            .build()

        return Retrofit.Builder()
            .baseUrl("https://tkaba54semarang.my.id/")
            .client(okHttp)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
            .create(PresensiApiService::class.java)
    }
}

Repository Example

package com.gerbang.presensi.repository

import com.gerbang.presensi.auth.TokenStore
import com.gerbang.presensi.data.LoginRequest
import com.gerbang.presensi.network.PresensiApiService
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.MultipartBody
import okhttp3.RequestBody.Companion.asRequestBody
import okhttp3.RequestBody.Companion.toRequestBody
import java.io.File

class PresensiRepository(
    private val api: PresensiApiService,
    private val tokenStore: TokenStore
) {
    suspend fun login(email: String, googleId: String, name: String?) {
        val response = api.loginGoogle(LoginRequest(email, googleId, name))
        tokenStore.saveToken(response.token)
    }

    suspend fun me() = api.getMe()

    suspend fun riwayat(from: String? = null, to: String? = null, limit: Int = 30) =
        api.getRiwayat(from, to, limit)

    suspend fun presensiMasuk(
        qrCode: String,
        latitude: Double,
        longitude: Double,
        fotoFile: File?
    ) {
        val textType = "text/plain".toMediaType()

        val qr = qrCode.toRequestBody(textType)
        val lat = latitude.toString().toRequestBody(textType)
        val lng = longitude.toString().toRequestBody(textType)

        val part = fotoFile?.let {
            val req = it.asRequestBody("image/*".toMediaType())
            MultipartBody.Part.createFormData("foto_bukti", it.name, req)
        }

        api.presensiMasuk(qr, lat, lng, part)
    }

    suspend fun logout() {
        api.logout()
        tokenStore.clearToken()
    }
}

Simple Usage Flow
1. Login Google di Android (dapatkan email dan google_id).
2. Panggil loginGoogle, simpan token ke TokenStore.
3. Panggil getMe untuk memuat profil user dan status presensi hari ini.
4. Saat presensi, kirim qr_code + latitude + longitude + foto_bukti opsional.
5. Panggil getRiwayat untuk menampilkan riwayat di aplikasi.
6. Saat logout, panggil endpoint logout lalu hapus token lokal.

Important Notes
- Pastikan Android memakai HTTPS dan internet permission aktif.
- Endpoint protected wajib kirim Bearer token.
- Jika dapat 401, biasanya token kosong, expired, atau belum login.

JSON Contract (Request/Response Samples)

1) POST /api/login/google

Request
```json
{
    "email": "guru@tkaba54semarang.my.id",
    "google_id": "109876543210123456789",
    "name": "Nama Guru"
}
```

Success 200
```json
{
    "status": "success",
    "message": "Login berhasil.",
    "token_type": "Bearer",
    "token": "1|XyZAbCdEf...",
    "user": {
        "id": 12,
        "name": "Nama Guru",
        "email": "guru@tkaba54semarang.my.id",
        "role": "guru",
        "kelas": "A1"
    }
}
```

Fail 401
```json
{
    "status": "error",
    "message": "Email tidak terdaftar sebagai guru."
}
```

2) GET /api/me (Bearer token required)

Success 200
```json
{
    "status": "success",
    "data": {
        "id": 12,
        "name": "Nama Guru",
        "email": "guru@tkaba54semarang.my.id",
        "role": "guru",
        "kelas": "A1",
        "phone": "081234567890",
        "profile_photo_url": "https://tkaba54semarang.my.id/storage/profile-photos/photo.jpg",
        "presensi_hari_ini": {
            "tanggal": "2026-04-08",
            "jam_masuk": "07:10:00",
            "jam_pulang": null,
            "status": "H",
            "keterangan": null
        }
    }
}
```

3) GET /api/presensi/riwayat?from=2026-04-01&to=2026-04-30&limit=30 (Bearer token required)

Success 200
```json
{
    "status": "success",
    "data": {
        "count": 2,
        "items": [
            {
                "id": 101,
                "tanggal": "2026-04-08",
                "jam_masuk": "07:10:00",
                "jam_pulang": "13:20:00",
                "status": "H",
                "keterangan": null
            },
            {
                "id": 100,
                "tanggal": "2026-04-07",
                "jam_masuk": "07:21:00",
                "jam_pulang": "13:25:00",
                "status": "T",
                "keterangan": null
            }
        ]
    }
}
```

4) POST /api/presensi/masuk (Bearer token required, multipart/form-data)

Form fields
- qr_code: string
- latitude: numeric
- longitude: numeric
- foto_bukti: image file (optional)

Success 200
```json
{
    "status": "success",
    "message": "Presensi masuk berhasil dicatat dengan status hadir.",
    "data": {
        "presensi_id": 101,
        "tanggal": "2026-04-08",
        "jam_masuk": "07:10:00",
        "status": "H",
        "foto_bukti_url": "https://tkaba54semarang.my.id/storage/presensi-bukti/abc.jpg"
    }
}
```

Fail 422 (outside radius)
```json
{
    "status": "error",
    "message": "Anda berada di luar area yang diizinkan untuk presensi.",
    "distance_meter": 153.21,
    "radius_meter": 100
}
```

Fail 422 (invalid QR)
```json
{
    "status": "error",
    "message": "QR code tidak valid."
}
```

Fail 409 (already checked in)
```json
{
    "status": "error",
    "message": "Presensi masuk sudah tercatat hari ini."
}
```

5) GET /api/profil-sekolah (Bearer token required)

Success 200
```json
{
    "status": "success",
    "data": {
        "school_name": "TK Pembina ABA 54 Semarang",
        "school_logo_url": "https://tkaba54semarang.my.id/storage/school_logos/logo.png",
        "contact_address": "Jl. ...",
        "contact_phone": "082220548870",
        "contact_email": "tkaba54semarang@gmail.com",
        "welcome_message": "Selamat datang",
        "vision": "...",
        "mission": "..."
    }
}
```

6) POST /api/logout (Bearer token required)

Success 200
```json
{
    "status": "success",
    "message": "Logout berhasil."
}
```

Common Error Payload (Laravel Validation)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "latitude": [
            "The latitude field is required."
        ]
    }
}
```

Quick cURL Test

Login
```bash
curl -X POST "https://tkaba54semarang.my.id/api/login/google" \
    -H "Accept: application/json" \
    -H "Content-Type: application/json" \
    -d '{"email":"guru@tkaba54semarang.my.id","google_id":"109876543210123456789","name":"Nama Guru"}'
```

Me
```bash
curl "https://tkaba54semarang.my.id/api/me" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer YOUR_TOKEN"
```

Presensi Masuk
```bash
curl -X POST "https://tkaba54semarang.my.id/api/presensi/masuk" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer YOUR_TOKEN" \
    -F "qr_code=TKABA-PRESENSI" \
    -F "latitude=-6.99" \
    -F "longitude=110.35" \
    -F "foto_bukti=@/path/to/foto.jpg"
```
