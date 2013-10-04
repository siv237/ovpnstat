wget -c -q -O - http://www.rossvyaz.ru/opendata/7710549038-Rosnumbase/Kody_DEF-9kh.csv | iconv -c -f WINDOWS-1251 -t UTF8 |awk -F ";" '{printf  $1 "%07d",$2 }{printf ";" $1 "%07d",$3}{print ";" $5 ";" $6}' | sed '1d '  > def.csv
wget -c -q -O - http://www.rossvyaz.ru/opendata/7710549038-Rosnumbase/Kody_ABC-3kh.csv | iconv -c -f WINDOWS-1251 -t UTF8 |awk -F ";" '{printf  $1 "%07d",$2 }{printf ";" $1 "%07d",$3}{print ";" $5 ";" $6}' | sed '1d '  >> def.csv
wget -c -q -O - http://www.rossvyaz.ru/opendata/7710549038-Rosnumbase/Kody_ABC-4kh.csv | iconv -c -f WINDOWS-1251 -t UTF8 |awk -F ";" '{printf  $1 "%07d",$2 }{printf ";" $1 "%07d",$3}{print ";" $5 ";" $6}' | sed '1d '  >> def.csv
wget -c -q -O - http://www.rossvyaz.ru/opendata/7710549038-Rosnumbase/Kody_ABC-8kh.csv | iconv -c -f WINDOWS-1251 -t UTF8 |awk -F ";" '{printf  $1 "%07d",$2 }{printf ";" $1 "%07d",$3}{print ";" $5 ";" $6}' | sed '1d '  >> def.csv
php gen_def.php
