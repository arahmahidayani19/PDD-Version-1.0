<?php
// Pastikan path PDF diambil dari parameter GET secara aman
$file = isset($_GET['file']) ? htmlspecialchars($_GET['file']) : '';

if ($file) {
    $file_path = "file_proxy.php?path=" . urlencode($file);
} else {
    die("No PDF file specified.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View PDF</title>
    <script src="pdf.min.js"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        #pdf-container {
            max-width: 100%;
            margin: 20px auto;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow-y: auto;
            height: 90vh; /* Tinggi area scroll */
            padding: 10px;
        }

        .pdf-page {
            margin-bottom: 10px;
            border-bottom: 2px solid #007BFF;
        }

        canvas {
            display: block;
            margin: 0 auto;
            width: 100%; /* Membuat kanvas responsif */
            height: auto;
        }
    </style>
</head>
<body>
<div id="pdf-container"></div>

<script>
    let pdfDoc = null;

    async function renderPage(page, container) {
        const viewport = page.getViewport({ scale: 1 });
        const scale = window.devicePixelRatio || 1; // Gunakan devicePixelRatio untuk resolusi tinggi
        const scaledViewport = page.getViewport({ scale });

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        canvas.classList.add('pdf-page');
        canvas.width = scaledViewport.width * scale;
        canvas.height = scaledViewport.height * scale;
        canvas.style.width = `${scaledViewport.width}px`;
        canvas.style.height = `${scaledViewport.height}px`;
        container.appendChild(canvas);

        const renderContext = {
            canvasContext: context,
            viewport: scaledViewport,
        };
        context.setTransform(scale, 0, 0, scale, 0, 0); // Atur skala untuk kanvas
        await page.render(renderContext).promise;
    }

    async function renderPDF(url) {
        const loadingTask = pdfjsLib.getDocument(url);
        pdfDoc = await loadingTask.promise;

        const container = document.getElementById('pdf-container');
        container.innerHTML = ''; // Hapus konten sebelumnya jika ada

        for (let num = 1; num <= pdfDoc.numPages; num++) {
            const page = await pdfDoc.getPage(num);
            await renderPage(page, container);
        }
    }

    // Inisialisasi render PDF
    renderPDF('<?php echo $file_path; ?>');
</script>
</body>
</html>
