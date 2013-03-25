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

circle.node {
  cursor: pointer;
  stroke: #000;
  stroke-width: .5px;
}

line.link {
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
    
var w = 1280,
    h = 800,
    node,
    link,
    root;

var force = d3.layout.force()
    .on("tick", tick)
    .charge(function(d) { return d._children ? -d.size / 100 : -30; })
    .linkDistance(function(d) { return d.target._children ? 80 : 30; })
    .size([w, h - 160]);

var vis = d3.select("#chart").append("svg:svg")
    .attr("width", w)
    .attr("height", h);


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
  root.fixed = true;
  root.x = w / 2;
  root.y = h / 2 - 80;
  update();
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

function update() {
    var nodes = flatten(root),
      links = d3.layout.tree().links(nodes);

  // Restart the force layout.
  force
      .nodes(nodes)
      .links(links)
      .start();

  // Update the links…
  link = vis.selectAll("line.link")
      .data(links, function(d) { return d.target.id; });

  // Enter any new links.
  link.enter().insert("svg:line", ".node")
      .attr("class", "link")
      .attr("x1", function(d) { return d.source.x; })
      .attr("y1", function(d) { return d.source.y; })
      .attr("x2", function(d) { return d.target.x; })
      .attr("y2", function(d) { return d.target.y; });

  // Exit any old links.
  link.exit().remove();

  // Update the nodes…
  node = vis.selectAll("circle.node")
      .data(nodes, function(d) { return d.id; })
      .style("fill", color);

  node.transition()
      .attr("r", function(d) { return d.children ? 4.5 : Math.sqrt(d.Rating) / 10; });

  // Enter any new nodes.
  node.enter().append("svg:circle")
      .attr("class", "node")
      .attr("cx", function(d) { return d.x; })
      .attr("cy", function(d) { return d.y; })
      .attr("r", function(d) { return d.children ? 4.5 : Math.sqrt(d.Rating) / 10; })
      .style("fill", color)
      .on("click", click)
      .call(force.drag);

  // Exit any old nodes.
  node.exit().remove();
}

// Toggle children on click.
function tick() {
  link.attr("x1", function(d) { return d.source.x; })
      .attr("y1", function(d) { return d.source.y; })
      .attr("x2", function(d) { return d.target.x; })
      .attr("y2", function(d) { return d.target.y; });

  node.attr("cx", function(d) { return d.x; })
      .attr("cy", function(d) { return d.y; });
}

// Color leaf nodes orange, and packages white or blue.
function color(d) {
  return d._children ? "#3182bd" : d.children ? "#c6dbef" : "#fd8d3c";
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
  update();
}

// Returns a list of all nodes under the root.
function flatten(root) {
  var nodes = [], i = 0;

  function recurse(node) {
    if (node.children) node.Rating = node.children.reduce(function(p, v) { return p + recurse(v); }, 0);
    if (!node.id) node.id = ++i;
    nodes.push(node);
    return node.Rating;
  }

  root.size = recurse(root);
  return nodes;
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
If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
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