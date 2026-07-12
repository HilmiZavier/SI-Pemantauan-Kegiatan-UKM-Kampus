<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Lakukan sesuatu sebelum request dikirim ke controller
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah session logged_in bernilai true
        if (!session()->get('logged_in')) {
            
            // (Opsional) Simpan URL tujuan ke session agar setelah login bisa diarahkan kembali
            // session()->set('redirect_url', current_url());

            // Set pesan error menggunakan flashdata
            session()->setFlashdata('error', 'Akses ditolak. Silakan login terlebih dahulu.');
            
            // Redirect ke halaman login
            return redirect()->to('/login');
        }
    }

    /**
     * Lakukan sesuatu setelah response digenerate
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa di sini untuk proses autentikasi
    }
}
