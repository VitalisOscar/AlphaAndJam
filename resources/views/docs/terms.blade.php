<div id="pdf-viewer" style="height: 380px; border: 1px solid #ccc" class="mb-3"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.4/pdfobject.min.js"></script>

<script>
    PDFObject.embed("{{ route('terms') }}", "#pdf-viewer");
</script>
