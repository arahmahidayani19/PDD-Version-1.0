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

        #pdf-viewer {
            max-width: 100%;
            margin: 20px auto;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        canvas {
            display: block;
            margin: 0 auto;
            border-bottom: 2px solid #007BFF;
        }

        #pdf-controls {
            display: flex;
            justify-content: center;
            padding: 10px;
            background: #007BFF;
        }

        #pdf-controls button {
            background-color: white;
            border: none;
            color: #007BFF;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;
        }

        #pdf-controls button:hover {
            background-color: #007BFF;
            color: white;
        }

        #pdf-controls button:disabled {
            background-color: #e0e0e0;
            color: #999;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
<div id="pdf-viewer">
    <canvas id="pdf-canvas"></canvas>
    <div id="pdf-controls">
        <button id="prev">Previous</button>
        <button id="next">Next</button>
    </div>
</div>

<script>
    let pdfDoc = null;
    let pageNum = 1;

    async function renderPage(num) {
        const page = await pdfDoc.getPage(num);
        const viewport = page.getViewport({ scale: 1 });

        // Atur skala sesuai orientasi halaman
        const scale = (viewport.width > viewport.height) ? Math.min(window.innerWidth / viewport.width, 1) : Math.min(window.innerHeight / viewport.height, 1);
        const scaledViewport = page.getViewport({ scale });

        const canvas = document.getElementById('pdf-canvas');
        const context = canvas.getContext('2d');

        canvas.height = scaledViewport.height;
        canvas.width = scaledViewport.width;

        const renderContext = {
            canvasContext: context,
            viewport: scaledViewport
        };

        await page.render(renderContext).promise;

        // Kontrol tombol navigasi
        document.getElementById('prev').disabled = (num <= 1);
        document.getElementById('next').disabled = (num >= pdfDoc.numPages);
    }

    (async () => {
        const loadingTask = pdfjsLib.getDocument('<?php echo $file_path; ?>');
        pdfDoc = await loadingTask.promise;
        renderPage(pageNum);
    })();

    document.getElementById('prev').addEventListener('click', () => {
        if (pageNum <= 1) return;
        pageNum--;
        renderPage(pageNum);
    });

    document.getElementById('next').addEventListener('click', () => {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        renderPage(pageNum);
    });

    // Responsif terhadap perubahan ukuran jendela
    window.addEventListener('resize', () => {
        if (pdfDoc) {
            renderPage(pageNum);
        }
    });
</script>
</body>
</html>
