#! /bin/bash

TEMP=$(mktemp -t chart.XXXXX)
QUERY1=36
QUERY2=64
cat > $TEMP <<EOF
<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Title');
        data.addColumn('number', 'Value');
        data.addRows([
          ['Error Percentage', $QUERY1],
          ['No Error Percentage', $QUERY2]
        ]);

        // Set chart options
        var options = {'title':'Errors',
                       'width':400,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>

  <body>
    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>
EOF

# open browser
case $(uname) in
   Darwin)
      open -a /Applications/Google\ Chrome.app $TEMP
      ;;

   Linux|SunOS)
      firefox $TEMP
      ;;
 esac

