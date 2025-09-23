<?php
session_start();
require 'vendor/autoload.php';

use Dompdf\Dompdf;

include("./conexao.php");

// Pega dados do usuário
$usuario_id = $_SESSION["usuario_id"];
$query = "SELECT * FROM tbl_transacao WHERE usuario_id = '$usuario_id' ORDER BY tipo = 'receita' DESC";
$result = mysqli_query($conn, $query);

// Calcula saldo
$total_receita = 0;
$total_despesa = 0;
while ($row = mysqli_fetch_assoc($result)) {
    if ($row["tipo"] === "receita") {
        $total_receita += $row["valor"];
    } else {
        $total_despesa += $row["valor"];
    }
}
// reseta o ponteiro e busca de novo para preencher tabela
mysqli_data_seek($result, 0);

$saldo = $total_receita - $total_despesa;

// Monta HTML do relatório
$html = '
<h2 style="text-align:center;">Relatório Financeiro</h2>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr style="background:#f2f2f2;">
            <th>ID</th>
            <th>Valor</th>
            <th>Tipo</th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>';

while ($row = mysqli_fetch_assoc($result)) {
    $cor = ($row["tipo"] === "receita") ? "green" : "red";
    $html .= "
        <tr>
            <td>{$row["id"]}</td>
            <td>R$ " . number_format($row["valor"], 2, ',', '.') . "</td>
            <td style='color:{$cor};'>{$row["tipo"]}</td>
            <td>{$row["descricao"]}</td>
        </tr>";
}

$html .= '
    </tbody>
</table>

<br><br>
<h3>Resumo</h3>
<p><strong>Total de Receitas:</strong> R$ ' . number_format($total_receita, 2, ',', '.') . '</p>
<p><strong>Total de Despesas:</strong> R$ ' . number_format($total_despesa, 2, ',', '.') . '</p>
<p><strong>Saldo:</strong> R$ ' . number_format($saldo, 2, ',', '.') . '</p>
';

// Gera o PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("relatorio.pdf", ["Attachment" => true]); // true = download automático
