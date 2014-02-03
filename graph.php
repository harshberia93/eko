<?php
  session_start();
  if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit(0);
  }
  $username = $_SESSION['username'];
?>

<!DOCTYPE html>
<meta charset="utf-8">
<html lang="en">
  <head>
    <title>Graph</title>
    <link href="static/css/bootstrap.css" rel="stylesheet">
    
  
  <style type="text/css">

  .custom-btn{
    width:100%;
    background-color: #59407F;
    color:#fff;
  }

    .axis path,
    .axis line {
      fill: none;
      stroke: #000;
      shape-rendering: crispEdges;
    }

    .x.axis path {
      display: none;
    }

    .line {
      fill: none;
      stroke: 'steelblue';
      stroke-width: 1.5px;
    }


    .date-icon{
      margin-left: -25px;
    }
  </style>
  </head>
  <body>
    <div class="container">
      <?php 
        include 'navbar.html'
      ?>
  
      <form class="form" role="form" id="plotDetailForm">
        <div class="row">
          <div class="col-sm-2"><h5>Date range</h5></div>
          <div class="col-sm-4">
            <div class="row">
              <div class="col-xs-10"><input type="text" class="form-control" id="startDate" name="startDate"  placeholder="start date"></div>
              <div class="col-xs-2 date-icon"><a href="#" id="startDatePicker"><span class=" glyphicon glyphicon-calendar" id="startDatePicker"></span></a></div>
            </div>
            
            
          </div>
          <div class="col-sm-4">
            <div class="row">
              <div class="col-xs-10"><input type="text" class="form-control" id="endDate" name="endDate" placeholder="end date"></div>
              <div class="col-xs-2 date-icon"><a href="#" id="endDatePicker"><span id="endDatePicker" class=" glyphicon glyphicon-calendar"></span></a></div>
            </div>
          </div>
          <div class="col-sm-4"></div>
        </div>
        <div class="row">
          <div class="col-md-2">Node</div>
          <div class="col-md-2"><input type="radio" name="node" value="node1" id="node1"><label for="node1">Node1</label></div>
          <div class="col-md-2"><input type="radio" name="node" value="node2" id="node2"><label for="node2">Node2</label></div>
          <div class="col-md-2"><input type="radio" name="node" value="node3" id="node3"><label for="node3">node3</label></div>
          <div class="col-md-2"><input type="radio" name="node" value="node4" id="node4"><label for="node4">node4</label></div>
          <div class="col-md-2"></div>
        </div>
        <div class="row">
          <div class="col-md-2">Node</div>
          <div class="col-md-2"><input type="checkbox" name="port" value="portA" id="portA"><label for="portA">portA</label></div>
          <div class="col-md-2"><input type="checkbox" name="port" value="portB" id="portB"><label for="portB">portB</label></div>
          <div class="col-md-2"><input type="checkbox" name="port" value="portC" id="portC"><label for="portC">portC</label></div>
          <div class="col-md-2"><input type="checkbox" name="port" value="portD" id="portD"><label for="portD">portD</label></div>
        </div>
        <div class="row">
          <div class="col-md-2"><button class="custom-btn btn btn-sm" id="plotGraph"><b>Plot Graph</b></button></div>
          <div class="col-md-10"></div>
        </div>
      </form>

    </div>
  </body>
</html>

<script src="http://d3js.org/d3.v3.js"></script>
<script>

var margin = {top: 20, right: 80, bottom: 30, left: 50},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var parseDate = d3.time.format("%Y%m%d").parse;

var x = d3.time.scale()
    .range([0, width]);

var y = d3.scale.linear()
    .range([height, 0]);

var color = d3.scale.category10();

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");

var line = d3.svg.line()
    .interpolate("basis")
    .x(function(d) { return x(d.date); })
    .y(function(d) { return y(d.rainfall); });

var svg = d3.select("body").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

d3.tsv("data1.tsv", function(error, data) {
  color.domain(d3.keys(data[0]).filter(function(key) { return key !== "date"; }));
  data.forEach(function(d) {
    d.date = parseDate(d.date);
  });

  var nodes = color.domain().map(function(name) {
    return {
      name: name,
      values: data.map(function(d) {
        // if (d.date > parseDate('20130815')){
          // console.log(d[name]);
        // }          
        return {date: d.date, rainfall: +d[name]};
      })
    };
  });

  x.domain(d3.extent(data, function(d) { return d.date; }));

  y.domain([
    d3.min(nodes, function(c) { return d3.min(c.values, function(v) { return v.rainfall; }); }),
    d3.max(nodes, function(c) { return d3.max(c.values, function(v) { return v.rainfall; }); })
  ]);

  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Rainfall (%)");

  var city = svg.selectAll(".city")
      .data(nodes)
    .enter().append("g")
      .attr("class", "city");

  city.append("path")
      .attr("class", "line")
      .attr("d", function(d) { return line(d.values); })
      .style("stroke", function(d) { return color(d.name); });

  city.append("text")
      .datum(function(d) { return {name: d.name, value: d.values[d.values.length - 1]}; })
      .attr("transform", function(d) { return "translate(" + x(d.value.date) + "," + y(d.value.rainfall) + ")"; })
      .attr("x", 3)
      .attr("dy", ".35em")
      .text(function(d) { return d.name; });
});
</script>