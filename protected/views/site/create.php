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

</style>
<body>
    <div id="chart">
        
        </div>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script>
function getfile(jsonfile){
    
var margin = {top: 20, right: 120, bottom: 20, left: 120},
    width = 960 - margin.right - margin.left,
    height = 800 - margin.top - margin.bottom;
    
var i = 0,
    duration = 750,
    root;

var tree = d3.layout.tree()
    .size([height, width]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

var svg = d3.select("#chart").append("svg")
    .attr("width", width + margin.right + margin.left)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


$.ajax({
            type: 'GET',
            url: 'index.php?r=site/Csv',
            //url: 'http://reports.globalenglish.com/Insights/V3/' + url,
            data: jsonfile,
            dataType: "json",
            async: false,
            success: function (flare) {
console.log(flare);
    for(val in flare){
console.log(flare[val].Id);
}
  root = flare;
  root.x0 = height / 2;
  root.y0 = 0;
 
  function collapse(d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(collapse);
      d.children = null;
    }
  }

  root.children.forEach(collapse);
// root.forEach(collapse);
  update(root);
            },
            error: function (msg) {
                //Error code goes here
                console.log(msg);
            }
        });
/*d3.json('index.php/site/Csv', function(error, flare) {
    console.log(flare);
    for(val in flare){
console.log(flare[val].Id);
}
  root = flare;
  root.x0 = height / 2;
  root.y0 = 0;
 
  function collapse(d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(collapse);
      d.children = null;
    }
  }

  root.children.forEach(collapse);
  update(root);
});*/

d3.select(self.frameElement).style("height", "800px");

function update(source) {

  // Compute the new tree layout.
  var nodes = tree.nodes(root).reverse(),
      links = tree.links(nodes);

  // Normalize for fixed-despth.
  nodes.forEach(function(d) { d.y = d.depth * 180; });

  // Update the nodes…
  var node = svg.selectAll("g.node")
      .data(nodes, function(d) { return d.id || (d.id = ++i); });

  // Enter any new nodes at the parent's previous position.
  var nodeEnter = node.enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
      .on("click", click);

  nodeEnter.append("circle")
      .attr("r", 1e-6)
      .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

  nodeEnter.append("text")
      .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
      .attr("dy", ".35em")
      .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
      .text(function(d) { return d.Name; })
      .style("fill-opacity", 1e-6);

  // Transition nodes to their new position.
  var nodeUpdate = node.transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

  nodeUpdate.select("circle")
      .attr("r", 4.5)
      .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

  nodeUpdate.select("text")
      .style("fill-opacity", 1);

  // Transition exiting nodes to the parent's new position.
  var nodeExit = node.exit().transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
      .remove();

  nodeExit.select("circle")
      .attr("r", 1e-6);

  nodeExit.select("text")
      .style("fill-opacity", 1e-6);

  // Update the links…
  var link = svg.selectAll("path.link")
      .data(links, function(d) { return d.target.id; });

  // Enter any new links at the parent's previous position.
  link.enter().insert("path", "g")
      .attr("class", "link")
      .attr("d", function(d) {
        var o = {x: source.x0, y: source.y0};
        return diagonal({source: o, target: o});
      });

  // Transition links to their new position.
  link.transition()
      .duration(duration)
      .attr("d", diagonal);

  // Transition exiting nodes to the parent's new position.
  link.exit().transition()
      .duration(duration)
      .attr("d", function(d) {
        var o = {x: source.x, y: source.y};
        return diagonal({source: o, target: o});
      })
      .remove();

  // Stash the old positions for transition.
  nodes.forEach(function(d) {
    d.x0 = d.x;
    d.y0 = d.y;
  });
}

// Toggle children on click.
function click(d) {
    console.log(d);
  if (d.children) {
    d._children = d.children;
    d.children = null;
  } else {
    d.children = d._children;
    d._children = null;
  }
  update(d);
}
}
</script>
<h1>Upload Data</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
Only CSV
</p>

<div class="form">
<?php

$this->widget('ext.coco.CocoWidget'
        ,array(
            'id'=>'cocowidget1',  
            'onCompleted'=>'function(id,filename,jsoninfo){ getfile(jsoninfo); }',
            'onCancelled'=>'function(id,filename){ alert("cancelled"); }',
            'onMessage'=>'function(m){ alert(m); }',
            'allowedExtensions'=>array('jpeg','jpg','gif','png','csv'), // server-side mime-type validated
            'sizeLimit'=>2000000, // limit in server-side and in client-side
            'uploadDir' => 'assets/files', // coco will @mkdir it
            // this arguments are used to send a notification
            // on a specific class when a new file is uploaded,
            'receptorClassName'=>'application.models.MyModel',
            //'receptorClassName'=>'application.controllers.SiteController',
            'methodName'=>'myFileReceptor',
            'userdata'=>$model,
            // controls how many files must be uploaded
            'maxUploads'=>1, // defaults to -1 (unlimited)
            'maxUploadsReachMessage'=>'No more files allowed', // if empty, no message is shown
            // controls how many files the can select (not upload, for uploads see also: maxUploads)
            'multipleFileSelection'=>false, // true or false, defaults: true
            'buttonText'=>'Find & Upload',
            'dropFilesText'=>'Drop Files Here !',
            'htmlOptions'=>array('style'=>'width: 300px;'),
            //'defaultControllerName'=>'site',
            //'defaultActionName'=>'Coco',
        ));
    
?>
</div><!-- form -->

<?php endif; ?>