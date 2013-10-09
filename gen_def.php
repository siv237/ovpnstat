<?php
                $temp = array_merge( file('def.csv') );
                $strBase = array();
                foreach($temp as $one)
                        {
                        $one = explode(';', $one);
                        $strBase[ $one[0][0] ][ $one[0][1] ][ $one[0][2] ][] = implode(';',$one);
                        }
                unset($temp);
                foreach($strBase as $key1=>$one1)
                foreach($one1 as $key2=>$one2)
                foreach($one2 as $key3=>$one3)
                        file_put_contents('./def_php/'.$key1.$key2.$key3.'.data', implode("",$one3) );

?>
