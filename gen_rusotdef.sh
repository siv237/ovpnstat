wget -c -q -O - http://www.rossvyaz.ru/opendata/7710549038-Rosnumbase/Kody_DEF-9kh.csv | iconv -c -f WINDOWS-1251 -t UTF8 |awk -F ";" '{print $1 $2 ";" $1 $3 ";" $5 ";" $6 }' > rusotdef.csv
