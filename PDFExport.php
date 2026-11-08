<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>

	<link rel="stylesheet" href="css/test.css">
</head>
<body>
	
	<div class="container mt-5">
		<div id="example-table">
			<h1>Poorin is real</h1>
			<p>
				Hi, this is a test text.
			</p>
		</div>

		<button onclick="previewPDF('PoorinHandsome')">Preview PDF</button>
	</div>

	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

	<!-- HTML2PDF -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

	<script>
        function previewPDF(filenameInput) {
            var element = document.getElementById("example-table");

            var opt = {
                margin:			1,
                filename:		filenameInput,
                image:			{ type: 'jpeg', quality: 0.98 },
                html2canvas:	{ scale: 2 },
                jsPDF:			{ unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).outputPdf('datauristring').then(function (pdfDataUri) {
                var newTab = window.open();
                newTab.document.open();
                newTab.document.write('<iframe src="' + pdfDataUri + '" width="100%" height="100%"></iframe>');
                newTab.document.close();
            });
        }
    </script>

</body>
</html>