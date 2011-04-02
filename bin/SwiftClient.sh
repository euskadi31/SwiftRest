#!/bin/bash
if [ $# == 3 ]
then
    if [ "$2" == "XML" ]
    then
        time curl -H "Accept: application/xml" -X $1 $3 -D headers.txt
    else
        time curl -H "Accept: application/json" -X $1 $3?indent=true -D headers.txt
    fi
else
    echo "./" + $0 + " GET JSON http://api.domain.com/path/123"
fi
echo ""
if [ -f headers.txt ]
then
    cat headers.txt
    rm -rf headers.txt
fi
