<?php

class Utils
{
	
	public static function in_arrayi($needle, $haystack)
	{
		return in_array(strtolower($needle), array_map('strtolower', $haystack));
	}

	/*
    public static function generateSlug($value){
        $string = preg_replace("/['’]/", ' ', $value);
        $string = transliterator_transliterate("Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();", $string);
        $string = preg_replace('/[-\s]+/', '-', $string);
        return trim($string, '-');
    }
*/

    public static function generateSlug($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }


    public static function verifUploadMulti($index, $i, $maxsize=FALSE,$extensions=FALSE){
        //Test1: fichier correctement uploadé
        if (!isset($_FILES[$index]) OR $_FILES[$index]['error'][$i] > 0) return FALSE;
        //Test2: taille limite
        if ($maxsize !== FALSE AND $_FILES[$index]['size'][$i] > $maxsize) return FALSE;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $ext = finfo_file($finfo, $_FILES[$index]['tmp_name'][$i].$filename);

        if ($ext !== FALSE AND !Utils::in_arrayi($ext,$extensions)) return FALSE;
        //Déplacement

        return true;
    }
    public static function uploadMulti($index, $i, $destination,$maxsize=FALSE,$extensions=FALSE){

        $maxsize = $maxsize ? Utils::getConf()['import']['maxsize'] : $maxsize;
        if(Utils::verifUploadMulti($index, $i, $maxsize,$extensions))
            return move_uploaded_file($_FILES[$index]['tmp_name'][$i],$destination);

    }

    public static function message($return, $messageSucces, $messagefail){
        if($return>0)
            $_SESSION['message']['success'] []= $messageSucces;
        else
            $_SESSION['message']['fail'] []= $messagefail;
    }


    public static function emailBody($idDemandeNew, $nom, $prenom, $genre, $naissance, $secu, $employeur, $email_bdd, $tel, $famille, $motif, $email, $message){
        
		// if($genre =='Mme' || $genre =='Mlle')
			// $genre = MAILSECURE_GENRE1;
		// elseif($genre =='M' || $genre =='M.')
			// $genre = MAILSECURE_GENRE2;
		// else
			// $genre = MAILSECURE_GENRE3;
		
		$messageEmail = '<body style="font-family:Arial,Verdana,sans-serif;">
       <div style="color:#666666;font-size:12px;padding:15px;width:800px;">
    <div style="padding-top:10px; margin-bottom:15px;">
     <div style="float:left;">
       <img style="float:left;" src="http://www.vivinter.fr/images/logo/logo_vivinter.jpg" alt="vivinter" />
        </div>
    
            <div style="margin-bottom: 5px;text-align: right;width: 100%;font-weight:bold;background-color:#ef7d0b;float:right;color: black;padding:0 10px 0;height: 15px;">
                
            </div>
    </div>
	<br/>
    <div>
    
    
        <h1 style="border-left-color:#EF7D0B;border-left-style:solid;border-left-width:25px;clear: both;color: white;line-height:24px;height:25px;margin-bottom:15px;padding-left:10px;font-size:14pt;font-weight:bold;background-color:#737172;">
        
        Site VIVINTER - '.FORM_MAIL.'
        
        </h1>
    
        <h2 style="border-bottom:2px solid #AAAAAA;color:#AAAAAA;font-size:11pt;font-weight:bold;margin-bottom:10px;padding-bottom:0pt;padding-top:5px;text-align:left;">'.ASSURE_MAIL.'</h2>
    
        <div style="margin-bottom:10px;">
         <div style="padding-bottom:5px;">
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.NOM_MAIL.' :</span><span style="padding:1px;">'.$genre.' '.$prenom.' '.$nom.'</span>
         </div>
         <div style="padding-bottom:5px;">
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.NAISSANCE_MAIL.' :</span><span style="padding:1px;">'.$naissance.'</span>
         </div>
    
         <div style="padding-bottom:5px;">
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.NUM_ASSUR_MAIL.' :</span><span style="padding:1px;">'.$secu.'</span>
         </div>
    
         <div style="padding-bottom:5px;">
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.EMPLOYEUR_MAIL.' :</span><span style="padding:1px;">'.$employeur.'</span>
         </div>
    
         <div style="padding-bottom:5px;">
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.MAIL_MAIL.' :</span><span style="padding:1px;">'.$email_bdd.'</span>
         </div>
    
         <div style="padding-bottom:5px;">
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.TEL_MAIL.' :</span><span style="padding:1px;">'.$tel.'</span>
         </div>
        </div>
    
        <h2 style="border-bottom:2px solid #AAAAAA;color:#AAAAAA;font-size:11pt;font-weight:bold;margin-bottom:10px;padding-bottom:0pt;padding-top:5px;text-align:left;">'.MESSAGE_MAIL.'</h2>
    
            <div style="margin-bottom:10px;">
             <div style="padding-bottom:5px;">
              <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.NUM_MAIL.' :</span><span style="padding:1px;">'.$idDemandeNew.'</span>
             </div>
             <div style="padding-bottom:5px;">
              <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.MOTIF_MAIL.' :</span><span style="padding:1px;">'.$famille.' > '.$motif.'</span>
             </div>
        
             <div style="padding-bottom:5px;">
              <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.MAIL_CONTACT_MAIL.' :</span><span style="padding:1px;">'.$email.'</span>
             </div>
        
             <div style="padding-bottom:5px;">
              <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.PRECISION_MAIL.' :</span>
             </div>
			 <div style="clear:both;">
			  <br/>
			  '.nl2br($message).'
			 </div>
            </div>
    
       </div>
      </div>
     </body>    
    ';
        return $messageEmail;
    }


    public static function emailSecurityBody($nom, $prenom, $genre, $email){
		
		if($genre =='Mme' || $genre =='Mlle')
			$genre = MAILSECURE_GENRE1;
		elseif($genre =='M' || $genre =='M.')
			$genre = MAILSECURE_GENRE2;
		else
			$genre = MAILSECURE_GENRE3;
		
		$date = date('d/m/Y');
		$heure = date("H:i");
		
        $messageEmail = '<body style="font-family:Arial,Verdana,sans-serif;">
       <div style="color:#666666;font-size:12px;padding:15px;width:800px;">
    <div style="padding-top:10px; margin-bottom:15px;">
     <div style="float:left;">
       <img style="float:left;" src="http://www.vivinter.fr/images/logo/logo_vivinter.jpg" alt="vivinter" />
        </div>
    
        <div style="background-color:#ef7d0b;color:white;padding:0 10px 0;clear:both;margin-top:5px;font-weight:bold;line-height:25px;height:25px;font-size:18px;">
            <div style="float:left;">
                '.$prenom.' '.$nom.'
            </div>
            <span style="clear:both"></span>
        </div>
    </div>
    <div>
    
    
        <h1 style="border-left-color:#EF7D0B;border-left-style:solid;border-left-width:25px;color:white;line-height:24px;height:25px;margin-bottom:15px;padding-left:10px;font-size:14pt;font-weight:bold;background-color:#737172;">
        
        '.MAILSECURE_TITRE.'
        
        </h1>    
      
            <div style="margin-bottom:10px;">
             <div>
              <p>'.$genre.',</p>
             </div>
        
             <div>
              <p>'.sprintf(MAILSECURE_BODY, $date, $heure).'</p>
             </div>
        
             <div>
              <p><b>'.$email.'</b></p>
			  <p>'.MAILSECURE_BODY2.'</p>
			  <p>'.MAILSECURE_BODY3.'</p>
			  <p>'.MAILSECURE_CORDIAL.',</p>
			  <p>'.MAILSECURE_EQUIPE.',</p>
             </div>
        
            </div>
    <div style="text-align:center;font-size:0.8em;"><i>-'.MAILSECURE_FOOTER.'-</i></div>
       </div>
      </div>
     </body>    
    ';
        return $messageEmail;
    }


    public static function messageBody($nom, $prenom, $genre, $naissance, $secu, $employeur, $email, $tel, $famille, $motif, $emailContact, $message, $pieces_jointes){
        $messageEmail = '<p>'.DEMANDE_RESP.' :</p><div>
    
        <h2 style="border-bottom:2px solid #AAAAAA;color:#AAAAAA;font-size:11pt;font-weight:bold;margin-bottom:10px;padding-bottom:0pt;padding-top:5px;text-align:left;">'.ASSURE_MAIL.'</h2>
    
        <div style="margin-bottom:10px;">
         <div>
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.NOM_MAIL.' :</span><span style="padding:1px;">'.$genre.' '.$prenom.' '.$nom.'</span>
         </div>
    
         <div>
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.NAISSANCE_MAIL.' :</span><span style="padding:1px;">'.$naissance.'</span>
         </div>
    
         <div>
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.NUM_MAIL.' :</span><span style="padding:1px;">'.$secu.'</span>
         </div>
    
         <div>
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.EMPLOYEUR_MAIL.' :</span><span style="padding:1px;">'.$employeur.'</span>
         </div>
    
         <div>
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.MAIL_MAIL.' :</span><span style="padding:1px;">'.$email.'</span>
         </div>
    
         <div>
          <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.TEL_MAIL.' :</span><span style="padding:1px;">'.$tel.'</span>
         </div>
    
        <h2 style="border-bottom:2px solid #AAAAAA;color:#AAAAAA;font-size:11pt;font-weight:bold;margin-bottom:10px;padding-bottom:0pt;padding-top:5px;text-align:left;">'.MESSAGE_MAIL.'</h2>
    
            <div style="margin-bottom:10px;">
             <div>
              <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.MOTIF_MAIL.' :</span><span style="padding:1px;">'.$famille.' > '.$motif.'</span>
             </div>
        
             <div>
              <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.MAIL_CONTACT_MAIL.' :</span><span style="padding:1px;">'.$emailContact.'</span>
             </div>
			 
			 <div>
              <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.PRECISION_MAIL.' :</span>
             </div>
			<div style="clear:both;">
			<br/>
			'.nl2br($message).'
			</div>
        
            </div>
    
        <h2 style="border-bottom:2px solid #AAAAAA;color:#AAAAAA;font-size:11pt;font-weight:bold;margin-bottom:10px;padding-bottom:0pt;padding-top:5px;text-align:left;">'.PIECES_RESP.'</h2>
		';
		if(count($pieces_jointes)>0){
			foreach($pieces_jointes as $pj){
				$messageEmail.='<div style="margin-bottom:10px;">
				 <div>
				  <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.NOM_MAIL.' :</span><span style="padding:1px;">'.$pj["name"].'</span>
				 </div>
			
				 <div>
				  <span style="float:left;width:250px;padding-right:5px;text-align:left;">'.TAILLE_RESP.' :</span><span style="padding:1px;">'.($pj["size"]/1000).' Kb</span>
				 </div>
			
				</div>';
			}
            
		}
            
    
       $messageEmail.='</div>
      </div> 
    ';
        return $messageEmail;
    }

    protected static $_conf = null;

    public static function getConf(){
        if(is_null(self::$_conf)) {
            // parses the settings file
            self::$_conf = parse_ini_file(ROOT_PATH . '/config/settings.ini', true);
        }
        return self::$_conf;
    }

    protected static $_dbh = null;

    public static function getDbo(){
        if(is_null(self::$_dbh)) {
            // parses the settings file
            $settings = self::getConf();

            // starts the connection to the database
            self::$_dbh = new PDO(
                sprintf(
                    "%s:host=%s;dbname=%s",
                    $settings['database_mysql']['driver'],
                    $settings['database_mysql']['host'],
                    $settings['database_mysql']['dbname']
                ),
                $settings['database_mysql']['user'],
                $settings['database_mysql']['password'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
        }
        return self::$_dbh;
    }

    const FAIL_MSG = 'fail';
    const SUCCESS_MSG = 'success';
    const NOTICE_MSG = 'notice';

    public static function addMsg($msg,$type = "success"){
        $_SESSION['message'][$type][] = $msg;
    }

    public static function getMsg($type = "success"){
        return $_SESSION['message'][$type];
    }

    public static function resetMsg($type = null){
        if(is_null($type)){
            unset($_SESSION['message']);
        }else{
            unset($_SESSION['message'][$type]);
        }
    }

    public static function checkGuestAccess(){
        $user = self::getCurrentUser();
        return $user ? false : true;
    }

    public static function checkUserAccess()
    {
        return self::checkAccess('USER');
    }

    public static function checkAdminAccess()
    {
        return self::checkAccess('ADMIN');
    }

    public static function checkAccess($access)
    {
        $user = self::getCurrentUser();
        $url =  $_SERVER['HTTP_HOST'].self::getConf()['security']['urllogin'];
        if (!user){
            self::addMsg("Vous devez vous connectez pour pouvoir accéder à cette ressource",self::FAIL_MSG );
            header('Location:http://'.$url);
            die();
        }
        // check rights
        if($user->role != "ADMIN"){
            self::addMsg("Vous n'avez pas les autorisations nécessaires pour accéder à cette ressource",self::FAIL_MSG );
            header('Location:http://'.$_SERVER['HTTP_HOST'].'/');
            die();
        }
        return true;
    }


    public static function getCurrentUser(){
        return $_SESSION['user'];
    }

    public static function sendMail($msg,$dest,$from){
        // make an insert into table histoMail
    }
}

