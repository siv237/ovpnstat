echo '"prepend","prefix","match pattern","callerid"'
cat rusotdef.csv |grep Хабаровск|awk -F ";" '{print "\""8$1" \""$3"\""}'|\
	sed  's/0 /X\",,/g'| \
	sed  's/0X\",,/XX\",,/g'| \
	sed  's/0XX\",,/XXX\",,/g'| \
	sed  's/0XXX\",,/XXXX\",,/g'| \
	sed  's/0XXXX\",,/XXXXX\",,/g'| \
	sed  's/0XXXXX\",,/XXXXXX\",,/g'| \
	sed  's/0XXXXXX\",,/XXXXXXX\",,/g'| \
	grep -E "Вымпел-Коммуникации"|awk -F ',' '{print ",,"$1","}'
