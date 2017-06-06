
function format_bytes(size){
    var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for (var i = 0; size >= 1024 && i < 5; i++) size /= 1024;
    //return toDecimal2(size,2) + ' ' + units[i];
	document.write(toDecimal2(size,2) + ' ' + units[i]);
}

function toDecimal2(x) {    
    var f = Math.round(x*100)/100;    
    var s = f.toString();    
    return s;    
}   
