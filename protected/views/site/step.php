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

.node rect {
  cursor: pointer;
  fill: #fff;
  fill-opacity: .5;
  stroke: #3182bd;
  stroke-width: 1.5px;
}

.node text {
  font: 10px sans-serif;
  pointer-events: none;
}

path.link {
  fill: none;
  stroke: #9ecae1;
  stroke-width: 1.5px;
}

</style>
<body>
    <div id="chart">
        
        </div>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script>
function getfile(jsonfile){
 
var w = 960,
    h = 800,
    i = 0,
    barHeight = 20,
    barWidth = w * .8,
    duration = 400,
    root;

var tree = d3.layout.tree()
    .size([h, 100]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

var vis = d3.select("#chart").append("svg:svg")
    .attr("width", w)
    .attr("height", h)
  .append("svg:g")
    .attr("transform", "translate(20,30)");

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
flare.x0 = 0;
  flare.y0 = 0;
  update(root = flare);

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

//d3.select(self.frameElement).style("height", "800px");

function update(source) {

  // Compute the flattened node list. TODO use d3.layout.hierarchy.
  var nodes = tree.nodes(root);
  
  // Compute the "layout".
  nodes.forEach(function(n, i) {
    n.x = i * barHeight;
  });
  
  // Update the nodes…
  var node = vis.selectAll("g.node")
      .data(nodes, function(d) { return d.id || (d.id = ++i); });
  
  var nodeEnter = node.enter().append("svg:g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
      .style("opacity", 1e-6);

  // Enter any new nodes at the parent's previous position.
  nodeEnter.append("svg:rect")
      .attr("y", -barHeight / 2)
      .attr("height", barHeight)
      .attr("width", barWidth)
      .style("fill", color)
      .on("click", click);
  
  nodeEnter.append("svg:text")
      .attr("dy", 3.5)
      .attr("dx", 5.5)
      .text(function(d) { return d.Name; });
  
  // Transition nodes to their new position.
  nodeEnter.transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
      .style("opacity", 1);
  
  node.transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
      .style("opacity", 1)
    .select("rect")
      .style("fill", color);
  
  // Transition exiting nodes to the parent's new position.
  node.exit().transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
      .style("opacity", 1e-6)
      .remove();
  
  // Update the links…
  var link = vis.selectAll("path.link")
      .data(tree.links(nodes), function(d) { return d.target.id; });
  
  // Enter any new links at the parent's previous position.
  link.enter().insert("svg:path", "g")
      .attr("class", "link")
      .attr("d", function(d) {
        var o = {x: source.x0, y: source.y0};
        return diagonal({source: o, target: o});
      })
    .transition()
      .duration(duration)
      .attr("d", diagonal);
  
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
  if (d.children) {
    d._children = d.children;
    d.children = null;
  } else {
    d.children = d._children;
    d._children = null;
  }
  update(d);
}

function color(d) {
  return d._children ? "#3182bd" : d.children ? "#c6dbef" : "#fd8d3c";
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