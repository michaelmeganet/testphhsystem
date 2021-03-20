<?PHP
/*
Array ( 
[qid] => 1 INT
[bid] => 1 INT
[currency] => 1 INT
[quono] => ASL 1911 001 VARCHAR
[pagetype] => normal VARCHAR
[custype] => local VARCHAR
[cusstatus] => active  VARCHAR
[cid] => 21089 INT
[accno] => 310A/079 VARCHAR
[date] => 2019-11-01 DATE
[terms] => 15 Days VARCHAR
[item] => 1 VARCHAR
[quantity] => 4 INT
[grade] => 12379s VARCHAR
[mdt] => 28 VARCHAR
[mdw] => VARCHAR
mdl] => 3000 VARCHAR
[fdt] => VARCHAR
[fdw] => VARCHAR
[fdl] => 2995 VARCHAR
[process] => 1 VARCHAR
[mat] => 294.80 DECIMAL
[pmach] => 0.00 DECIMAL
[cncmach] => 0.00 DECIMAL
[other] => 0.00 DECIMAL
[unitprice] => 0.00 DECIMAL
[amount] => 0.00 DECIMAL
[discount] => 0.00 DECIMAL
[vat] => 0.00 DECIMAL
[gst] => 0.00 DECIMAL
[ftz] => SR VARCHAR
[amountmat] => 1179.20 DECIMAL
[discountmat] => 0.00 DECIMAL
[gstmat] => 0.00 DECIMAL
[totalamountmat] => 1179.20 DECIMAL
[amountpmach] => 0.00 DECIMAL
[discountpmach] => 0.00 DECIMAL
[gstpmach] => 0.00 DECIMAL
[totalamountpmach] => 0.00 DECIMAL
[amountcncmach] => 0.00 DECIMAL
[discountcncmach] => 0.00 DECIMAL
[gstcncmach] => 0.00 DECIMAL
[totalamountcncmach] => 0.00 DECIMAL
[amountother] => 0.00 DECIMAL
[discountother] => 0.00 DECIMAL
[gstother] => 0.00 DECIMAL
[totalamountother] => 0.00 DECIMAL
[totalamount] => 1179.20 DECIMAL
[mat_disc] => 0.00 DECIMAL
[pmach_disc] => 0.00 DECIMAL
[aid_quo] => 154 INT
[aid_cus] => 105 INT
[datetimeissue] => 2019-11-01 08:01:44 DATETIME
[odissue] => issued ) VARCHAR

*/

$quotationArray = array_fill_keys(
   array(        
    'qid'  ,
    'bid'  ,
    'currency', 
    'quono' ,
    'pagetype' ,
    'custype' ,
    'cusstatus' ,
    'cid' ,
    'accno' ,
    'date' ,
    'terms' ,
    'item' ,
    'quantity' ,
    'grade' ,
    'mdt' ,
    'mdw' ,
    'mdl' ,
    'fdt' ,
    'fdw' ,
    'fdl' ,
    'process' ,
    'mat' ,
    'pmach' ,
    'cncmach' ,
    'other' ,
    'unitprice' ,
    'amount' ,
    'discount' ,
    'vat' ,
    'gst' ,
    'ftz' ,
    'amountmat' ,
    'discountmat' ,
    'gstmat' ,
    'totalamountmat' ,
    'amountpmach'  ,
    'discountpmach' ,
    'gstpmach' ,
    'totalamountpmach' ,
    'amountcncmach' ,
    'discountcncmach' ,
    'gstcncmach' ,
    'totalamountcncmach' ,
    'amountother' ,
    'discountother' ,
    'gstother' ,
    'totalamountother' ,
    'totalamount' ,
    'mat_disc' ,
    'pmach_disc' ,
    'aid_quo' ,
    'aid_cus' ,
    'datetimeissue'  ,
    'odissue' 
), '');

echo 'echo \$quotationArray below : <br>';
print_r($quotationArray);

