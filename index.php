<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Tree</title>
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
</head>
<body>
	<button>create root</button>
	<div id="jstree"></div>	
	

	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>	
	<script>
	$(function () { 
		// Create root
		$('button').click(function () {
			$('#jstree').jstree().create_node(
				'#' ,  
				{ "id" : "demo_root", "text" : "Main category", "type" : "root" },
				"last", 
				function(node){
	    			console.log(node)
				});
		});

		// Create node event
		$('#jstree').on("create_node.jstree", function (node, parent, position) {
		    $.post("/api.php/category/create",
			{
				name: parent.node.text,
				parent_id: parent.node.parent,
				position: parent.position,
			},
			function(data, status){
				console.log("Data: " + data + "\nStatus: " + status);
			    $('#jstree').jstree(true).set_id(parent.node,data.id);
			});
		});

		// Rename name event
		$('#jstree').on("rename_node.jstree", function (node, text, old) {
		    let data = {
				name: text.node.text,
				parent_id: text.node.parent
			};

			$.ajax({
			    type: 'PUT',
			    url: '/api.php/category/update/' + text.node.id,
			    contentType: 'application/json',
			    data: JSON.stringify(data), // access in body
			}).done(function () {
				$('#jstree').jstree(true).set_id(text.node, text.node.id);
			}).fail(function (msg) {
			    console.log('FAIL');
			}).always(function (msg) {
			    console.log('ALWAYS');
			});
		});

		// Drag & Drop Event
		$('#jstree').on("move_node.jstree", function (node, parent, position) {
			let data = {
				name: parent.node.text,
				parent_id: parent.node.parent
			};

			$.ajax({
			    type: 'PUT',
			    url: '/api.php/category/update/' + parent.node.id,
			    contentType: 'application/json',
			    data: JSON.stringify(data), // access in body
			}).done(function () {
			    console.log('SUCCESS');
			}).fail(function (msg) {
			    console.log('FAIL');
			}).always(function (msg) {
			    console.log('ALWAYS');
			});
		});

		// Init tree
		$('#jstree').jstree({
		  "core" : {
			"animation" : 0,
			"check_callback" : true,
			"themes" : { "stripes" : true },
			'data' : {
			  'url' : function (node) {
				return node.id === '#' ?
				  '/api.php/category/get' : '/api.php/category/get';
			  },
			  'data' : function (node) {
				return { 'parent_id' : node.id };
			  }
			}
		  },
		  "types" : {
			"#" : {
			  "max_children" : -1,
			  "max_depth" : -1,
			  "valid_children" : ["root"]
			},
			"root" : {
			  "icon" : "/assets/images/tree_icon.png",
			  "valid_children" : ["default"]
			},
			"default" : {
			  "valid_children" : ["default","file"]
			},
			"file" : {
			  "icon" : "glyphicon glyphicon-file",
			  "valid_children" : []
			}
		  },
		  "plugins" : [
			"contextmenu", "dnd", "search",
			"state", "types", "wholerow"
		  ],
		  "contextmenu": {items: customMenu}
		});
	});

	// usefull http://jsfiddle.net/7xpbf/1/
	function customMenu(node) {
	    // The default set of all items
	    var items = {
	        createItem: { // The "create" menu item
	            label: "Create",
	            action: function (data) {
	                var inst = $.jstree.reference(data.reference),
	                    obj = inst.get_node(data.reference);
	                inst.create_node(obj, {}, "last", function (new_node) {
	                    new_node.data = {file: true};
	                    setTimeout(function () { inst.edit(new_node); },0);
	                });
	            }
	        },
	        renameItem: { // The "rename" menu item
	            label: "Rename",
	            action: function (data) {
	                var inst = $.jstree.reference(data.reference),
	                    obj = inst.get_node(data.reference);
	                inst.edit(obj);
	            }
	        }
	    };
	    
	    return items;
	}

	
	</script>
</body>
</html>
