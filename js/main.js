//=====================================
// Build Worksheet function
//=====================================
function buildWorksheet(data){
  var html = "";
  for (var i = 0; i < data.length; i++) {
    switch (data[i]['name']) {
      case 'title':
      html += "<h1>" + data[i]['value'] + "</h1>";
      break;
      case 'subtitle':
      html += "<h3>" + data[i]['value'] + "</h3>";
      break;
      case 'text':
      html += "<p>" + data[i]['value'] + "</p>";
      break;
      case 'code':
      html += "<p>" + data[i]['value'] + "</p>";
      break;
    }
  }
  console.log(html);
  return html;
}


$(document).ready(function(){

  //=====================================
  // Initialize ace code editors
  //=====================================
  if ($("#schemaEditor").length > 0){
    var schemaEditor = ace.edit("schemaEditor");
    schemaEditor.session.setMode("ace/mode/sql");

    var queryEditor = ace.edit("queryEditor");
    queryEditor.session.setMode("ace/mode/sql");
  }

  if ($("#wSchemaEditor").length > 0){
    var schemaEditor = ace.edit("wSchemaEditor", {
        mode: "ace/mode/sql",
        maxLines: 10,
        minLines: 2
    });

    var queryEditor = ace.edit("wQueryEditor", {
        mode: "ace/mode/sql",
        maxLines: 10,
        minLines: 2
    });
  }






  var readOnlys = {};
  $('.readOnly').each(function(index, element){
    var id = 'readOnly' + index;
    $(element).attr('id', id);
    readOnlys[index] = ace.edit(id, {
      mode: "ace/mode/sql",
      autoScrollEditorIntoView: true,
      wrap: true
    });
    readOnlys[index].setReadOnly(true);
    readOnlys[index].session.setValue($('#' + id).attr('value'));
  });


  //=====================================
  // Build Schema Button
  //=====================================
  $('#schemaButton').click(function(){
    if (schemaEditor.getValue().length > 0) {
      var data = "schema=";
      data += schemaEditor.getValue();
      console.log(data);
      // Send reference to button clicked
      data += '&button=schema';
      $.post('database.php', data, function(returnedData) {
        console.log(returnedData);
        $('#query').removeAttr("disabled");
        $('#query').removeAttr("placeholder");
        //$('#schemaButton').text("Rebuild");
        if (returnedData.length > 0) {
          console.log(returnedData);
          $('#messages').append('<div id="builtMessage" class="alert alert-danger alert-dismissible fade show" role="alert">'+ returnedData +'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        }else{
          $('#messages').append('<div id="builtMessage" class="alert alert-success alert-dismissible fade show" role="alert">Schema Built!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        }
        setTimeout(function(){ $('#builtMessage').alert('close'); }, 3500);
      });
    }else{
      $('#messages').append('<div id="builtMessage" class="alert alert-danger alert-dismissible fade show" role="alert">Schema empty<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    }
  });


  //=====================================
  // Run SQL Button
  //=====================================
  $('#queryButton').click(function(){
    $('#schemaButton').trigger('click');
    if (queryEditor.getValue().length > 0) {
      // Post schema editor data, query editor data and which button was clicked to PHP
      var data = "query=";
      data += queryEditor.getValue();
      data += "&schema=";
      data += schemaEditor.getValue();
      data += '&button=query';
      $.post('database.php', data, function(returnedData) {
        var dataObject;
        var x;
        var txt = "";
        console.log(returnedData);
        // Data is returned as JSON file
        // JSON is converted to array of arrays
        dataObject = JSON.parse(returnedData);
        // Check if an error occured by reading first element of array then deleting it
        if (dataObject.shift() === false) {
          for (var error in dataObject) {
            $('#messages').append('<div id="queryMessage" class="alert alert-danger alert-dismissible fade show" role="alert">' + dataObject[error] + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
          }
        }else{
          for (var table in dataObject) {
            txt += "<table class='table'><tbody>";
            txt += "<thead>";
            // Assign table headers
            for (var i = 0; i < dataObject[table][0].length; i++) {
              txt += "<th>" + dataObject[table][0][i] + "</th>";
            }
            // Create rows
            for (var row in dataObject[table]) {
              txt += "<tr>";
              // Add data to row cells
              for (var cell in dataObject[table][row]) {
                // Only adds data if not index 0 (column headers)
                // Stops headers being shown in table body
                if (row != 0) {
                  txt += "<td>" + dataObject[table][row][cell] + "</td>";
                }
              }
              txt += "</tr>";
            }
            txt += "</tbody></table>";
          }
          $('#messages').append('<div id="queryMessage" class="alert alert-success alert-dismissible fade show" role="alert">Query Executed!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
          // Remove alert after 3.5 seconds
          setTimeout(function(){ $('#queryMessage').alert('close'); }, 3500);
        }
        console.log(dataObject);

        // Table is sent to output div
        $('#output').html(txt);
        // Add a success alert to messages div

      });
    }else{
      $('#messages').append('<div id="builtMessage" class="alert alert-danger alert-dismissible fade show" role="alert">SQL empty<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    }
  });


  //=====================================
  // Load Example Buttons
  //=====================================
  $('#exampleButton1').click(function(){
    schemaEditor.session.setValue("CREATE TABLE IF NOT EXISTS `docs` (\n  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,\n  `rev` int(3) unsigned NOT NULL,\n  `content` varchar(200) NOT NULL,\n  PRIMARY KEY (`id`,`rev`)\n) DEFAULT CHARSET=utf8;\nINSERT INTO `docs` (`rev`, `content`) VALUES\n  ('1', 'The earth is flat'),\n  ('1', 'One hundred angels can dance on the head of a pin'),\n  ('2', 'The earth is flat and rests on a horn'),\n  ('3', 'The earth is like a ball.');");
    queryEditor.session.setValue("SELECT * FROM docs;");
  });

  $('#exampleButton2').click(function(){
    schemaEditor.session.setValue("CREATE TABLE IF NOT EXISTS customers (\nid int(6) unsigned NOT NULL AUTO_INCREMENT,\nname varchar(200) NOT NULL,\nPRIMARY KEY (id)\n) DEFAULT CHARSET=utf8;\nINSERT INTO customers (name) VALUES\n('Ben Rose'),\n('David Rose'),\n('Lisa Rose');\n\nCREATE TABLE IF NOT EXISTS orders (\nid int(6) unsigned NOT NULL AUTO_INCREMENT,\ncustomer_id int(3) unsigned NOT NULL,\nproduct varchar(200) NOT NULL,\nPRIMARY KEY (id)\n) DEFAULT CHARSET=utf8;\nINSERT INTO orders (customer_id, product) VALUES\n('2', 'Sandwich'),\n('3', 'Soup'),\n('1', 'Walkers Crisps'),\n('3', 'Burger'),\n('2', 'Salad');");
    queryEditor.session.setValue("SELECT orders.id, customers.name, orders.product\nFROM orders\nINNER JOIN customers ON orders.customer_id=customers.id;");
  });


  //=====================================
  // Enter worksheet filename form
  //=====================================
  $('#modalSubmit').click(function(){
    event.preventDefault();
    $('#submitButton').trigger('click');
  });

  //=====================================
  // Build dynamic create worksheet form
  //=====================================
  var index = 0;
  $('#addTitle').click(function(){
    $('#dynamicTable').append('<tr class="row'+index+'"><td class="tableTitle"><h5>Title</h5></td></tr><tr class="row'+index+'"><td><input type="text" name="title" placeholder="Title" class="form-control" /></td><td><button type="button" name="remove" id="'+index+'" class="btn btn-danger btn_remove">X</button></td></tr>');
    index++;
  });
  $('#addSubtitle').click(function(){
    $('#dynamicTable').append('<tr class="row'+index+'"><td class="tableTitle"><h5>Subtitle</h5></td></tr><tr class="row'+index+'"><td><input type="text" name="subtitle" placeholder="Subtitle" class="form-control" /></td><td><button type="button" name="remove" id="'+index+'" class="btn btn-danger btn_remove">X</button></td></tr>');
    index++;
  });
  $('#addText').click(function(){
    $('#dynamicTable').append('<tr class="row'+index+'"><td class="tableTitle"><h5>Text</h5></td></tr><tr class="row'+index+'"><td><textarea type="text" name="text" placeholder="Text" class="form-control" /></td><td><button type="button" name="remove" id="'+index+'" class="btn btn-danger btn_remove">X</button></td></tr>');
    index++;
  });
  $('#addCode').click(function(){
    $('#dynamicTable').append('<tr class="row'+index+'"><td class="tableTitle"><h5>Read-Only Code</h5></td></tr><tr class="row'+index+'"><td><textarea type="text" name="code" placeholder="Code" class="form-control" /></td><td class="removeBut"><button type="button" name="remove" id="'+index+'" class="btn btn-danger btn_remove">X</button></td></tr>');
    index++;
  });


  //=====================================
  // Remove element from dynamic worksheet form
  //=====================================
  $(document).on('click', '.btn_remove', function(){
    var id = $(this).attr("id");
    $('.row'+id+'').remove();
  });


  //=====================================
  // Submit dynamic worksheet form
  //=====================================
  $('#worksheet').submit(function(){
    event.preventDefault();
    var form = $(this);
    var array = form.serializeArray();
    console.log(array);

    var worksheetName = $('#filename').html();

    var data = "data=";
    var file = {
      name: "file",
      value: worksheetName
    }
    array.unshift(file);

    data += JSON.stringify(array);

    console.log(data);

    $.post('create_worksheet.php', data, function(returnedData) {
      console.log(returnedData);
      window.location.replace('worksheet.php?w=' + worksheetName);
    });
  });

});
