<div id="columnchart_values" style="width: 100%; height: 400px;"></div>
<script type="text/javascript">
  google.charts.load("current", {
    packages: ['corechart']
  });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    console.log(<?= $json_data_grafico ?>);
    var data = google.visualization.arrayToDataTable(<?= $json_data_grafico ?>);

    var options = {
      title: "Receitas vs Despesas",
      width: '100%',
      height: 400,
      legend: {
        position: "none"
      },
      vAxis: {
        title: "Valor"
      },
      hAxis: {
        title: "Tipo"
      }
    };

    var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
    chart.draw(data, options);
  }
  // Garante responsividade
  window.addEventListener('resize', drawChart);
</script>