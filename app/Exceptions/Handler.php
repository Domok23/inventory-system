<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            return $this->renderHttpException($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render the given HttpException.
     */
    protected function renderHttpException(HttpException $e)
    {
        $statusCode = $e->getStatusCode();

        // Use custom error messages for better user experience
        $message = $this->getErrorMessage($statusCode);

        if (view()->exists("errors.{$statusCode}")) {
            return response()->view(
                "errors.{$statusCode}",
                [
                    'exception' => $e,
                    'message' => $message,
                ],
                $statusCode,
                $e->getHeaders(),
            );
        }

        return parent::renderHttpException($e);
    }

    /**
     * Get user-friendly error messages in Indonesian.
     */
    private function getErrorMessage($statusCode)
    {
        $messages = [
            // 3xx Redirect Errors
            300 => 'Terdapat beberapa pilihan untuk resource yang Anda minta. Silakan pilih salah satu.',
            301 => 'Halaman telah dipindahkan secara permanen ke lokasi baru.',
            302 => 'Halaman sementara dipindahkan ke lokasi lain.',
            303 => 'Respons untuk permintaan Anda dapat ditemukan di lokasi lain.',
            304 => 'Konten belum dimodifikasi sejak permintaan terakhir Anda.',
            305 => 'Akses ke resource ini harus melalui proxy yang ditentukan.',
            306 => 'Status code ini sudah tidak digunakan lagi.',
            307 => 'Halaman sementara dipindahkan dengan mempertahankan metode request.',
            308 => 'Halaman telah dipindahkan secara permanen dengan mempertahankan metode request.',
            310 => 'Resource memiliki beberapa representasi yang tersedia. Silakan pilih format yang diinginkan.',

            // 4xx Client Errors
            400 => 'Permintaan yang Anda kirim tidak valid. Silakan periksa data yang Anda masukkan.',
            401 => 'Anda perlu masuk terlebih dahulu untuk mengakses halaman ini.',
            403 => 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.',
            404 => 'Halaman yang Anda cari tidak ditemukan. Silakan periksa kembali URL atau kembali ke beranda.',
            405 => 'Metode HTTP yang digunakan tidak diizinkan untuk halaman ini.',
            406 => 'Server tidak dapat menghasilkan respons yang dapat diterima oleh browser Anda.',
            408 => 'Permintaan Anda membutuhkan waktu terlalu lama. Silakan coba lagi.',
            409 => 'Terjadi konflik dengan kondisi saat ini. Silakan refresh halaman dan coba lagi.',
            410 => 'Halaman yang Anda cari sudah tidak tersedia lagi.',
            411 => 'Permintaan perlu menyertakan panjang konten yang valid.',
            413 => 'Data yang Anda kirim terlalu besar. Silakan kurangi ukuran file atau data.',
            414 => 'URL yang Anda masukkan terlalu panjang.',
            415 => 'Format file atau media yang Anda kirim tidak didukung.',
            422 => 'Data yang Anda kirim tidak dapat diproses. Silakan periksa kembali.',
            429 => 'Terlalu banyak permintaan. Silakan tunggu sebentar sebelum mencoba lagi.',

            // 5xx Server Errors
            500 => 'Terjadi kesalahan pada server. Tim teknis kami sedang menangani masalah ini.',
            501 => 'Fitur yang Anda minta belum tersedia. Silakan hubungi administrator.',
            502 => 'Server mengalami masalah komunikasi. Silakan coba lagi dalam beberapa saat.',
            503 => 'Layanan sedang dalam pemeliharaan. Silakan coba lagi nanti.',
            504 => 'Server membutuhkan waktu terlalu lama untuk merespons. Silakan coba lagi.',
            505 => 'Versi HTTP yang digunakan tidak didukung oleh server.',
            507 => 'Server kehabisan ruang penyimpanan. Silakan hubungi administrator.',
            508 => 'Server mendeteksi loop tak terbatas dalam memproses permintaan.',
            511 => 'Diperlukan autentikasi jaringan untuk mengakses layanan ini.',
        ];

        return $messages[$statusCode] ?? 'Terjadi kesalahan yang tidak diketahui.';
    }
}
