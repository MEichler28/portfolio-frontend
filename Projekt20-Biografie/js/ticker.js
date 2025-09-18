<!-- begin// global variables

// 1996 von Christoph Bergmann... http://acc.de/cb
// Es waere nett, wenn dieser Vermerk drin bliebe...

var max=0;

function textlist()
{
max=textlist.arguments.length;
for (i=0; i<max; i++)
this[i]=textlist.arguments[i];
}

tl=new textlist
(
"Please enter the Password",
"**********",
"Access denied!",
"**********",
"Accepted! Welcome to Anderworld!"
);

var x=0; pos=0;
var l=tl[0].length;

function textticker() 
{

document.form1.textfeld.value=tl[x].substring(0,pos)+"_";

if(pos++==l)
{
pos=0;
setTimeout("textticker()",1500);
x++;
if(x==max)
x=0;
l=tl[x].length;
} else
setTimeout("textticker()",50);
}
// end -->