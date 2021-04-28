 <?php 
$titre='Chat';
if(isuser())
{
// verif_uri('/chat');
echo '<iframe src="/chat.php" style="width:100%;height:1000px;border:none;" seamless></iframe>';
}
else{echo ACCES_REFUSE_INVITE;$header='<meta name="robots" content="noindex,follow" />';}