<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de arquivo CSV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>
<body class="container mt-5">
    <a href="/lines.php">Pesquisar</a>
    <h1 class="mb-4">Upload de arquivo CSV</h1>
    <form method="post" action="api/upload.php" enctype="multipart/form-data" class="mb-4">
        <div class="form-group">
            <label for="campaignName">Nome da Campanha:</label>
            <input type="text" id="campaignName" name="campaignName" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="csvFile">Escolha o arquivo CSV:</label>
            <input type="file" id="csvFile" name="file" class="form-control-file" accept=".csv" required>
        </div>
        <button type="button" class="btn btn-primary" onclick="submitForm()">Upload</button>
    </form>

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Titulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="dialog_message">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const dialogMessage = document.getElementById("dialog_message");
        const dialogTitle = document.getElementById("modalLabel");

        async function submitForm() {
            const input = document.querySelector('input[type="file"]');
            const data = new FormData();
            data.append('file', input.files[0]);
            data.append('campaignName', document.getElementById('campaignName').value);

            try {
                const response = await fetch('/api/upload.php', {
                    method: 'POST',
                    body: data
                });

                const json = await response.json();

                if (!response.ok) {
                    throw new Error(json.message|| json.error || 'Ocorreu um erro inesperado');
                }

                dialogMessage.innerText = json.message || 'Upload successful!';
                dialogTitle.innerText = "OK"
            } catch (error) {
                dialogMessage.innerText = error.message || error.error;
                dialogTitle.innerText = "ERRO"
            }

            const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
            modal.show();
        }

        document.getElementById('confirmButton').addEventListener('click', submitForm);
    </script>
    
</body>
</html>
