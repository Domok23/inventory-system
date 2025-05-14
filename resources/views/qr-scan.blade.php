<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
</head>

<body>
    <div class="container mt-5 text-center">
        <h2>Scan Inventory QR Code</h2>
        <div id="reader" style="width: 300px; margin: 0 auto;"></div>
        <p class="mt-3 text-muted">Place a QR code in front of the camera to scan.</p>
    </div>

    <script>
        const html5QrCode = new Html5Qrcode("reader");

        html5QrCode.start({
                facingMode: "environment"
            }, // Gunakan kamera belakang
            {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            },
            (decodedText) => {
                // Kirim data QR ke backend
                fetch('/process-qr', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            qrData: decodedText
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect ke halaman detail barang
                            window.location.href = data.url;
                        } else {
                            alert(data.message); // Tampilkan pesan error jika barang tidak ditemukan
                        }
                    })
                    .catch(error => console.error('Error:', error));

                // Hentikan scanner setelah berhasil
                html5QrCode.stop();
            },
            (errorMessage) => {
                console.log(errorMessage); // Log error (opsional)
            }
        ).catch((err) => {
            console.error("Unable to start scanning.", err);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
