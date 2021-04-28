<?php  header('content-type: text/css');  
include('../includes/admin/f.php');
?>
/* /////////////////////////////////////////////////////////////////////////////////////////// */
/* /////////////////////////////////          CSS           ////////////////////////////////// */
/* /////////////////////////////////////////////////////////////////////////////////////////// */
@font-face{
    font-family : "DAUPHINN";

    src : url('../dauphinn-webfont.eot');
    src : url('../dauphinn-webfont.eot?') format('eot'),
          url('../dauphinn-webfont.ttf') format('truetype'),
		  url('../dauphinn-webfont.woff') format('woff'),
          url('../dauphinn-webfont.svg#abcd') format('svg');
}
@font-face{
    font-family : "BENGUIAB";

    src : url('../benguiab-webfont.eot');
    src : url('../benguiab-webfont.eot?') format('eot'),
          url('../benguiab-webfont.ttf') format('truetype'),
		  url('../benguiab-webfont.woff') format('woff'),
          url('../benguiab-webfont.svg#abcd') format('svg');
}
/*@font-face { 
  font-family: 'DAUPHINN'; 
  src: url('../DAUPHINN.TTF') format('truetype'); 
}
 @font-face { 
  font-family: 'FREESCPT'; 
  src: url('../FREESCPT.TTF') format('truetype'); 
}
@font-face { 
  font-family: 'LITHOGRB'; 
  src: url('../LITHOGRB.TTF') format('truetype'); 
}
@font-face { 
  font-family: 'LITHOGRL'; 
  src: url('../LITHOGRL.TTF') format('truetype'); 
} 
@font-face { 
  font-family: 'BENGUIAB'; 
  src: url('../BENGUIAB.TTF') format('truetype'); 
}*/
/* :target {
  padding-top: 45px;
} */
#scrolable_body{
	margin-top:50px;
	/* padding-bottom:50px;
	margin-bottom:-50px;  */
}
h1,h2,.benguiab{
	font-family: 'BENGUIAB', "Helvetica Neue", Helvetica, Arial, sans-serif;
}
h3,h4,h5,h6,.dauphinn{
	font-family: 'DAUPHINN', "Helvetica Neue", Helvetica, Arial, sans-serif;
}
legend{
	margin-bottom:0;
	font-family: 'DAUPHINN', "Helvetica Neue", Helvetica, Arial, sans-serif;
}
.center{
	text-align:center;
}
.black{
	color:black;
}
.black:hover{
	color:black;
}
.nav-header {
	font-size: small;
}
footer{
	background:#3D3D3D;/* #7C828D */
	color:whitesmoke;
	margin-top:15px;
	padding-top:20px;
}
fieldset{
	margin-top:5px;
	margin-bottom:5px;
}
#hide_bar_button{
	margin-bottom:10px;
}
.null_width{
	width:0;
	overflow: hidden;
}
.rssRow div{
	font-size:x-small;
	font-style:italic;
}
.rssRow p{
	font-size:small;
}
#feedback input, #feedback textarea{
	width:95%;
}
#custom-search-form {
	margin:0;
	margin-top: 5px;
	padding: 0;
    }
 
#custom-search-form .search-query {
	padding-right: 3px;
	padding-right: 4px \9;
	padding-left: 3px;
	padding-left: 4px \9;
	/* IE7-8 doesn't have border-radius, so don't indent the padding */

	margin-bottom: 0;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
}

#custom-search-form button {
	border: 0;
	background: none;
	/** belows styles are working good */
	padding: 2px 5px;
	margin-top: 2px;
	position: relative;
	left: -28px;
	/* IE7-8 doesn't have border-radius, so don't indent the padding */
	margin-bottom: 0;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
}

.search-query:focus + button {
	z-index: 3;   
}
#toTop {
	width:20px;
	border:2px solid #ccc;
	background:#f7f7f7;
	text-align:center;
	padding:5px;
	position:fixed; /* this is the magic */
	bottom:17px; /* together with this to put the div at the bottom*/
	right:10px;
	cursor:pointer;
	display:none;
	color:#333;
	font-family:verdana;
	font-size:11px;
}
 



/* //// useless */



/* .quadrillage{ /* affiche un quadrillage sur l'élément
background-image: -webkit-linear-gradient(rgba(0,0,255,.2) 2px, transparent 2px), -webkit-linear-gradient(0, rgba(0,0,255,.1) 2px, transparent 2px), -webkit-linear-gradient(rgba(0,0,255,.3) 1px, transparent 1px), -webkit-linear-gradient(0, rgba(0,0,255,.3) 1px, transparent 1px);
			background-image: -moz-linear-gradient(rgba(0,0,255,.2) 2px, transparent 2px), -moz-linear-gradient(0, rgba(0,0,255,.1) 2px, transparent 2px), -moz-linear-gradient(rgba(0,0,255,.3) 1px, transparent 1px), -moz-linear-gradient(0, rgba(0,0,255,.3) 1px, transparent 1px);
			background-image: -o-linear-gradient(rgba(0,0,255,.2) 2px, transparent 2px), -o-linear-gradient(0, rgba(0,0,255,.1) 2px, transparent 2px), -o-linear-gradient(rgba(0,0,255,.3) 1px, transparent 1px), -o-linear-gradient(0, rgba(0,0,255,.3) 1px, transparent 1px);
			background-image: -ms-linear-gradient(rgba(0,0,255,.2) 2px, transparent 2px), -ms-linear-gradient(0, rgba(0,0,255,.1) 2px, transparent 2px), -ms-linear-gradient(rgba(0,0,255,.3) 1px, transparent 1px), -ms-linear-gradient(0, rgba(0,0,255,.3) 1px, transparent 1px);
			-webkit-background-size:100px 100px, 100px 100px, 20px 20px, 20px 20px;
			-moz-background-size:100px 100px, 100px 100px, 20px 20px, 20px 20px;
			
			/* IN A PERFECT WORLD
			background-image: 
                          linear-gradient(rgba(0,0,255,.2) 2px, transparent 2px),
						  linear-gradient(0, rgba(0,0,255,.1) 2px, transparent 2px),
						  linear-gradient(rgba(0,0,255,.3) 1px, transparent 1px),
						  linear-gradient(0, rgba(0,0,255,.3) 1px, transparent 1px);
			background-size:100px 100px, 100px 100px, 20px 20px, 20px 20px;
			background-position:-2px -2px, -2px -2px, -1px -1px, -1px -1px;

}*/
table.table thead .sorting,
table.table thead .sorting_asc,
table.table thead .sorting_desc,
table.table thead .sorting_asc_disabled,
table.table thead .sorting_desc_disabled {
    cursor: pointer;
    *cursor: hand;
}
 
table.table thead .sorting { background: url('<?php base64_encode_image('./../ressources/img/img_css/sort_both.png','png');?>') no-repeat center right; }
table.table thead .sorting_asc { background: url('<?php base64_encode_image('./../ressources/img/img_css/sort_asc.png','png');?>') no-repeat center right; }
table.table thead .sorting_desc { background: url('<?php base64_encode_image('./../ressources/img/img_css/sort_desc.png','png');?>') no-repeat center right; }
 
table.table thead .sorting_asc_disabled { background: url('<?php base64_encode_image('./../ressources/img/img_css/sort_asc_disabled.png','png');?>') no-repeat center right; }
table.table thead .sorting_desc_disabled { background: url('<?php base64_encode_image('./../ressources/img/img_css/sort_desc_disabled.png','png');?>') no-repeat center right; }
.stacked { /* affiche 3 éléments type photo qui s'écartent */
    background: #f6f6f6;
    border: 1px solid #ccc;
    height: 250px;
    margin: 50px auto;
    position: relative;
    width: 400px;
    -webkit-box-shadow: 0 0 3px hsla(0,0%,0%,.1);
       -moz-box-shadow: 0 0 3px hsla(0,0%,0%,.1);
            box-shadow: 0 0 3px hsla(0,0%,0%,.1);
}
.stacked:after,
.stacked:before {
    background: #f6f6f6;
    border: 1px solid #ccc;
    bottom: -3px;
    content: '';
    height: 250px;
    left: 2px;
    position: absolute;
    width: 394px;
    z-index: -10;
    -webkit-box-shadow: 0 0 3px hsla(0,0%,0%,.2);
       -moz-box-shadow: 0 0 3px hsla(0,0%,0%,.2);
            box-shadow: 0 0 3px hsla(0,0%,0%,.2);
}
.stacked:before {
    bottom: -5px;
    left: 5px;
    width: 388px;
}
.stacked:hover:after {
    -webkit-transform: rotate(-3deg) translate(-25px,0);
       -moz-transform: rotate(-3deg) translate(-25px,0);
        -ms-transform: rotate(-3deg) translate(-25px,0);
         -o-transform: rotate(-3deg) translate(-25px,0);
            transform: rotate(-3deg) translate(-25px,0);
}
.stacked:hover:before {
    -webkit-transform: rotate(3deg) translate(25px,0);
       -moz-transform: rotate(3deg) translate(25px,0);
        -ms-transform: rotate(3deg) translate(25px,0);
         -o-transform: rotate(3deg) translate(25px,0);
            transform: rotate(3deg) translate(25px,0);
}
.papers {
  background: #EFEAE0;/* #E7E7D2 */
  padding: 20px;
  padding-top: 1px;padding-bottom: 1px;
  margin-bottom:20px;
  border-top: 1px solid rgba(0, 0, 0, .2);
  border-left: 1px solid rgba(0, 0, 0, .2);
  width: 90%;
  -moz-box-shadow:     1px 1px 0 rgba(0, 0, 0, .2),
                       3px 3px 0 #EFEAE0,
                       4px 4px 0 rgba(0, 0, 0, .2),
                       6px 6px 0 #EFEAE0,
                       7px 7px 0 rgba(0, 0, 0, .1),
                       9px 9px 0 #EFEAE0,
                       10px 10px 0 rgba(0, 0, 0, .1);
  -webkit-box-shadow:  1px 1px 0 rgba(0, 0, 0, .2),
                       3px 3px 0 #EFEAE0,
                       4px 4px 0 rgba(0, 0, 0, .2),
                       6px 6px 0 #EFEAE0,
                       7px 7px 0 rgba(0, 0, 0, .1),
                       9px 9px 0 #EFEAE0,
                       10px 10px 0 rgba(0, 0, 0, .1);
  box-shadow:          1px 1px 0 rgba(0, 0, 0, .2),
                       3px 3px 0 #EFEAE0,
                       4px 4px 0 rgba(0, 0, 0, .2),
                       6px 6px 0 #EFEAE0,
                       7px 7px 0 rgba(0, 0, 0, .1),
                       9px 9px 0 #EFEAE0,
                       10px 10px 0 rgba(0, 0, 0, .1);z
}
.papers  h2
{
	font-size : 22px;
	margin-top : 3px;
	height: 38px;
   padding-left: 39px;
   color: black;
   text-align: left;
   font-family: 'DAUPHINN', cursive;
}
.papers  h2 a
{
   font-family: 'DAUPHINN', cursive;
}
.papers  h2 a:hover
{
   font-family: 'DAUPHINN', cursive;
   text-decoration:underline;
}
.papers .news
{
	background-image: url(<?php base64_encode_image('./../ressources/img/img_css/titre.png','png');?>); /* Une petite image de fond sur les titres h2 */
	background-repeat: no-repeat; /* L'image ne se répètera pas, elle sera à gauche du titre */
	font-family: 'DAUPHINN', cursive;
}
.info-box {border:1px solid #AFDBEE; background: url(<?php base64_encode_image('./../ressources/img/img_css/info.png','png');?>) no-repeat scroll 8px 55% #E4F5FD; padding:10px 10px 10px 35px; margin:0; color:#2A80A7; font-size:13px; position:relative;}
.info-box a {color:#2A80A7; border-bottom:#2A80A7 1px solid;}
.info-box a:hover {color:#2A80A7; border-bottom:none !important;}

.warning-box {border:1px solid #efdc75; background: url(<?php base64_encode_image('./../ressources/img/img_css/warning.png','png');?>) no-repeat scroll 8px 55% #fff7cb; padding:10px 10px 10px 35px; margin:0; color:#DB7701; font-size:13px; position:relative;}
.warning-box a {color:#DB7701; border-bottom:#DB7701 1px solid;}
.warning-box a:hover {color:#DB7701; border-bottom:none !important;}

.success-box {border:1px solid #b3dc7c; background: url(<?php base64_encode_image('./../ressources/img/img_css/success.png','png');?>) no-repeat scroll 8px 55% #e8ffca; padding:10px 10px 10px 35px; margin:0; color:#527A19; font-size:13px; position:relative;}
.success-box a {color:#527A19; border-bottom:#527A19 1px solid;}
.success-box a:hover {color:#527A19; border-bottom:none !important;}

.error-box {border:1px solid #ebb1b1; background: url(<?php base64_encode_image('./../ressources/img/img_css/error.png','png');?>) no-repeat scroll 8px 55% #ffd6d6; padding:10px 10px 10px 35px; margin:0; color:#9d2121; font-size:13px; position:relative;}
.error-box a {color:#9d2121; border-bottom:#9d2121 1px solid;}
.error-box a:hover {color:#9d2121; border-bottom:none !important;}

.info-box, .warning-box, .success-box, .error-box {margin-bottom:20px; margin-top:5px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; overflow:hidden;}

.code {
  -webkit-box-shadow:#000000 1px 1px 5px;
  background-attachment:initial;
  background-clip:initial;
  background-color:#E4E8E8;
  background-image:initial;
  background-origin:initial;
  background-position:initial initial;
  background-repeat:initial initial;
  box-shadow:#000000 1px 1px 5px;
  margin-bottom:3px;
  margin-left:7px;
  margin-right:7px;
  margin-top:5px;
  // overflow-x:auto;
  overflow-y:auto;
  padding-bottom:3px;
  padding-left:7px;
  padding-right:7px;
  padding-top:3px;
}
code:before {
				position: absolute;
				content: 'Code:';
				top: -1.35em;
				left: 0;
			}
			code {
				margin-top: 1.5em;
				position: relative;
				background: #eee;
				border: 1px solid #aaa;
				white-space: pre;
				padding: .25em;
				min-height: 1.25em;
			}
			code:before, code {
				display: block;
				text-align: left;
			}