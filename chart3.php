<?php
// filepath: c:\wamp64\www\template-login\charT3.php
$usuario_id = $_SESSION["usuario_id"];

$consultaSQL = "SELECT 
    tipo,
    SUM(valor) AS total,
    DATE(data) AS data_transacao
FROM 
    tbl_transacao
WHERE 
    usuario_id = $usuario_id AND
    data BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()
GROUP BY 
    tipo, DATE(data)
ORDER BY
    DATE(data), tipo;";

$resultadoConsulta = $conn->query($consultaSQL);

$dadosGrafico = array();
$dadosGrafico[] = array('Data', 'Receitas', 'Despesas');

$valoresReceitas = array();
$valoresDespesas = array();

while ($linha = $resultadoConsulta->fetch_assoc()) {
    $dataTransacao = $linha['data_transacao'];
    $tipoTransacao = $linha['tipo'];
    $totalTransacao = (float)$linha['total'];

    if ($tipoTransacao == 'receita') {
        $valoresReceitas[$dataTransacao] = $totalTransacao;
    } elseif ($tipoTransacao == 'despesa') {
        $valoresDespesas[$dataTransacao] = $totalTransacao;
    }
}

$dataInicial = new DateTime('-6 days');
$dataFinal = new DateTime('today');

for ($data = $dataInicial; $data <= $dataFinal; $data->modify('+1 day')) {
    $dataString = $data->format('Y-m-d');
    $receitaDia = isset($valoresReceitas[$dataString]) ? $valoresReceitas[$dataString] : 0;
    $despesaDia = isset($valoresDespesas[$dataString]) ? $valoresDespesas[$dataString] : 0;

    $dadosGrafico[] = array($dataString, $receitaDia, $despesaDia);
}

$jsonDataGrafico = json_encode($dadosGrafico);
?>

<div id="chart_div" style="width: 100%; height: 600px;"></div>
<script type="text/javascript">
    
    function desenharGrafico() {
        var data = google.visualization.arrayToDataTable(<?= $jsonDataGrafico ?>);
        
        var options = {
            title: 'Receitas e Despesas ao Longo da Semana',
            hAxis: {
                title: 'Dias da Semana',
                titleTextStyle: {
                    color: '#333'
                }
            },
            vAxis: {
                minValue: 0
            },
            series: {
                0: {
                    color: '#198754'
                }, // Receitas em verde
                1: {
                    color: '#DC3545'
                } // Despesas em vermelho
            }
        };
        
        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(desenharGrafico);
    window.addEventListener('resize', drawChart);
</script>