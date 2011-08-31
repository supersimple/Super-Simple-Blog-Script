// returns true if the string only contains characters A-Z or a-z
function isAlpha(str){
		var re = /[^a-zA-Z]/g
		if (re.test(str)) return true;
		return false;
}
	
// returns true if the string only contains characters A-Z or a-z or 0-9
function isAlphaNumeric(str){
		var re = /[^a-zA-Z0-9-'\s]/g
		if (re.test(str)) return false;
		return true;
}

function isNumeric(str){
		var re = /^\d+$/
		if (re.test(str)) return true;
		return false;
}

function isInteger(str){
		var re = /^[\-\+]?[\d]+$/
		if (re.test(str)) return true;
		return false;
}

function isEmpty(str){
		if(str.length == 0 || str == null){
			return true;
		}else{
			return false;
		}
}
	
function stripWhitespace(str, replacement){
	if (replacement == null) replacement = '';
	var result = str;
	var re = /\s/g
	if(str.search(re) != -1){
		result = str.replace(re, replacement);
	}
	return result;
}
