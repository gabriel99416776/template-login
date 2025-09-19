 <script type="text/javascript">
     google.charts.load("current", {
         packages: ["corechart"]
     });
     google.charts.setOnLoadCallback(drawChart);

     function drawChart() {
         var data = google.visualization.arrayToDataTable([
             ['Task', 'Hours per Day'],
             ['Work', 11],
             ['Eat', 2],
             ['Commute', 2],
             ['Watch TV', 15],
             ['Sleep', 7]
         ]);

         var options = {
             title: 'My Daily Activities',
             pieHole: 0.4,
             backgroundColor: '#e6e6e6ff' // ou qualquer cor desejada
         };

         var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
         chart.draw(data, options);
     }

     google.charts.load("current", {
         packages: ['corechart']
     });
 </script>
 <div id="donutchart"  style="width: 800px; height: 500px; border-radius: 50px; overflow: hidden;"></div>