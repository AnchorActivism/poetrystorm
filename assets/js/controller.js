console.log('controller.js script open');

/*================
	vars
================*/

var disp_poem;		//to be rendered to page
var full_poem;		//poem thread with first tier alternatives
var origin = {		//
					"id": origin_id,		//database id of the origin line
					"loc" :{			//tracks which line to render to within full_poem table (assoc. array)
						"row":undefined,		//reply index 		(row)
						"col":undefined}		//alternative index (col)
					}


/*================
	ajax
================*/

	function ajax_client(url, json) {

		var cct = Cookies.get('csrf_cookie_name');

		return $.ajax({
			url: url,
			method: 'POST',
			data: {'json': json},
			dataType: 'json'
		});
	}


/*================
	Controller
================*/


	function ini()
	{

																		console.log('INDEX');

		if (origin.id == 'null')
		{//origin.id is not set

																		console.log("origin.id: "+origin.id);

			$.when( //get first lines of top poems 
				get_top_entries() )
			.then(function(data)
			{
				full_poem = data;
				disp_poem = data;

				render(disp_poem);

			});
		}
		else
		{//origin.id line has been set
																		console.log("origin.id: "+origin.id);

			$.when( //retrieve parents, replies, top alternatives
				get_origin_parents(origin.id),
				get_origin(origin.id), 
				get_origin_replies(origin.id) 
				)
			.done(function( r_parents, r_origin, r_replies ) 
			{

				var p=r_parents[0].slice();
				var o=r_origin[0].slice();
				var r=r_replies[0].slice();
																		console.log(r);
				
				//define globals

				disp_poem = append_lines(p, [o]);
																		console.log('disp_poem:');
																		console.log(disp_poem);
																		//console.log(append_lines(disp_poem.slice(), r));

				$.when(
					get_thread_alternatives( append_lines(disp_poem.slice(), r) )
					)
				.done(function(full_poem_result) 
				{
					full_poem = full_poem_result;
				
																		console.log('full_poem:');
																		console.log(full_poem);

					track_origin(full_poem, origin.id);

					console.log(origin);

					//render disp_poem
					render(disp_poem);

				});


				//render(make_disp_poem(full_poem.slice(),origin.id));


			});
		}
	}



	/*================
		Model
	================*/
	
	/*ini*/
	function get_top_entries() {
		//retrieves first line of all top poems
		return ajax_client(base_url+'ajax_port/get_top_entries', 'null');
	}

	function get_line_by_id(id) {
		return ajax_client(base_url+'ajax_port/get_line_by_id/'+id, 'null');
	}

	function get_origin_parents(origin_id) {
		//gets the origin line and all preceding lines in the poem
		return ajax_client(base_url+'ajax_port/get_origin_parents/'+origin_id, 'null');
	}

	function get_origin(origin_id) {
		return ajax_client(base_url+'ajax_port/get_origin/'+origin_id, 'null');
	}

	function get_origin_replies(origin_id) {
		//gets all replying lines, DOES NOT get origin
		console.log(origin_id);
		return ajax_client(base_url+'ajax_port/get_origin_replies/'+origin_id, 'null');
	}

	function get_thread_alternatives(thread) {
		//takes thread array and adds alternative lines
		//prototype:
		// thread = [			==>	thread = [
		//				[{}],					[{},[{},{},{}]],
		//				[{}],					[{},[{},{}]],
		//				[{}]					[{},[{},{}]]
		//			];						  ];
		return ajax_client(base_url+'ajax_port/get_thread_alternatives/', thread);
	}

	function get_poem_tree(top_line_id) {
		//uses the first line to collect all subsequent threads
		var the_poem_data = ajax_client(base_url+'ajax_port/get_poem_tree/'+top_line_id, 'null');
		console.table(the_poem_data[0]);
		//return "hhhkjhkkj";
		return ajax_client(base_url+'ajax_port/get_poem_tree/'+top_line, 'null');
	}

	function append_lines(thread, lines) {
		//var copy = thread.slice();

		for (var i = 0; i < lines.length; i++) 
		{
			thread.push(lines[i]);
		}
																		//console.log('thread:');
																		//console.log(thread);
		return thread;
	}

	function send_line_inner(data) {
		console.log('send_line_inner');
		return ajax_client(base_url+'ajax_port/insert_line/', data);
	}

	function send_line(line, reply_to) {

		var data = [line, reply_to];
		console.log(data);

		if (line.length>0) {
			$.when( send_line_inner(data) )
			.done(function(new_id)
			{
				origin.id = new_id;
				ini();
																		console.log('send_line: 	'+new_id);
			});
		}
	}


	/*================
		UI
	================*/

	/*function next(id) {
		//get next line with parent id
		for (var i = 0; i < full_poem.length; i++) 
		{
			for (var ii = 0; ii < full_poem[i].length; ii++)
			if (full_poem[i][ii]['reply_to'] == id)
			{
				return full_poem[i][ii];
			}
		}

	}*/

	function next(key, full_poem_loc=undefined) {
		//get next line using array key

		if (full_poem_loc==undefined) {
			var full_poem_loc = full_poem;
			console.log('full_poem_loc is UNDEFINED')
		}
		else
		{
			console.log('full_poem_loc is DEFINED');
		}

		var new_key = full_poem_loc[parseInt(key)+1];

																		//console.log(parseInt(key)+1)

																		//console.log(full_poem[parseInt(key)+1])

		if (new_key !== undefined) {
			console.log('next() keys');
			console.log('index '+(parseInt(key)+1));
			console.log(full_poem_loc);

			var new_full_poem=full_poem_loc.slice();

			console.log(new_full_poem);

			console.log(new_full_poem[parseInt(key)+1]);
			return full_poem_loc[parseInt(key)+1]['0'];
		}
		else
		{
			return false;
		}
	}

	function track_line(full_poem, line_id) {

		for (var i = 0; i < full_poem.length; i++) 
		//row within full_poem (reply)
		{
			for (var ii = 0; ii < full_poem[i].length; ii++) 
			//col within full_poem (reply alternative)
			{
				var msg = 'FALSE';

				if(full_poem[i][ii].id == line_id) {
					var row = i;
					var col = ii;
					var id = line_id;
					msg = 'TRUE';
					break;
				}
																		//console.log('('+i+','+ii+') = '+msg);
			};
		};

		return {		//
					"id": id,		//database id of the origin line
					"loc" :{			//tracks which line to render to within full_poem table (assoc. array)
						"row":row,		//reply index 		(row)
						"col":col}		//alternative index (col)
					}

	}

	function track_origin(full_poem, origin_id) {
		//updates origin.loc

																		//console.log('***track_origin()***')
																		//console.log(full_poem);
																		//console.log(origin_id);

		origin = track_line(full_poem, origin_id);

		//origin.id 		= id;
		//origin.loc.row 	= parseInt(row);
		//origin.loc.col 	= parseInt(col);

																		//console.log(origin);
	}


	function update_full_poem_replies(full_poem, row, line_id) {
																	console.log('update_full_poem_replies()');
																	console.log('line_id: '+line_id);
																	console.log('row: '+row);

		$.when(
			get_origin_replies(line_id))
		.done(function(r_replies)
		{
																		console.log(r_replies);
			$.when(
				get_thread_alternatives(r_replies))
			.done(function(reply_tree)
			{

				full_poem.splice(row+1);
				append_lines(full_poem, reply_tree);
				
																		console.log(reply_tree);
																		console.log('full poem:');
																		console.log(full_poem);

				return full_poem;
			});															
		});

	}


	function make_disp_poem(full_poem, origin_id) {
		//Returns disp_poem
		//Takes the origin_id and constructs parent lines

		console.log('track_origin()')
		console.log('make_disp_poem() -> origin_id: '+origin_id);

		track_origin(full_poem, origin_id);
		var row = origin.loc.row;
		var col = origin.loc.col;

		//find which row the origin_id line is in
		for (var i = 0; i < full_poem.length; i++) 
		{
			for (var ii = 0; ii < full_poem[i].length; ii++) 
			{
				if(full_poem[i][ii].id == origin_id) {
					var row = i;
					break;
				}
			};
		};

		if (row !== undefined) {

			var disp_poem = [];
			var target_id = origin_id;
			//construct disp_poem array
			for (var i = row; i >= 0; i--) 
			{
				for (var ii = 0; ii < full_poem[i].length; ii++) 
				{
				 	if(full_poem[i][ii].id == target_id)
				 	{
				 		//wrap line in array
				 		line = [full_poem[i][ii]];
				 		//add to array
				 		disp_poem.unshift(line);
				 		//set next target as reply_to of current
				 		target_id = line[0].reply_to;
				 	}
				};
			};
			console.table(disp_poem);
			console.table(full_poem);

			//meta
			history.pushState({}, "Poetry Storm", base_url+"experience/"+origin_id);
			$('form.writeon').attr('action', base_url+"experience/"+origin_id);
			$('form.writeon hidden[name=reply_to]').attr('value', origin_id);

			return disp_poem;
		}
		return false;
	}

	function get_line_by_id_local(id, arr) {

		for (var i = 0; i < arr.length; i++)
		{
			if (id == arr[i][0].id) {
				return arr[i][0];
			}
		}

	}

	/*================
		UI
	================*/



	function render(arr) {
		//prints the lines into the DOM

		console.log('**render()**')


		if (arr !== undefined) {

			var line;
			var key;

			var row;// = origin.loc.row;
			var col;// = origin.loc.col;

			var next_id;
			var prev_id;


			//clear poetry div
			$('div.poetry').html('');
		
			//print poetry
			for (var i = 0; i < arr.length; i++) {
				 line = arr[i][0];

				//render the origin line
				$('div.poetry').append('<p><a href="'+line.id+'" id="'+line.id+'" title="Focus this line." class="line">'+line.line+'</a></p>');
				key = i;


				if (i == arr.length -1) {
				//if it's the last line in disp_poem (then give it arrows)

					//track current line in full_poem
					line_loc = track_line(full_poem, line.id);
					row = line_loc.loc.row;
					col = line_loc.loc.col;

					if (full_poem[row].length > 1) {
					//if this row has alternatives (multiple columns) (give it arrows)

						if (col == full_poem[row].length - 1) {
						//if this is the last column in the row (last alternative)

							prev_id = full_poem[row][col-1].id;
							next_id = full_poem[row][0].id;
						}
						else if (col == 0) {
						//if this is the first column

							prev_id = full_poem[row][full_poem[row].length-1].id; // haha cheeky
							next_id = full_poem[row][col+1].id;
						}
						else
						{
						//if we're in the middle somewhere
							prev_id = full_poem[row][col-1].id;
							next_id = full_poem[row][col+1].id;
						}

						//output arrows

						//right arrow
						$('div.poetry p:last-child').append('<a href="'+next_id+'" title="Right" id="right" class="arrow">&raquo;</a>');

						//left arrow
						if (full_poem[row].length > 2) {
						//if there are more than 2 cols (so arrows don't link to same)
						
							$('div.poetry p:last-child').prepend('<a href="'+prev_id+'" title="Left" id="left" class="arrow">&laquo;</a>');
						}
					}
				}
			}

/*
			//update full_poem with new replies if necessary
			if (col>0) {
//				update_full_poem_replies(full_poem, row, line.id);


				$.when(
					update_full_poem_replies(full_poem, origin.loc.row, origin.id))
				.done(function(full_poem_loc)
				{
					console.log('FULL POEM REPLIES UPDATED');
					console.log('full_poem');
					console.log(full_poem_loc);

					//down arrow: link to next line if there is one
					if (next(row, full_poem_loc)) {

						console.log('*** *** next(row) *** *** :::COL>0')
						console.log(next(row, full_poem_loc));

						$('div.poetry').append('<p><a href="'+next(row,full_poem_loc).id+'" title="Next" id="next" class="arrow">See Next Line</a></p>');
					
					}
					
					set_actions();

				});

					//$when.done()???

			}
			else 
			{*/

				//down arrow: link to next line if there is one
				if (next(row)) {

					console.log('*** *** next(row) *** *** :::COL<==0')
					console.log(next(row));

					$('div.poetry').append('<p><a href="'+next(row).id+'" title="Next" id="next" class="arrow">See Next Line</a></p>');
				
				}

				$('#reply_to_hidden').val(origin.id);

				set_actions();
			//}
		}
		else
		{
		console.log('arr is UNDEFINED');
		}
		//return false;
	}

	function set_actions() {
		
		//when the user clicks on of the next buttons (down, left, right arrows)
		//for alternatives OR replies
		$('a.arrow').click(function (event) {
			event.preventDefault();

			track_origin(full_poem, $(this).attr('href'));

			ini();

			//render(make_disp_poem(full_poem,origin.id));
			$('html, body').animate({scrollTop: $('body').height()}, 800);
			$('div.poetry p.line:last-child').addClass('appear');

		});
		

		//when the user click a line
		$('div.poetry a.line').click(function (event) {
			event.preventDefault();
			//console.log($(this).attr('href'));

			track_origin(full_poem, $(this).attr('id'));
			
			render(make_disp_poem(full_poem,origin.id));

		});


		$('form.writeon input#submit').unbind();
		$('form.writeon input#submit').click(function (event) {
			event.preventDefault();
																		console.log('you pressed a button!!');
			var line = $('textarea#line').val();
			var reply_to = $('#reply_to_hidden').val();
																		console.log(line);
			send_line(line, reply_to);
		});
	}


$(function() {

//open_uri_tags(uri_tags);

 ini();

});