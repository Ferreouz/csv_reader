<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Upload Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="text"], input[type="file"] {
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<a href="/lines.php">Pesquisar</a>
<h1>Upload CSV File</h1>
<form method="post" action="api/upload.php" enctype="multipart/form-data">
    <label for="campaignName">Nome da Campanha:</label>
    <input type="text" id="campaignName" name="campaignName" required>

    <label for="csvFile">Escolha o arquivo CSV:</label>
    <input type="file" id="csvFile" name="file" accept=".csv" required>

    <button type="submit">Upload</button>
</form>
<script>
        // Function to get a cookie by name
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }

        // Function to delete a cookie
        function deleteCookie(name) {
            document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
        }

        const lastError = getCookie('lastError');
        const messageElement = document.getElementById('message');

        if (lastError) {
            messageElement.textContent = `${lastError}`;

            // Delete the cookie after displaying its value
            deleteCookie('lastError');
        }
    </script>
</body>
</html>
