function draw_activity_pie(data){

	obj = pie_start(data);
	arc = pie_center(obj[0],obj[1],obj[2],obj[3],"Activity",data);
	legend(arc,data);

}

function draw_forum_pie(data){

	obj = pie_start(data);
	arc = pie_center(obj[0],obj[1],obj[2],obj[3],"Which fora are posted in?",data);
	legend_fora(arc,data);

}

function draw_status_pie(data){

	obj = pie_start(data);
	arc = pie_center(obj[0],obj[1],obj[2],obj[3],"Who do you @?",data);
	legend_status(arc,data);

}

function pie_start(data){

	var w = 700,                        //width
		h = 450,                            //height
		r = 200,                            //radius
		color = d3.scale.category10();     //builtin range of colors
     
    var vis = d3.select("#buddypress_activity_graph_ajax_response")
        .append("svg:svg")              //create the SVG element inside the <body>
        .data([data])                   //associate our data with the document
            .attr("width", w)           //set the width and height of our visualization (these will be attributes of the <svg> tag
            .attr("height", h)
        .append("svg:g")                //make a group to hold our pie chart
            .attr("transform", "translate(" + (r+100) + "," + r + ")")    //move the center of the pie chart from 0, 0 to radius, radius
 
	var center_group = vis.append("svg:g")
			.attr("transform", "translate(" + 0 + "," + -0 + ")");

	return Array(vis, center_group, r, color);

}

function pie_center(vis, center_group, r, color, label, data){

		// CENTER LABEL
	var pieLabel = center_group.append("svg:text")
			.attr("text-anchor", "middle")
			.text(label);
	
    var arc = d3.svg.arc()              //this will create <path> elements for us using arc data
        .outerRadius(r)
		.innerRadius(120);
 
    var pie = d3.layout.pie()           //this will create arc data for us given a list of values
        .value(function(d) { return d.value; });    //we must tell it out to access the value of each element in our data array
 
    var arcs = vis.selectAll("g.slice")     //this selects all <g> elements with class slice (there aren't any yet)
        .data(pie)                          //associate the generated pie data (an array of arcs, each having startAngle, endAngle and value properties) 
        .enter()                            //this will create <g> elements for every "extra" data element that should be associated with a selection. The result is creating a <g> for every object in the data array
            .append("svg:g");
 
        arcs.append("svg:path")
                .attr("fill", function(d, i) { return color(i); } ) //set the color for each slice to be chosen from the color function defined above
                .attr("d", arc);                                    //this creates the actual SVG path using the associated data (pie) with the arc drawing function
 
        arcs.append("svg:text")                                     //add a label to each slice
                .attr("transform", function(d) {                    //set the label's origin to the center of the arc
                return "translate(" + arc.centroid(d) + ")";        //this gives us a pair of coordinates like [50, 50]
            })
            .attr("text-anchor", "middle")                          //center the text on it's origin
            .text(function(d, i) { return data[i].label; });        //get the label from our original data array

	return arcs;
			
}
    
function legend(arcs, data){

	arcs.append("svg:text")
		.attr("x", function(d,i) { return 250; })
		.attr("y", function(d,i) { return -100+(15*i); })
		.text(function(d,i) { return data[i].value + " " + data[i].label + "(s)";});
 
}

function legend_fora(arcs, data){

	arcs.append("svg:text")
		.attr("x", function(d,i) { return 250; })
		.attr("y", function(d,i) { return -100+(15*i); })
		.text(function(d,i) { return data[i].value + " post(s) in " + data[i].label;});
 
}

function legend_status(arcs, data){

	arcs.append("svg:text")
		.attr("x", function(d,i) { return 250; })
		.attr("y", function(d,i) { return -100+(15*i); })
		.text(function(d,i) { return data[i].value + " updates(s) @ " + data[i].label;});
 
}

function tag_cloud(data){
	
   d3.layout.cloud().size([600, 600])
      .words(data.map(function(d) {
        return {text: d.label, size: d.value*20};
      }))
      .rotate(function() { return ~~(Math.random() * 2) * 90; })
      .fontSize(function(d) { return d.size; })
      .on("end", draw)
      .start();

  function draw(words) {
    d3.select("#buddypress_activity_graph_ajax_response").append("svg")
        .attr("width", 600)
        .attr("height", 600)
      .append("g")
        .attr("transform", "translate(150,150)")
      .selectAll("text")
        .data(words)
      .enter().append("text")
        .style("font-size", function(d) { return d.size + "px"; })
        .style("font-face", "Georgia")
        .style("color", "#aaa")
        .attr("text-anchor", "middle")
        .attr("transform", function(d) {
          return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.text; });
  }

}

function bar_chart(data){

 var chart = d3.select("#buddypress_activity_graph_ajax_response").append("svg")
     .attr("class", "chart")
     .attr("width", 420)
     .attr("height", 20 * data.length);

 var x = d3.scale.linear()
     .domain([0, 420])
     .range([0, data.length]);

 chart.selectAll("rect")
     .data(data)
   .enter().append("rect")
     .attr("y", function(d, i) { return i * 20; })
     .attr("width", function(d, i) { return d.value })
     .attr("height", 20);

 chart.selectAll("text")
     .data(data)
   .enter().append("text")
     .attr("x", function(d, i) { return 200; } )
     .attr("y", function(d, i) { return (i * 20)+8 })
     .attr("dx", -3) // padding-right
     .attr("dy", ".35em") // vertical-align: middle
     .attr("text-anchor", "end") // text-align: right
     .text(function(d,i) { return data[i].value + " actions on " + data[i].label;});

}