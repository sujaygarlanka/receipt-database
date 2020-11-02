// Running Google script can be found here: https://script.google.com/d/13DB7lLnhJPuWOsxN9qydYgx_jiTZX0STPfCo8iYgBsEJfPA0kdzZaKLH/edit?usp=sharing

function test() {

  var label = GmailApp.getUserLabelByName("Receipts Uploaded");
  if (label == null){
    GmailApp.createLabel('Receipts Uploaded');
    label = GmailApp.getUserLabelByName("Receipts Uploaded");
  }
  var label2 = GmailApp.getUserLabelByName("Receipts Not Uploaded");
  if (label2 == null){
    GmailApp.createLabel('Receipts Not Uploaded');
    label2 = GmailApp.getUserLabelByName("Receipts Not Uploaded");
  }
  var threads = GmailApp.search('{label:^smartlabel_receipt OR label:purchases OR subject:receipt OR subject:receipts OR from:receipt OR from:receipts} AND -label:receipts-uploaded AND -label:receipts-not-uploaded');
  //var threads = GmailApp.search('label:receipts not uploaded');
  var returnData = [];
  var length;
  if (threads.length < 10) {
    length = threads.length; // this is so that the for loop below doesn't run more times than the number of threads 
  }
  else {
    length = 10; // a cap of 50 prevents a timeout error from google apps scripts
  }
  for(var i=0; i<length; i++){
    var messageData = [];
    
    var message = threads[i].getMessages()[0]; // get first message
    var totalAmount;
    var test1 = total(message,0);
    var test2 = total(message,1);
    //Logger.log(message.getFrom());
    if ( test1 != 0){
      totalAmount = test1;
    }
    else if (test2 != 0) {
      totalAmount = test2;
    }
    else {
      totalAmount = 0; 
    }
    
    if (totalAmount != 0 && totalAmount != -1) {
      // Logger.log(message.getFrom());
      // Logger.log(message.getBody());
      //  Logger.log(totalAmount);
      //  Logger.log(message.getId());
      //  Logger.log("///");
      threads[i].addLabel(label); // adds message to Receipts Uploaded label
      //      debug = debug + 'array(\'';
      //      debug = debug + message.getId() + '\',\'';
      //      debug = debug + message.getFrom() + '\',';
      //      debug = debug + totalAmount + ',';
      //      debug = debug + message.getDate().getTime()/1000 + ',\'';
      //      debug = debug + message.getBody() + '\'),';
      
      messageData.push(message.getId());
      messageData.push(message.getFrom());
      messageData.push(totalAmount);
      messageData.push(message.getDate().getTime()/1000); // converts to seconds from milliseconds for php timestamp
      messageData.push(message.getBody());
      returnData.push(messageData);
    }
    else {
      threads[i].addLabel(label2);
      
    }
    
    threads[i].moveToArchive();
    
    
  }
  
  threads = GmailApp.search('{label:^smartlabel_receipt OR label:purchases OR subject:receipt OR subject:receipts OR from:receipt OR from:receipts} AND -label:receipts-uploaded AND -label:receipts-not-uploaded');
  returnData.push(threads.length);
  return returnData;
}

function total(message, type) {
  var body;
  if (type == 0){
    try { // plain body throws some random errors, so this avoids any potential problems 
      if (message.getPlainBody() == null) {
        return 0;
      }
      else {
        //Logger.log("check");
        body = message.getPlainBody().toLowerCase();
      } 
      
    }
    
    catch(error) {
      return 0;
    }
  }
  else {
    //Logger.log('run');
    try { // the try statement is here because some long files can take a while to decode and throw an execution time error
      body = decodeRawContent(message.getRawContent());
      
      if (typeof body == 'undefined'){
        body = message.getBody();
      }
      body = body.toLowerCase();
    }
    
    catch(err){
      
      return 0;
      
    }
  }
  
  var total;
  // var word = 'total'; // following three statements are to find total as an individual word instead of part of subtotal
  // var word = '\b' + word + '\b';
  var regex = /(\btotal\b)(?![^]*\b\1\b)/;
  var location = body.search(regex);
  // var location = body.lastIndexOf('total');
  
  if (location != -1){ // checks for the word 'total'
    body = body.substring(location);
    total = parseNumber(body);
  }
  else { // if nothing is found total is 0
    total = 0;
  }
  return total;
  
  
}

function parseNumber(body) { // gets the number from an appended document where other currency signs that may exist in the body are removed
  // so indexOf doesn't find the wrong number
  var currency = ['$','€','£','¥']; // checks for numbers multiple currencies
  var location;
  var total;
  var check = false;
  for (var j=0; j<currency.length; j++){
    if (body.indexOf(currency[j]) != -1){
      location = body.indexOf(currency[j]);
      check = true;
      break;
    }
  } 
  
  
  if (check) {
    body = body.substring(location + 1);
    for (var i = 0; i < body.length; i++) {
      var letter = body.charAt(i);
      if (letter != '.' && isNaN(Number(letter)) && letter != ',' ) {
        total = body.substring(0,i);
        total = total.replace(",", "");
        total = Number(total);
        return total;
      }
      
    }
    total = Number(body);
  }
  
  else {
    total = -1;
  }
  
  return total;
  
  
}

function decodeRawContent(message){
  var html;
  // Concatenate the chunks in the source
  message = message.replace(/\n|\r/g, "");
  
  // Extract the base64 string using regex
  var matches = message.match(/base64[a-zA-Z0-9\+\/=]+/g);
  
  for (var line in matches) {
    var base64 = matches[line].replace(/^base64/, "");
    try { // this ignores errors in decoding
      var decoded = Utilities.base64Decode(base64);
    }
    catch (error){
    }
    html += Utilities.newBlob(decoded).getDataAsString();
  }
  
  return html;
}


function numberReceipts (){
  var threads = GmailApp.search('{label:^smartlabel_receipt OR label:purchases OR subject:receipt OR subject:receipts OR from:receipt OR from:receipts} AND -label:receipts-uploaded AND -label:receipts-not-uploaded');
  Logger.log(threads.length);
}
