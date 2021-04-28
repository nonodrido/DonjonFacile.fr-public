<?php

require_once(dirname(__FILE__)."/../pfccommand.class.php");

/**
 * Dice rolling, 
 * test routines at the end of this file
 *
 * @author   Alessandro Pasotti www.itopen.it
 * @copyright (C) itOpen 2006
 * @licence  LGPL
 * 
 * Valid strings:
 * xdx
 * xdxx
 * xdxxx
 * xdxxx+x
 * xdxxx-x
 */

class Dice {

  var $command;

  function check($text){
    $this->errors = array();
    $this->command= '';
    if(preg_match('/^([0-9]+)d([0-9]{1,3})([\+-][0-9]+)?$/', $text, $matches)){
      $this->command['launch'] = (int) $matches[1];
      $this->command['faces']  = (int) $matches[2];
      // Now go for corrections
      if(count($matches) == 4){
        $this->command['bias'] = $matches[3];
      }
      if(!($this->command['launch'] && $this->command['faces'])){
        //print_r($matches);
        $this->errors[] = "Sincèrement, pas de dé nul svp.";
        return false;
      }
    } else {
      //print_r($matches);
      // Too long
      //$this->errors[] = "'$text' is not a valid string for a dice launch. Valid strings match the following patterns xdyyy, xdyyy+z or xdyyy-z where x,  y and z are digits, you can have up to three y.";
      $this->errors[] = 'Non-valide. Les lancer valides sont du type xdyyy';
      return false;
    }
    $this->text    = $text;
    srand((double)microtime()*1000000);
    return true;
  }

  function roll(){
    $sum    = 0;
    $result = $this->text . ' &#187; ' ;  
    for($i = 0; $i < $this->command['launch']; $i++){
      $launchresult  = rand(1, $this->command['faces']);
      $sum          += $launchresult;
      $result       .= ' + ' . $launchresult;
    }
    if(count($this->command) == 3){
      $sum          += $this->command['bias'];
      $result       .= ' [' . $this->command['bias'] . ']';
    }
    return $result . ' = ' . '<strong>' . $sum . '</strong>';
  }

  function error_get(){
    if(!count($this->errors)){
      return '';
    } else {
      return join("<br />\n", $this->errors);
    }
  }
}

class pfcCommand_roll extends pfcCommand
{
  function run(&$xml_reponse, $p)
  {
    $clientid    = $p["clientid"];
    $param       = $p["param"];
    $sender      = $p["sender"];
    $recipient   = $p["recipient"];
    $recipientid = $p["recipientid"];
    
    $u =& pfcUserConfig::Instance();
    
    $nick = $u->nick;
    $ct   =& pfcContainer::Instance();
    $text = trim($param);
    
    // Call parse roll
    $dice = new Dice();
    if (!$dice->check($text))
    { 
      $result = $dice->error_get();
      $cmdp = $p;
      $cmdp["param"] = "Cmd_roll a échoué : " . $result;
      $cmd =& pfcCommand::Factory("error");
      $cmd->run($xml_reponse, $cmdp);
    }
    else
    {
      $result = $dice->roll();
      $ct->write($recipient, $nick, "send", $result);
    }
  }
}


?>