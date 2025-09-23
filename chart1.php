<div id="piechart" style="width: 100%; height: 400px;"></div>
<script type="text/javascript">
    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?= $json_data_grafico ?>);

        var options = {
            title: 'Receitas vs Despesas',
            is3D: true,
            pieSliceText: 'value',
            sliceVisibilityThreshold: 0,
            colors: ['#198754', '#DC3545'] // primeira fatia = verde, segunda = vermelho
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }

    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);
    // Garante responsividade
    window.addEventListener('resize', drawChart);
</script>