
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Campanhas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        #searchBar {
            margin-bottom: 20px;
            padding: 10px;
            width: 100%;
            font-size: 16px;
        }
    </style>
</head>
<body>

<h1>Lista de Campanhas</h1>
<input type="text" id="searchBar" placeholder="Pesquisar..." onkeyup="fetchCampaignLines()">

<table>
    <thead>
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
        <!-- Os dados da campanha serão inseridos aqui -->
    </tbody>
</table>

<script>
    async function fetchCampaignLines() {
        try {
            const response = await fetch('/api/search.php?search=' + document.getElementById('searchBar').value); // Substitua pela URL da sua API
            if (!response.ok) {
                throw new Error('Erro ao buscar dados: ' + response.status);
            }
            const data = await response.json();
            console.log(data)
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

    function filterTable() {
        const input = document.getElementById('searchBar');
        const filter = input.value.toLowerCase();
        const tableBody = document.getElementById('campaignTableBody');
        const rows = tableBody.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let found = false;

            // Check each cell in the row for a match
            for (let j = 0; j < cells.length; j++) {
                if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
            rows[i].style.display = found ? '' : 'none'; // Show or hide row
        }
    }

    window.onload = fetchCampaignLines;
</script>

</body>
</html>
