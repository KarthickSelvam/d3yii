<?php
/* @var $this SiteController */
/* @var $model MyModel */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);
?>
<style>

.node {
  cursor: pointer;
}

.node circle {
  fill: #fff;
  stroke: steelblue;
  stroke-width: 1.5px;
}

.node text {
  font: 10px sans-serif;
}

.link {
  fill: none;
  stroke: #ccc;
  stroke-width: 1.5px;
}
#countries, #country-centroids circle {
  fill: #ccc;
  stroke: #fff;
  stroke-width: 1.5px;
  heigth:100%;
  widht:100%;
}

#country-centroids circle {
  fill: steelblue;
  fill-opacity: .8;
}
</style>
<body>
    <div id="chart">
        
        </div>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script>
var r = d3.scale.sqrt()
    .domain([0, 1e6])
    .range([0, 10]);

// World Map Projection
var xy = d3.geo.mercator();
//.size([width, height]);
path = d3.geo.path().projection(xy);
var width = window.innerWidth,
    height = window.innerHeight;

var svg = d3.select("#chart").append("svg")
.style("width", width + "px")
    .style("height", height + "px")
svg.append("g").attr("id", "countries");
svg.append("g").attr("id", "country-centroids");

d3.json("../data/world-countries.json", function(collection) {
  svg.select("#countries")
    .selectAll("path")
      .data(collection.features)
    .enter().append("path")
      .attr("d", d3.geo.path().projection(xy));
});
//[[[78.08,35.45],[79.53,32.75],[78.4,32.55],[78.77,31.31],[81.03,30.2],[80.06,28.84],[82.07,27.91],[83.29,27.34],[84.15,27.51],[85.86,26.57],[88.01,26.36],[88.14,27.87],[88.83,28.01],[88.92,27.32],[89.64,26.72],[92.07,26.86],[91.66,27.76],[92.54,27.86],[94.65,29.33],[95.39,29.04],[96.08,29.47],[96.62,28.79],[96.4,28.35],[97.35,28.22],[96.89,27.61],[97.14,27.09],[96.19,27.27],[95.14,26.61],[94.15,23.86],[93.34,24.08],[93.2,22.26],[92.6,21.98],[92.28,23.71],[91.61,22.94],[91.16,23.64],[92.41,25.03],[89.85,25.29],[89.74,26.16],[88.43,26.55],[88.11,25.84],[89.01,25.29],[88.04,24.68],[88.75,24.22],[89.06,22.12],[89.07,21.61],[88.71,21.57],[88.67,22.2],[88.25,21.55],[88.2,22.16],[87.91,22.42],[88.17,22.09],[86.96,21.38],[87.03,20.67],[86.42,19.98],[85.43,19.89],[82.36,17.1],[82.3,16.58],[80.28,15.7],[79.86,10.29],[79.32,10.28],[78.91,9.48],[79.45,9.15],[78.4,9.09],[77.54,8.07],[76.58,8.88],[73.45,16.06],[72.66,19.87],[72.93,20.77],[72.56,21.38],[73.13,21.75],[72.5,21.98],[72.92,22.27],[72.15,22.28],[72.11,21.2],[70.82,20.7],[68.94,22.29],[70.17,22.55],[70.51,23.1],[69.22,22.84],[68.43,23.43],[68.74,23.84],[68.2,23.77],[68.78,24.33],[71.11,24.42],[69.58,27.17],[70.37,28.02],[71.9,27.96],[74.69,31.05],[74.61,31.88],[75.38,32.21],[74.02,33.19],[74.3,33.98],[73.94,34.65],[76.87,34.66],[77.82,35.5],[78.08,35.45]]]]
//d3.json("http://10.1.1.221/insightsAPI/coach.svc/GetUsersCount", function(collection) {
d3.json("../data/base.json", function(collection) {
  svg.select("#country-centroids")
    .selectAll("circle")
      .data(collection.features
      .sort(function(a, b) { return b.properties.population - a.properties.population; }))
    .enter().append("circle")
      .attr("transform", function(d) { return "translate(" + xy(d.geometry.coordinates) + ")"; })
      .attr("r", 0)
    .transition()
      .duration(1000)
      .delay(function(d, i) { return i * 50; })
      .attr("r", function(d) { return r(d.properties.population); });
});

</script>
<h1>World Users based on countries</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
</p>

<div class="form">
<?php

?>
</div><!-- form -->

<?php endif; ?>