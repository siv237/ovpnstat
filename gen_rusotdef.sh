wget -c -q -O - http://www.rossvyaz.ru/docs/num/DEF-9x.html | grep "^<tr>" | sed -e 's/<\/td>//g' -e 's/<tr>//g' -e 's/<\/tr>//g' -e 's/[\t]//g' -e 's/^<td>//g' -e 's/<td>/;/g' | iconv -c -f WINDOWS-1251 -t UTF8|awk -F ";" '{print $1 $2 ";" $1 $3 ";" $5 ";" $6 }' > rusotdef.csv
