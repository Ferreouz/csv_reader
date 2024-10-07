<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Campanhas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body class="container mt-5">

<a href="/">Upload</a>

<h1 class="mb-4">Lista de Campanhas</h1>
<input type="text" id="searchBar" class="form-control mb-4" placeholder="Pesquisar por Campanha/Telefone" onkeyup="fetchCampaignLines()">

<table class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sobrenome</th>
            <th>Email</th>
            <th>Endereço</th>
            <th>Telefone</th>
            <th>Cidade</th>
            <th>CEP</th>
            <th>Data de Nascimento</th>
            <th>Campanha</th>
            <th>Data de Criação</th>
        </tr>
    </thead>
    <tbody id="campaignTableBody">
    </tbody>
</table>

<script>
    async function fetchCampaignLines() {
        try {
            const response = await fetch('/api/search.php?search=' + encodeURIComponent(document.getElementById('searchBar').value)); // Substitua pela URL da sua API
            if (!response.ok) {
                throw new Error('Erro ao buscar dados: ' + response.status);
            }
            const data = await response.json();
            populateTable(data.message);
        } catch (error) {
            console.error(error);
            alert('Erro ao carregar os dados. Tente novamente mais tarde.');
        }
    }

    function populateTable(campaignLines) {
        const tableBody = document.getElementById('campaignTableBody');
        tableBody.innerHTML = ''; // Limpa a tabela

        campaignLines.forEach(line => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${line.id}</td>
                <td>${line.nome}</td>
                <td>${line.sobrenome}</td>
                <td>${line.email}</td>
                <td>${line.endereco}</td>
                <td>${line.telefone}</td>
                <td>${line.cidade}</td>
                <td>${line.cep}</td>
                <td>${line.data_nascimento}</td>
                <td>${line.campaign}</td>
                <td>${new Date(line.created_at).toLocaleString('pt-BR')}</td>
            `;
            tableBody.appendChild(row);
        });
    }

    window.onload = fetchCampaignLines;
</script>

</body>
</html>