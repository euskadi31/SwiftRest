#!/bin/bash

strtolower() {
    [ $# -eq 1 ] || return 1;
    local _str _cu _cl _x;
    _cu=(A B C D E F G H I J K L M N O P Q R S T U V W X Y Z);
    _cl=(a b c d e f g h i j k l m n o p q r s t u v w x y z);
    _str=$1;
    for ((_x=0;_x<${#_cl[*]};_x++)); do
        _str=${_str//${_cu[$_x]}/${_cl[$_x]}};
    done;
    echo $_str;
	return 0;
}

function call {
    format="$(strtolower "$2")"
	if [ "$1" == "PUT" -o "$1" == "POST" ]; then
		time curl -i -H "Accept: application/$format" -X $1 $3 -d "$4"
	else
		time curl -i -H "Accept: application/$format" -X $1 $3
	fi
}

if [ $# == 3 -o $# == 4 ]; then
    if [ "$2" == "XML" ]; then
		call $1 $2 $3 $4
    else
		call $1 $2 "$3?indent=true" $4
    fi	
else
    echo "$0 GET JSON http://api.domain.com/path/123 \"foot=bar\""
fi
